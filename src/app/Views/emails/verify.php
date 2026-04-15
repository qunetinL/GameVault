<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background:#1a1a2e; font-family:Arial,Helvetica,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a1a2e; padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#16213e; border-radius:12px; overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#6c5ce7,#a29bfe); padding:30px; text-align:center;">
                            <h1 style="margin:0; color:#fff; font-size:28px;">GameVault</h1>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:40px 30px;">
                            <h2 style="color:#fff; margin:0 0 20px;">Bienvenue, <?= htmlspecialchars($username) ?> !</h2>
                            <p style="color:#b0b0c0; font-size:16px; line-height:1.6; margin:0 0 30px;">
                                Merci de vous être inscrit sur GameVault. Pour activer votre compte et accéder à toutes les fonctionnalités, veuillez confirmer votre adresse email.
                            </p>
                            <table cellpadding="0" cellspacing="0" style="margin:0 auto;">
                                <tr>
                                    <td style="background:#6c5ce7; border-radius:8px;">
                                        <a href="<?= htmlspecialchars($verifyUrl) ?>"
                                           style="display:inline-block; padding:14px 40px; color:#fff; text-decoration:none; font-size:16px; font-weight:bold;">
                                            Vérifier mon email
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="color:#888; font-size:13px; margin:30px 0 0; line-height:1.5;">
                                Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                                <a href="<?= htmlspecialchars($verifyUrl) ?>" style="color:#a29bfe; word-break:break-all;">
                                    <?= htmlspecialchars($verifyUrl) ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 30px; border-top:1px solid #2a2a4a; text-align:center;">
                            <p style="color:#666; font-size:12px; margin:0;">
                                Cet email a été envoyé automatiquement. Si vous n'avez pas créé de compte, ignorez ce message.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
