<?php
include('email-config.php');

$mail->setFrom('argayorgor@philsaga.com', 'First Last');

$mail->addAddress('jatano@philsaga.com', 'John Doe');

$mail->Subject = 'PHPMailer SMTP without auth test';

$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}