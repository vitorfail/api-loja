<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);

    $doc = array_map('strtolower', $_POST['teste']);

    $total_documentos =  count($doc);

    $termos = total_termos($doc);
    $frenquencia_do_termo = caulcular_termo($doc, $termos);

    function caulcular_documento($documentos, $tr){
        //echo $tr." ";
        $i = 0;
        foreach ($documentos as $row) {
            if($row !== "" and $row !== null and $row !== ' '){
                $checar = strpos($row, $tr);
                if($checar !== false){
                    $i++;
                }    
            }
        }
        return $i;
    }
    function caulcular_termo($documentos, $termo){
        $frequencia = array();
        foreach($termo as $tr){
            if($tr != '' and $tr != ' '){
                $frequencia[$tr] = caulcular_documento($documentos, $tr);
            }
        }
        return $frequencia;
    }
    function total_termos($lista){
        $t = '';
        foreach($lista as $row){
            $t = $t.' '.$row;
        }
        $t = preg_replace('/[0-9\@\.\;\" "]+/', ' ', $t);
        return explode(' ', $t);
    }

    $idf = 
    echo json_encode($frenquencia_do_termo); 
?>