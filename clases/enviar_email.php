<?php

$id_transaccion = $id_transaccion ?? '';
$email = $email ?? '';
$monto = $monto ?? 0;

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;    
    $mail->isSMTP();                                          
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'leonardopizarroquispe@gmail.com';                     //SMTP username
    $mail->Password   = 'oeem iyga wdqg buae';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      
    $mail->Port       = 587;                                    

    //Recipients
    $mail->setFrom('leonardopizarroquispe@gmail.com', 'Tienda RT');
    $mail->addAddress('leonardoquispepizarro@gmail.com', 'Joe User');     //Add a recipient


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalles de su Compra';

    $cuerpo = '<h4>Gracias por su compra</h4>';
    $cuerpo .= '<p>EL ID de su compra es <b>'. $id_transaccion .'</b></p>';

    $mail->Body    = $cuerpo;
    $mail->AltBody = 'Le enviamos los detalles de su compra.';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
 
} catch (Exception $e) {
    echo "Error al enviar el correo electronico no de la compra: {$mail->ErrorInfo}";
    //exit;
}