<?php

namespace App\Services;

use App\Models\HostingAccount;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class HostingFileManagerService
{
    /**
     * Extensões permitidas para edição direta no navegador.
     */
    protected array $editableExtensions = [
        'html', 'htm', 'php', 'css', 'js', 'json', 'sql', 'txt', 'md',
        'env', 'xml', 'htaccess', 'svg', 'ini', 'yaml', 'yml', 'config'
    ];

    /**
     * Retorna o caminho absoluto do diretório raiz da hospedagem.
     * Inicializa com arquivos padrão caso ainda não exista.
     */
    public function getRootPath(HostingAccount $hosting): string
    {
        $domain = strtolower(trim($hosting->domain));
        $root = public_path("published_sites/{$domain}");

        if (!File::exists($root)) {
            File::makeDirectory($root, 0755, true);
            $this->seedInitialFiles($hosting, $root);
        }

        return realpath($root) ?: $root;
    }

    /**
     * Valida e resolve um caminho relativo para dentro da raiz segura do domínio.
     * Previne directory traversal (ex: ../../).
     */
    public function resolveSecurePath(HostingAccount $hosting, string $relativePath = ''): ?string
    {
        $root = $this->getRootPath($hosting);
        $cleanPath = trim(str_replace(['\\', '..', chr(0)], ['/', '', ''], $relativePath), '/');
        
        $target = $cleanPath ? "{$root}/{$cleanPath}" : $root;

        // Se o arquivo/pasta já existe, valida com realpath
        if (File::exists($target)) {
            $realTarget = realpath($target);
            if ($realTarget && str_starts_with($realTarget, $root)) {
                return $realTarget;
            }
            return null;
        }

        // Se for um novo arquivo ou pasta ainda a ser criado, valida o diretório pai
        $parentDir = dirname($target);
        $realParent = realpath($parentDir);
        if ($realParent && str_starts_with($realParent, $root)) {
            return $target;
        }

        return null;
    }

    /**
     * Lista arquivos e pastas de um diretório na hospedagem.
     */
    public function listFiles(HostingAccount $hosting, string $subpath = ''): array
    {
        $targetDir = $this->resolveSecurePath($hosting, $subpath);
        if (!$targetDir || !File::isDirectory($targetDir)) {
            return [
                'current_path' => '',
                'items' => [],
                'total_files' => 0,
                'total_size' => '0 B',
            ];
        }

        $root = $this->getRootPath($hosting);
        $relativeCurrent = trim(str_replace($root, '', $targetDir), '/\\');
        
        $entries = scandir($targetDir);
        $items = [];
        $totalBytes = 0;

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $fullEntryPath = "{$targetDir}/{$entry}";
            $isDir = is_dir($fullEntryPath);
            $size = $isDir ? 0 : (filesize($fullEntryPath) ?: 0);
            $totalBytes += $size;

            $relEntryPath = $relativeCurrent ? "{$relativeCurrent}/{$entry}" : $entry;
            $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));

            $items[] = [
                'name' => $entry,
                'path' => $relEntryPath,
                'is_dir' => $isDir,
                'size' => $size,
                'formatted_size' => $isDir ? '-' : $this->formatBytes($size),
                'extension' => $ext,
                'is_editable' => !$isDir && in_array($ext, $this->editableExtensions),
                'is_zip' => !$isDir && $ext === 'zip',
                'modified_at' => date('d/m/Y H:i', filemtime($fullEntryPath)),
            ];
        }

        // Ordena: pastas primeiro, depois arquivos alfabeticamente
        usort($items, function ($a, $b) {
            if ($a['is_dir'] !== $b['is_dir']) {
                return $a['is_dir'] ? -1 : 1;
            }
            return strcasecmp($a['name'], $b['name']);
        });

        return [
            'current_path' => $relativeCurrent,
            'items' => $items,
            'total_files' => count($items),
            'total_size' => $this->formatBytes($totalBytes),
        ];
    }

    /**
     * Lê o conteúdo de um arquivo de texto/código.
     */
    public function readFile(HostingAccount $hosting, string $filepath): ?string
    {
        $realPath = $this->resolveSecurePath($hosting, $filepath);
        if (!$realPath || !File::isFile($realPath)) {
            return null;
        }

        // Limite de 5MB para edição direta
        if (filesize($realPath) > 5 * 1024 * 1024) {
            return "// Arquivo muito grande para abrir no editor online (limite: 5MB).";
        }

        return File::get($realPath);
    }

    /**
     * Salva o conteúdo editado em um arquivo existente ou novo.
     */
    public function saveFile(HostingAccount $hosting, string $filepath, string $content): bool
    {
        $target = $this->resolveSecurePath($hosting, $filepath);
        if (!$target) {
            return false;
        }

        $dir = dirname($target);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        return File::put($target, $content) !== false;
    }

    /**
     * Cria um novo arquivo no diretório especificado.
     */
    public function createFile(HostingAccount $hosting, string $subpath, string $filename, string $initialContent = ''): bool
    {
        $safeName = basename(trim($filename));
        if (empty($safeName)) {
            return false;
        }

        $relPath = trim($subpath, '/') ? trim($subpath, '/') . "/{$safeName}" : $safeName;
        $target = $this->resolveSecurePath($hosting, $relPath);
        if (!$target || File::exists($target)) {
            return false;
        }

        return File::put($target, $initialContent) !== false;
    }

    /**
     * Cria uma nova pasta.
     */
    public function createFolder(HostingAccount $hosting, string $subpath, string $folderName): bool
    {
        $safeName = basename(trim($folderName));
        if (empty($safeName)) {
            return false;
        }

        $relPath = trim($subpath, '/') ? trim($subpath, '/') . "/{$safeName}" : $safeName;
        $target = $this->resolveSecurePath($hosting, $relPath);
        if (!$target || File::exists($target)) {
            return false;
        }

        return File::makeDirectory($target, 0755, true);
    }

    /**
     * Realiza o upload de um arquivo para o diretório de destino.
     */
    public function uploadFile(HostingAccount $hosting, string $subpath, UploadedFile $file): array
    {
        $targetDir = $this->resolveSecurePath($hosting, $subpath);
        if (!$targetDir || !File::isDirectory($targetDir)) {
            return ['success' => false, 'message' => 'Diretório de destino inválido.'];
        }

        $filename = $file->getClientOriginalName();
        // Remove caracteres perigosos mantendo extensão
        $filename = preg_replace('/[^a-zA-Z0-9_\.\-]/', '_', $filename);

        $destinationPath = "{$targetDir}/{$filename}";
        
        try {
            $file->move($targetDir, $filename);
            return [
                'success' => true,
                'filename' => $filename,
                'path' => trim($subpath, '/') ? trim($subpath, '/') . "/{$filename}" : $filename,
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Extrai um arquivo .ZIP diretamente no diretório onde se encontra.
     */
    public function extractZip(HostingAccount $hosting, string $filepath): array
    {
        $zipRealPath = $this->resolveSecurePath($hosting, $filepath);
        if (!$zipRealPath || !File::isFile($zipRealPath)) {
            return ['success' => false, 'message' => 'Arquivo ZIP não encontrado.'];
        }

        $destDir = dirname($zipRealPath);
        $zip = new ZipArchive();
        $res = $zip->open($zipRealPath);

        if ($res === true) {
            $zip->extractTo($destDir);
            $zip->close();
            return ['success' => true, 'message' => 'Arquivo ZIP descompactado com sucesso!'];
        }

        return ['success' => false, 'message' => 'Não foi possível extrair o arquivo compactado. Código de erro: ' . $res];
    }

    /**
     * Exclui um arquivo ou diretório recursivamente.
     */
    public function deleteItem(HostingAccount $hosting, string $path): bool
    {
        $target = $this->resolveSecurePath($hosting, $path);
        if (!$target) {
            return false;
        }

        $root = $this->getRootPath($hosting);
        // Previne exclusão da raiz da hospedagem
        if ($target === $root) {
            return false;
        }

        if (File::isDirectory($target)) {
            return File::deleteDirectory($target);
        }

        return File::delete($target);
    }

    /**
     * Gera um backup completo (.ZIP) de todos os arquivos da hospedagem.
     */
    public function exportFullBackup(HostingAccount $hosting): ?string
    {
        $root = $this->getRootPath($hosting);
        $domain = strtolower(trim($hosting->domain));
        $timestamp = date('Y-m-d_His');
        $zipName = "backup_{$domain}_{$timestamp}.zip";

        $backupsDir = storage_path('app/backups');
        if (!File::exists($backupsDir)) {
            File::makeDirectory($backupsDir, 0755, true);
        }

        $zipPath = "{$backupsDir}/{$zipName}";

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return null;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($root) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return File::exists($zipPath) ? $zipPath : null;
    }

    /**
     * Formata bytes em formato legível (KB, MB, GB).
     */
    public function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Cria os arquivos iniciais para uma hospedagem recém-provisionada.
     */
    protected function seedInitialFiles(HostingAccount $hosting, string $rootPath): void
    {
        $domain = $hosting->domain;
        $clientName = $hosting->client ? $hosting->client->name : 'Cliente';

        $indexHtml = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$domain} &bull; Hospedado com HostDevPro Cloud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 0%, #0d1b2a 0%, #030712 100%);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 28px;
            padding: 48px;
            max-width: 680px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 9999px;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 24px;
        }
        .pulse { width: 8px; height: 8px; border-radius: 50%; background: #34d399; box-shadow: 0 0 12px #34d399; }
        h1 { font-size: 32px; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 12px; }
        p { color: #94a3b8; font-size: 15px; line-height: 1.6; margin-bottom: 32px; }
        .details {
            background: rgba(3, 7, 18, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 20px;
            text-align: left;
            font-size: 13px;
            margin-bottom: 32px;
        }
        .row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.06); }
        .row:last-child { border-bottom: none; }
        .label { color: #64748b; }
        .value { color: #38bdf8; font-family: monospace; font-weight: 600; }
        .footer { font-size: 12px; color: #64748b; }
        .footer a { color: #10b981; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <div class="badge">
            <span class="pulse"></span>
            Hospedagem HostDevPro Ativa
        </div>
        <h1>{$domain}</h1>
        <p>Seu servidor web e zona DNS foram configurados com sucesso. Este é o arquivo padrão <code>index.html</code>. Você pode substituí-lo a qualquer momento enviando seus arquivos ou editando diretamente no Gerenciador de Arquivos do Painel.</p>
        
        <div class="details">
            <div class="row">
                <span class="label">Domínio Principal:</span>
                <span class="value">{$domain}</span>
            </div>
            <div class="row">
                <span class="label">Proprietário:</span>
                <span class="value">{$clientName}</span>
            </div>
            <div class="row">
                <span class="label">Infraestrutura:</span>
                <span class="value">HostDevPro NVMe Cloud VPS</span>
            </div>
            <div class="row">
                <span class="label">Certificado SSL:</span>
                <span class="value" style="color: #34d399;">Ativo (HTTPS Automático)</span>
            </div>
        </div>

        <div class="footer">
            Gerenciado com excelência por <a href="https://hostdevpro.app.br" target="_blank">HostDevPro Cloud Solutions</a>.
        </div>
    </div>
</body>
</html>
HTML;

        File::put("{$rootPath}/index.html", $indexHtml);
        File::put("{$rootPath}/robots.txt", "User-agent: *\nAllow: /\n");
    }
}
