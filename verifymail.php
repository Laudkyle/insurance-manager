<?php
// Include PHPMailer and database connection
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to send email
function sendEmail($recipient, $firstname,$lastname, $email, $phone, $verificationLink) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'projectuserone2023@gmail.com';
        $mail->Password   = 'tswvqdrinrlkgvnk';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Sender and recipient
        $mail->setFrom('projectuserone2023@gmail.com'); 
        $mail->addAddress($recipient); 

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Account authentication";
        $mail->Body = $firstname .' '. $lastname .' has requested for an account verification with ' . $email . ' as email.<br><br>Click the following link to verify your account: <a href='.$verificationLink.'>$verificationLink</a> you can contact this person on ' . $phone;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>