<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'Email/Exception.php';
require 'Email/PHPMailer.php';
require 'Email/SMTP.php';
class Mailer{
    public function sendEmail($email,$tieude,$noidung){
        try{
            $mail = new PHPMailer(true);
            $mail->isSMTP();// gửi mail SMTP
            $mail->Host = 'smtp.gmail.com';// Set the SMTP server to send through
            $mail->SMTPAuth = true;// Enable SMTP authentication
            $mail->Username = 'lehoangtuan783@gmail.com';// SMTP username
            $mail->Password = 'bhdp jtyl wgrc hpem'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port = 587; // TCP port to connect to

            $mail->setFrom('phamtrunghieu04112003@gmail.com', 'Coffee Shop');

            $mail->addAddress($email, 'Customer');

           // $mail->addCC('lehoangtuan783@gmail.com');

            $mail->isHTML(true);   // Set email format to HTML
            $mail->Subject = $tieude;
            $mail->Body = $noidung;
            $mail->AltBody = '';

            $mail->send();
            echo 'messege has been sent';
        }
        catch (Exception $e){

        }
    }
}