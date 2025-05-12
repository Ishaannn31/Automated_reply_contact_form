<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['name'])    &&
    isset($_POST['email'])   &&
    isset($_POST['subject']) &&
    isset($_POST['text'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $text = $_POST['text'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $em = "Invalid email format";
        header("Location: index.php?error=$em");
        exit;
    }

    if (empty($name) || empty($subject) || empty($text)) {
        $em = "Fill out all required entry fields";
        header("Location: index.php?error=$em");
        exit;
    }

    
    $mail = new PHPMailer(true);

    try {
      
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-mail';
        $mail->Password = 'your-app-passowrd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465; 


//  for testing purposes only
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        
        $mail->SMTPDebug = 2;
       

        
        $mail->setFrom($email, $name);
        $mail->addAddress('ijadhao.de@gmail.com');

        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <h3>Contact Form</h3>
            <p><strong>Name</strong>: $name</p>
            <p><strong>Email</strong>: $email</p>
            <p><strong>Subject</strong>: $subject</p>
            <p><strong>Message</strong>: $text</p>
        ";

        $mail->send();

        // Automated 
        $responseMail = new PHPMailer(true);
        $responseMail->isSMTP();
        $responseMail->Host = 'smtp.gmail.com';
        $responseMail->SMTPAuth = true;
        $responseMail->Username = 'your-email';
        $responseMail->Password = 'your-app-passowrd';
    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465; 


        // (for testing purposes only)
        $responseMail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $responseMail->setFrom('your-email', 'QuantML');
        $responseMail->addAddress($email);

        $responseMail->isHTML(true);
        $responseMail->Subject = 'Thank you for contacting QuantML';
        $responseMail->Body    = "
            <p>Thank you for reaching out to QuantML</p>
            <p>We have received your message and our team will get back to you as soon as possible.</p>
            <p>Thanks for contacting QuantML!</p>
        ";

        $responseMail->send();

        $sm = 'Message has been sent';
        header("Location: index.php?success=$sm");
        exit;
    } catch (Exception $e) {
        $em = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("Location: index.php?error=$em");
        exit;
    }
}
?>
