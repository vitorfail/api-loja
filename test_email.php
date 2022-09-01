<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    
// Inclui o arquivo class.phpmailer.php localizado na mesma pasta do arquivo php 
include "PHPMailer/PHPMailerAutoload.php"; 
if(isset($_REQUEST) && !empty($_REQUEST)){
    $email = $_POST["email"];
    $mail = new PHPMailer(); 

    // Método de envio 
    $mail->IsSMTP(); 

    // Enviar por SMTP 
    $mail->Host = "smtp.gmail.com"; 

    // Você pode alterar este parametro para o endereço de SMTP do seu provedor 
    $mail->Port = "587"; 


    // Usar autenticação SMTP (obrigatório) 
    $mail->SMTPAuth = true; 

    // Usuário do servidor SMTP (endereço de email) 
    // obs: Use a mesma senha da sua conta de email 
    $mail->Username = 'failcreator0.0@gmail.com'; 
    $mail->Password = 'Inuyashacrashc0m'; 

    // Configurações de compatibilidade para autenticação em TLS 
    $mail->SMTPSecure = 'tsl';

    // Você pode habilitar esta opção caso tenha problemas. Assim pode identificar mensagens de erro. 
    // $mail->SMTPDebug = 2; 

    // Define o remetente 
    // Seu e-mail 
    $mail->From = "failcreator0.0@gmail.com"; 

    // Seu nome 
    $mail->FromName = "Vitor"; 

    // Define o(s) destinatário(s) 
    $mail->AddAddress($email); 

    // Opcional: mais de um destinatário
    // $mail->AddAddress('fernando@email.com'); 

    // Opcionais: CC e BCC
    // $mail->AddCC('joana@provedor.com', 'Joana'); 
    // $mail->AddBCC('roberto@gmail.com', 'Roberto'); 

    // Definir se o e-mail é em formato HTML ou texto plano 
    // Formato HTML . Use "false" para enviar em formato texto simples ou "true" para HTML.
    $mail->IsHTML(true); 

    // Charset (opcional) 
    $mail->CharSet = 'UTF-8'; 

    // Assunto da mensagem 
    $mail->Subject = "Teste"; 

    // Corpo do email 
    $mail->Body = 'Aqui entra o conteudo texto do email'; 

    // Opcional: Anexos 
    // $mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf"); 

    // Envia o e-mail 
    $enviado = $mail->Send(); 

    // Exibe uma mensagem de resultado 
    if ($enviado) 
    { 
        echo "Seu email foi enviado com sucesso!"; 
    } else { 
        echo "Houve um erro enviando o email: ".$mail->ErrorInfo; 
    } 

}
else{
    echo 'erro';
}

?>