<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);

    $total_documentos =  count($_POST['teste']);

    $termos = total_termos($_POST['teste']);


    function total_termos($lista){
        $t = '';
        foreach($lista as $row){
            $t = $t.' '.$row;
        }
        return explode(' ', $t);
    }
    echo json_encode($termos); 
?>