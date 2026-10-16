<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark light">
    <meta name="supported-color-schemes" content="dark light">
    <title>Redefinição de senha</title>
    <style>
        @media (prefers-color-scheme: dark) {
            body, .bg-wrapper { background-color: #020617 !important; }
            .card { background-color: #0f172a !important; border-color: #1e293b !important; }
            .title { color: #f8fafc !important; }
            .text-main { color: #cbd5e1 !important; }
            .text-muted { color: #94a3b8 !important; }
            .footer-bg { background-color: #0b0f19 !important; border-color: #1e293b !important; }
            .link-url { color: #34d399 !important; }
            .badge-box { background-color: #022c22 !important; border-color: #064e3b !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#0f172a;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#e2e8f0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="bg-wrapper" style="background-color:#0f172a;padding:40px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="card"
                    style="max-width:560px;width:100%;background-color:#1e293b;border-radius:20px;border:1px solid #334155;overflow:hidden;box-shadow:0 10px 25px -5px rgba(0, 0, 0, 0.3);">

                    <!-- CABEÇALHO / BRANDING -->
                    <tr>
                        <td style="padding:36px 32px 24px;text-align:center;">
                            <!-- Ícone do App -->
                            <table role="presentation" align="center" cellspacing="0" cellpadding="0" style="margin:0 auto 16px;">
                                <tr>
                                    <td align="center" style="width:52px;height:52px;background-color:#059669;border-radius:16px;box-shadow:0 4px 12px rgba(5, 150, 105, 0.35);">
                                        <img src="https://img.icons8.com/ios-filled/50/ffffff/lock--v1.png" width="24" height="24" alt="Ícone de Cadeado" style="display:block;margin:0 auto;border:0;">
                                    </td>
                                </tr>
                            </table>

                            <h1 class="title" style="margin:0;font-size:22px;font-weight:700;color:#f8fafc;letter-spacing:-0.02em;">
                                Triagem IA
                            </h1>
                            <p class="text-muted" style="margin:4px 0 0;font-size:13px;color:#94a3b8;font-weight:500;">
                                Cuidado e tecnologia em saúde
                            </p>
                        </td>
                    </tr>

                    <!-- CONTEÚDO PRINCIPAL -->
                    <tr>
                        <td style="padding:0 36px 36px;">
                            <h2 class="title" style="margin:0 0 16px;font-size:18px;font-weight:600;color:#f8fafc;">
                                Redefinição de senha
                            </h2>

                            <p class="text-main" style="margin:0 0 12px;font-size:15px;line-height:1.6;color:#cbd5e1;">
                                Olá{{ isset($user->name) && $user->name ? ', ' . $user->name : '' }}!
                            </p>

                            <p class="text-main" style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#cbd5e1;">
                                Recebemos uma solicitação para redefinir a senha da sua conta no <strong style="color:#059669;">Triagem IA</strong>. Clique no botão abaixo para criar uma nova senha.
                            </p>

                            <!-- BOTÃO DE AÇÃO -->
                            <table role="presentation" cellspacing="0" cellpadding="0" width="100%" style="margin:28px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}"
                                           target="_blank"
                                           style="display:inline-block;width:80%;max-width:280px;padding:14px 24px;background-color:#059669;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;border-radius:12px;text-align:center;box-shadow:0 4px 14px rgba(5, 150, 105, 0.4);transition:all 0.2s ease;">
                                            Redefinir minha senha
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p class="text-muted" style="margin:0 0 20px;font-size:13px;line-height:1.6;color:#94a3b8;">
                                Se você não solicitou essa alteração, pode ignorar este e-mail com segurança. Sua senha atual continuará a mesma.
                            </p>

                            <!-- LINK ALTERNATIVO -->
                            <div class="badge-box" style="padding:16px;background-color:#0f172a;border:1px solid #334155;border-radius:12px;margin-top:24px;">
                                <p class="text-muted" style="margin:0 0 8px;font-size:12px;color:#94a3b8;">
                                    Se o botão não funcionar, copie e cole o link abaixo no seu navegador:
                                </p>
                                <p style="margin:0;font-size:11px;line-height:1.5;word-break:break-all;">
                                    <a href="{{ $url }}" target="_blank" class="link-url" style="color:#34d399;text-decoration:underline;">
                                        {{ $url }}
                                    </a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- RODAPÉ -->
                    <tr>
                        <td class="footer-bg" style="padding:20px 32px;background-color:#0f172a;border-top:1px solid #334155;text-align:center;">
                            <p class="text-muted" style="font-size:12px;line-height:1.5;color:#64748b;margin:0;">
                                E-mail automático enviado pelo sistema <strong>Triagem IA</strong>.<br>
                                Por favor, não responda a esta mensagem.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>