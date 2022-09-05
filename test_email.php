<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    
// Inclui o arquivo class.phpmailer.php localizado na mesma pasta do arquivo php 
include "PHPMailer/PHPMailerAutoload.php"; 
if(isset($_REQUEST) && !empty($_REQUEST)){
    include('conexao.php');
    $email = $_POST["email"];
    $mail = new PHPMailer(); 



    $sql = "UPDATE users_info SET verif_code = '".$verif."' WHERE email = :email";
    $inserir2 = $conexao->prepare($sql);
    $inserir2->bindValue(':email' ,$email);
    $inserir2->execute();
    $conexao =null;


    $mail->isSMTP();
    $mail->SMTPDebug = 2;
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'failcreator0.0@gmail.com';
    $mail->Password = 'Inuyashacrashc0m';
    $mail->setFrom('failcreator0.0@gmail.com', 'Raquel LTDA');
    $mail->addReplyTo('failcreator0.0@gmail.com', 'Raquel LTDA');
    $mail->addAddress($email);
    $mail->Subject = 'Código de verificação';
    $mail->msgHTML(file_get_contents('message.html'), __DIR__);
    $mail->Body = 'Seu código de verificação é: '.$verif;
    //$mail->addAttachment('test.txt');
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'The email message was sent.';
    }

?>