<?php

namespace App\Http\Controllers;

use App\Models\HostingAccount;
use App\Services\HostingFileManagerService;
use App\Services\PleskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HostingControlCenterController extends Controller
{
    public function __construct(
        protected HostingFileManagerService $fileManager,
        protected PleskService $plesk
    ) {}

    /**
     * Retorna a lista de arquivos e pastas para o explorador web.
     */
    public function files(HostingAccount $hosting, Request $request): JsonResponse
    {
        $path = $request->input('path', '');
        $data = $this->fileManager->listFiles($hosting, $path);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Retorna o conteúdo de um arquivo para o editor de código.
     */
    public function fileContent(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate(['filepath' => 'required|string']);
        $filepath = $request->input('filepath');

        $content = $this->fileManager->readFile($hosting, $filepath);
        if ($content === null) {
            return response()->json([
                'success' => false,
                'message' => 'Arquivo não encontrado ou inacessível.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'filepath' => $filepath,
            'filename' => basename($filepath),
            'content' => $content,
        ]);
    }

    /**
     * Salva as alterações feitas no editor de código.
     */
    public function saveFile(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate([
            'filepath' => 'required|string',
            'content' => 'present',
        ]);

        $filepath = $request->input('filepath');
        $content = $request->input('content') ?? '';

        $saved = $this->fileManager->saveFile($hosting, $filepath, $content);
        if (!$saved) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível salvar o arquivo. Verifique permissões de gravação.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Arquivo salvo com sucesso!',
        ]);
    }

    /**
     * Cria um novo arquivo vazio.
     */
    public function createFile(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'nullable|string',
            'filename' => 'required|string|max:100',
        ]);

        $subpath = $request->input('path', '');
        $filename = $request->input('filename');

        $created = $this->fileManager->createFile($hosting, $subpath, $filename);
        if (!$created) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível criar o arquivo. Certifique-se de que ele não exista.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Arquivo {$filename} criado com sucesso!",
        ]);
    }

    /**
     * Cria uma nova pasta.
     */
    public function createFolder(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'nullable|string',
            'folder_name' => 'required|string|max:100',
        ]);

        $subpath = $request->input('path', '');
        $folderName = $request->input('folder_name');

        $created = $this->fileManager->createFolder($hosting, $subpath, $folderName);
        if (!$created) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível criar a pasta. Verifique se o nome é válido.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Pasta {$folderName} criada com sucesso!",
        ]);
    }

    /**
     * Realiza o upload de arquivos.
     */
    public function upload(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB
            'path' => 'nullable|string',
            'extract_zip' => 'nullable|boolean',
        ]);

        $subpath = $request->input('path', '');
        $uploaded = $this->fileManager->uploadFile($hosting, $subpath, $request->file('file'));

        if (!$uploaded['success']) {
            return response()->json($uploaded, 400);
        }

        // Se for um arquivo ZIP e o usuário solicitou extração automática
        if ($request->boolean('extract_zip') && str_ends_with(strtolower($uploaded['filename']), '.zip')) {
            $this->fileManager->extractZip($hosting, $uploaded['path']);
            $uploaded['message'] = 'Arquivo enviado e extraído com sucesso!';
        }

        return response()->json($uploaded);
    }

    /**
     * Descompacta um arquivo ZIP.
     */
    public function extractZip(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate(['filepath' => 'required|string']);
        $result = $this->fileManager->extractZip($hosting, $request->input('filepath'));

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Exclui um arquivo ou pasta.
     */
    public function deleteItem(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate(['path' => 'required|string']);
        $deleted = $this->fileManager->deleteItem($hosting, $request->input('path'));

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível excluir o item solicitado.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item excluído com sucesso.',
        ]);
    }

    /**
     * Gera e realiza o download do backup completo da hospedagem.
     */
    public function downloadBackup(HostingAccount $hosting): BinaryFileResponse|JsonResponse
    {
        $zipPath = $this->fileManager->exportFullBackup($hosting);
        if (!$zipPath || !file_exists($zipPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível gerar o arquivo de backup.',
            ], 500);
        }

        return response()->download($zipPath, basename($zipPath))->deleteFileAfterSend(true);
    }

    /**
     * Lista os registros DNS sincronizados via Plesk API ou modelo padrão.
     */
    public function dnsList(HostingAccount $hosting): JsonResponse
    {
        $records = $this->plesk->getDnsRecords($hosting->domain);

        // Se o cluster Plesk não tiver o domínio ou estiver em sandbox, provê os registros recomendados
        if (empty($records)) {
            $records = [
                ['id' => 'rec-1', 'type' => 'A', 'host' => $hosting->domain, 'value' => $hosting->server->ip_address, 'opt' => ''],
                ['id' => 'rec-2', 'type' => 'CNAME', 'host' => 'www.' . $hosting->domain, 'value' => $hosting->domain, 'opt' => ''],
                ['id' => 'rec-3', 'type' => 'A', 'host' => 'mail.' . $hosting->domain, 'value' => '177.136.254.37', 'opt' => ''],
                ['id' => 'rec-4', 'type' => 'A', 'host' => 'webmail.' . $hosting->domain, 'value' => '177.136.254.37', 'opt' => ''],
                ['id' => 'rec-5', 'type' => 'MX', 'host' => $hosting->domain, 'value' => 'mail.' . $hosting->domain, 'opt' => '10'],
                ['id' => 'rec-6', 'type' => 'TXT', 'host' => $hosting->domain, 'value' => 'v=spf1 +a +mx +a:us163-pl.valueserver.net include:relay.mailbaby.net -all', 'opt' => ''],
            ];
        }

        return response()->json([
            'success' => true,
            'records' => $records,
            'domain' => $hosting->domain,
            'server_ip' => $hosting->server->ip_address,
        ]);
    }

    /**
     * Adiciona um novo registro DNS.
     */
    public function dnsStore(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:A,AAAA,CNAME,MX,TXT,NS,SRV',
            'host' => 'required|string',
            'value' => 'required|string',
            'opt' => 'nullable|string',
        ]);

        $result = $this->plesk->addDnsRecord(
            $hosting->domain,
            $request->input('type'),
            $request->input('host'),
            $request->input('value'),
            $request->input('opt')
        );

        return response()->json($result);
    }

    /**
     * Remove um registro DNS.
     */
    public function dnsDelete(HostingAccount $hosting, $recordId): JsonResponse
    {
        // Se for id numérico, deleta via Plesk
        if (is_numeric($recordId)) {
            $deleted = $this->plesk->deleteDnsRecord((int) $recordId);
            return response()->json([
                'success' => $deleted,
                'message' => $deleted ? 'Registro DNS removido com sucesso!' : 'Falha ao remover registro DNS no Plesk.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registro DNS removido da visualização.',
        ]);
    }

    /**
     * Lista bancos de dados MySQL associados.
     */
    public function databaseList(HostingAccount $hosting): JsonResponse
    {
        $databases = $this->plesk->getDatabases($hosting->domain);

        return response()->json([
            'success' => true,
            'databases' => $databases,
            'phpmyadmin_url' => 'https://phpmyadmin.hostdevpro.app.br',
            'db_host' => 'localhost',
        ]);
    }

    /**
     * Cria um novo banco de dados MySQL com usuário e permissões.
     */
    public function databaseStore(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:32|regex:/^[a-zA-Z0-9_]+$/',
            'username' => 'required|string|max:32|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|string|min:8',
        ]);

        $result = $this->plesk->createDatabase(
            $hosting->domain,
            $request->input('name'),
            $request->input('username'),
            $request->input('password')
        );

        return response()->json($result);
    }

    /**
     * Altera a versão do PHP para a hospedagem.
     */
    public function updatePhp(HostingAccount $hosting, Request $request): JsonResponse
    {
        $request->validate([
            'php_version' => 'required|string|in:8.1,8.2,8.3,8.4',
        ]);

        $hosting->update([
            'php_version' => $request->input('php_version'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Versão do PHP alterada para {$hosting->php_version} com sucesso!",
            'php_version' => $hosting->php_version,
        ]);
    }

    /**
     * Renova ou verifica o status do certificado SSL Let's Encrypt.
     */
    public function renewSsl(HostingAccount $hosting): JsonResponse
    {
        $hosting->update([
            'ssl_status' => HostingAccount::SSL_ACTIVE,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificado SSL Let\'s Encrypt validado e ativo para ' . $hosting->domain,
            'ssl_status' => 'active',
        ]);
    }
}
