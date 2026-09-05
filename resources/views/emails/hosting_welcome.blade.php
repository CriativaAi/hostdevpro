<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sua Hospedagem HostDevPro Foi Ativada</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1e293b;">

    <!-- Wrapper Geral -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; padding: 30px 10px;">
        <tr>
            <td align="center">
                <!-- Container Card Principal -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 640px; background-color: #ffffff; border-radius: 18px; overflow: hidden; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
                    
                    <!-- HEADER TOPO -->
                    <tr>
                        <td align="center" style="padding: 36px 30px 26px 30px; background: linear-gradient(180deg, #090d16 0%, #0f172a 100%); border-bottom: 3px solid #10b981;">
                            <div style="font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px; text-transform: uppercase;">
                                HOST<span style="color: #10b981;">DEV</span>PRO <span style="font-size: 13px; color: #94a3b8; font-weight: 700; letter-spacing: 1px;">CLOUD</span>
                            </div>
                            <div style="margin-top: 14px;">
                                <span style="display: inline-block; background-color: #064e3b; border: 1px solid #10b981; color: #6ee7b7; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; padding: 6px 16px; border-radius: 9999px;">
                                    ● SERVIDOR ATIVADO & OPERACIONAL
                                </span>
                            </div>
                        </td>
                    </tr>

                    <!-- CORPO PRINCIPAL -->
                    <tr>
                        <td style="padding: 36px 32px 28px 32px; background-color: #ffffff;">
                            
                            <!-- Saudação -->
                            <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 12px 0; line-height: 1.3;">
                                Parabéns, {{ $client->name }}! 🎉
                            </h1>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin: 0 0 28px 0;">
                                Sua conta de hospedagem de alta performance para o domínio <strong style="color: #0f172a; background-color: #e0f2fe; padding: 3px 8px; border-radius: 6px; font-family: monospace;">{{ $account->domain }}</strong> acaba de ser provisionada com sucesso em nossa infraestrutura NVMe Enterprise.
                            </p>

                            <!-- SEÇÃO 1: RESUMO DO SERVIDOR -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 26px;">
                                <tr>
                                    <td style="font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: #059669; padding-bottom: 8px;">
                                        ⚙️ RESUMO DO SERVIDOR
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px;">
                                        <table border="0" cellpadding="6" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="40%" style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Domínio Principal:</td>
                                                <td width="60%" style="color: #0f172a; font-size: 14px; font-weight: 800; font-family: monospace; border-bottom: 1px solid #edf2f7; text-align: right;">{{ $account->domain }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Plano Contratado:</td>
                                                <td style="color: #0f172a; font-size: 13px; font-weight: 800; border-bottom: 1px solid #edf2f7; text-align: right;">{{ strtoupper($account->plan) }} NVMe Gen5</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">IP do Servidor:</td>
                                                <td style="color: #0284c7; font-size: 14px; font-weight: 800; font-family: monospace; border-bottom: 1px solid #edf2f7; text-align: right;">{{ $serverDetails['ip'] ?? '177.136.254.37' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Datacenter:</td>
                                                <td style="color: #0f172a; font-size: 13px; font-weight: 700; border-bottom: 1px solid #edf2f7; text-align: right;">São Paulo / BR (Equinix Cluster)</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Versão PHP:</td>
                                                <td style="color: #0f172a; font-size: 13px; font-weight: 800; border-bottom: 1px solid #edf2f7; text-align: right;">{{ $account->php_version ?? '8.4' }} (Nativo)</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600;">Certificado SSL:</td>
                                                <td style="color: #16a34a; font-size: 13px; font-weight: 800; text-align: right;">Let's Encrypt Wildcard Grátis</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- SEÇÃO 2: SERVIDORES DNS (NAMESERVERS) -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 26px;">
                                <tr>
                                    <td style="font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: #d97706; padding-bottom: 8px;">
                                        🌐 SERVIDORES DNS (APONTE NO REGISTRO.BR OU CLOUDFLARE)
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 13px; color: #475569; padding-bottom: 10px; line-height: 1.5;">
                                        Para que seu site e e-mails comecem a responder, altere os DNS no seu registrador de domínio para os servidores oficiais abaixo:
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #fffbeb; border: 2px dashed #fcd34d; border-radius: 12px; padding: 18px 20px;">
                                        <div style="font-family: Consolas, Monaco, 'Liberation Mono', monospace; font-size: 13px; color: #92400e; line-height: 1.8;">
                                            <strong>Master DNS 1:</strong> ns1.valueserver.net (177.93.111.32)<br/>
                                            <strong>Slave DNS 2:</strong> &nbsp;ns2.valueserver.net (187.45.181.114)<br/>
                                            <strong>Slave DNS 3:</strong> &nbsp;ns3.valueserver.net (51.81.81.61)<br/>
                                            <strong>Slave DNS 4:</strong> &nbsp;ns4.valueserver.net (51.222.29.124)
                                        </div>
                                        <div style="font-size: 12px; color: #78350f; margin-top: 10px; border-top: 1px solid #fef3c7; pt-2;">
                                            💡 <em>Se você preferir usar Cloudflare, crie uma entrada <strong>Tipo A</strong> com nome <code>@</code> e <code>www</code> apontando para o IP <strong>{{ $serverDetails['ip'] ?? '177.136.254.37' }}</strong>.</em>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- SEÇÃO 3: CREDENCIAIS DO PAINEL & FTP -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 26px;">
                                <tr>
                                    <td style="font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: #0284c7; padding-bottom: 8px;">
                                        🔑 CREDENCIAIS DO PAINEL DE CONTROLE & FTP
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px;">
                                        <table border="0" cellpadding="6" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="40%" style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Painel de Hospedagem:</td>
                                                <td width="60%" style="border-bottom: 1px solid #edf2f7; text-align: right;">
                                                    <a href="https://us163-pl.valueserver.net:8443" style="color: #0284c7; font-size: 13px; font-weight: 800; text-decoration: underline;" target="_blank">us163-pl.valueserver.net:8443</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Usuário do Painel:</td>
                                                <td style="color: #0f172a; font-size: 14px; font-weight: 900; font-family: monospace; border-bottom: 1px solid #edf2f7; text-align: right;">
                                                    <span style="background-color: #f1f5f9; padding: 3px 8px; border-radius: 6px;">{{ $account->username }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Senha de Acesso:</td>
                                                <td style="color: #b45309; font-size: 14px; font-weight: 900; font-family: monospace; border-bottom: 1px solid #edf2f7; text-align: right;">
                                                    <span style="background-color: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 6px; border: 1px solid #fde68a;">{{ $plainPassword }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Host FTP:</td>
                                                <td style="color: #0f172a; font-size: 13px; font-weight: 700; font-family: monospace; border-bottom: 1px solid #edf2f7; text-align: right;">ftp.{{ $account->domain }} ou {{ $serverDetails['ip'] ?? '177.136.254.37' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Porta FTP:</td>
                                                <td style="color: #0f172a; font-size: 13px; font-weight: 800; font-family: monospace; border-bottom: 1px solid #edf2f7; text-align: right;">21 (FTP / FTPS)</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600;">Diretório Raiz:</td>
                                                <td style="color: #7c3aed; font-size: 13px; font-weight: 800; font-family: monospace; text-align: right;">/httpdocs</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- SEÇÃO 4: E-MAILS CORPORATIVOS & WEBMAIL -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: #db2777; padding-bottom: 8px;">
                                        ✉️ E-MAILS PROFISSIONAIS & WEBMAIL
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px;">
                                        <table border="0" cellpadding="6" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="40%" style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Webmail Online:</td>
                                                <td width="60%" style="border-bottom: 1px solid #edf2f7; text-align: right;">
                                                    <a href="https://us163-pl.valueserver.net/webmail" style="color: #db2777; font-size: 13px; font-weight: 800; text-decoration: underline;" target="_blank">us163-pl.valueserver.net/webmail</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600; border-bottom: 1px solid #edf2f7;">Entrada (IMAP):</td>
                                                <td style="color: #0f172a; font-size: 13px; font-weight: 700; font-family: monospace; border-bottom: 1px solid #edf2f7; text-align: right;">mail.{{ $account->domain }} (Porta 993 SSL)</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px; font-weight: 600;">Saída (SMTP):</td>
                                                <td style="color: #0f172a; font-size: 13px; font-weight: 700; font-family: monospace; text-align: right;">mail.{{ $account->domain }} (Porta 465 SSL)</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- BOTÃO CTA DE AÇÃO -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 12px;">
                                        <a href="https://app.hostdevpro.app.br/dashboard" target="_blank" 
                                           style="display: block; width: 85%; max-width: 380px; background-color: #10b981; color: #ffffff; font-size: 15px; font-weight: 900; text-align: center; text-decoration: none; padding: 18px 24px; border-radius: 12px; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35); text-transform: uppercase; letter-spacing: 0.5px;">
                                            ACESSAR CENTRAL DO CLIENTE &rarr;
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-size: 12px; color: #64748b;">
                                        Gerencie faturas, chamados, e-mails e domínios com 1 clique.
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- RODAPÉ -->
                    <tr>
                        <td align="center" style="padding: 24px 30px 30px 30px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; line-height: 1.6;">
                            <div style="margin-bottom: 10px; color: #334155; font-weight: 600;">
                                Dúvidas técnicas ou suporte? Fale com nossa equipe no WhatsApp:
                            </div>
                            <div style="margin-bottom: 14px;">
                                <a href="https://wa.me/5511921381308" target="_blank" 
                                   style="display: inline-block; background-color: #ecfdf5; color: #047857; font-weight: 800; text-decoration: none; padding: 8px 18px; border-radius: 8px; border: 1px solid #a7f3d0; font-size: 14px;">
                                    💬 +55 (11) 92138-1308
                                </a>
                            </div>
                            <div style="font-size: 11px; color: #94a3b8;">
                                &copy; {{ date('Y') }} HostDevPro Cloud. Todos os direitos reservados.<br/>
                                Infraestrutura gerenciada por CreativaAi Hub Technology.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
