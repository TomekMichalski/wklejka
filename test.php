<?php

header('Content-type: text/html; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'classes/mail/Exception.php';
require 'classes/mail/PHPMailer.php';
require 'classes/mail/SMTP.php';


$tresc = 'Przepraszam musialem xDDD';
$temat = 'Jeszcze raz przepraszam XDDDDDDD';

date_default_timezone_set('Europe/Warsaw');

$mail = new PHPMailer(true);
try {
 $mail->isSMTP(); // Używamy SMTP
 $mail->Host = 'smtp.zoho.com'; // Adres serwera SMTP
 $mail->SMTPAuth = true; // Autoryzacja (do) SMTP
 $mail->Username = "wklejka@zonegames.pl"; // Nazwa użytkownika
 $mail->Password = "Wklejka99*"; // Hasło
 $mail->SMTPSecure = 'tls'; // Typ szyfrowania (TLS/SSL)
 $mail->Port = 587; // Port

 $mail->CharSet = "UTF-8";
 $mail->setLanguage('pl', '/phpmailer/language');

 $mail->setFrom('wklejka@zonegames.pl');
 $mail->FromName = 'Wklejka';
 $mail->addAddress('osica32@gmail.com');

 $mail->isHTML(true); // Format: HTML
 $mail->Subject = $temat;
 $mail->Body = $tresc;
 $mail->AltBody = 'By wyświetlić wiadomość należy skorzystać z czytnika obsługującego wiadomości w formie HTML';
 
	$mail->send();
 // Gdy OK:
 echo 'dziala';

} catch (Exception $e) {
 // Gdy błąd:
 echo 'Nie dziala'.$e;
}

?>
