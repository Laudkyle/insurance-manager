<?php
// Include PHPMailer and database connection
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'connection.php';

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    //Mailing system
    $mail = new PHPMailer();

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'projectuserone2023@gmail.com';
        $mail->Password   = 'tswvqdrinrlkgvnk';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('projectuserone2023@gmail.com', 'PROJECT ONE');
        $mail->addReplyTo('projectuserone2023@gmail.com', 'PROJECT ONE');

        $currentDate = date('Y-m-d');
        $thirtyDaysLater = date('Y-m-d', strtotime('+30 days'));

        $heading = "License Expiration Alert";
    
        // Query the database for dates due in the next 30 days
        $sql = "SELECT email,company_name FROM insurance_entries WHERE expiry_date BETWEEN '$currentDate' AND '$thirtyDaysLater'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $email = $row["email"];
                $company_name = $row['company_name'];
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = $heading;
                $mail->Body    = "Hello, your $product_name insurance is due to expire on $thirtyDaysLater. Please do well to renew your subscription.";
                

                $mail->send();
                $mail->clearAllRecipients();
            }

            echo "<p style='text-align: center; color: green;'>Emails Sent Successfully!</p>";

        } else {
            echo "<p style='text-align: center; color: red;'>No licenses are due today!</p>";
        }

        // Close the database connection
        $con->close();
    } catch (Exception $e) {
        echo "<p style='text-align: center; color: red;'>Email sending failed: {$mail->ErrorInfo}</p>";
    }


}
?>
