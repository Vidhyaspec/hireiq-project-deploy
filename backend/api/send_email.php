<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendMail($to, $name, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {

        // =========================
        // SMTP CONFIG (GMAIL)
        // =========================
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'vidhyasri0615@gmail.com';
        $mail->Password = 'fazxokkcoqwwbanu'; // APP PASSWORD (NO SPACES)

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // =========================
        // 🔥 IMPORTANT DEBUG (TURN ON FOR TESTING)
        // =========================
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function ($str, $level) {
            error_log("SMTP DEBUG: $str");
        };

        // =========================
        // EMAIL SETTINGS
        // =========================
        $mail->setFrom('vidhyasri0615@gmail.com', 'HIREIQ');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // =========================
        // SEND EMAIL
        // =========================
        $mail->send();

        return [
            "status" => "success",
            "message" => "Email sent successfully"
        ];

    } catch (Exception $e) {

        // 🔥 SHOW REAL ERROR
        return [
            "status" => "error",
            "message" => "Email failed",
            "error" => $mail->ErrorInfo
        ];
    }
}
?>

