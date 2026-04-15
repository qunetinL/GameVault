<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    public static function send(string $to, string $subject, string $htmlBody): bool
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'] ?? 'mailpit';
            $mail->Port = (int) ($_ENV['MAIL_PORT'] ?? 1025);
            $mail->SMTPAuth = false;
            $mail->setFrom(
                $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@gamevault.local',
                $_ENV['MAIL_FROM_NAME'] ?? 'GameVault'
            );
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;

            return $mail->send();
        } catch (Exception $e) {
            error_log('MailHelper error: ' . $e->getMessage());
            return false;
        }
    }
}
