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
                        <td style="background:linear-gradient(135deg,#e74c3c,#e67e22); padding:30px; text-align:center;">
                            <h1 style="margin:0; color:#fff; font-size:28px;">GameVault</h1>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:40px 30px;">
                            <h2 style="color:#fff; margin:0 0 20px;">Réinitialisation du mot de passe</h2>
                            <p style="color:#b0b0c0; font-size:16px; line-height:1.6; margin:0 0 10px;">
                                Bonjour <strong style="color:#fff;"><?= htmlspecialchars($username) ?></strong>,
                            </p>
                            <p style="color:#b0b0c0; font-size:16px; line-height:1.6; margin:0 0 30px;">
                                Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour en choisir un nouveau. Ce lien expire dans <strong style="color:#fff;">1 heure</strong>.
                            </p>
                            <table cellpadding="0" cellspacing="0" style="margin:0 auto;">
                                <tr>
                                    <td style="background:#e74c3c; border-radius:8px;">
                                        <a href="<?= htmlspecialchars($resetUrl) ?>"
                                           style="display:inline-block; padding:14px 40px; color:#fff; text-decoration:none; font-size:16px; font-weight:bold;">
                                            Réinitialiser mon mot de passe
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="color:#888; font-size:13px; margin:30px 0 0; line-height:1.5;">
                                Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                                <a href="<?= htmlspecialchars($resetUrl) ?>" style="color:#e67e22; word-break:break-all;">
                                    <?= htmlspecialchars($resetUrl) ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 30px; border-top:1px solid #2a2a4a; text-align:center;">
                            <p style="color:#666; font-size:12px; margin:0;">
                                Si vous n'avez pas demandé cette réinitialisation, ignorez cet email. Votre mot de passe restera inchangé.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
