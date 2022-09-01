<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    $hotname = "localhost";
    $user = 'root';
    $password = '';
    $database = "loja";

    $conexao = new PDO("mysql:host=$hotname;dbname=$database;charset=utf8", $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    if(!$conexao){
        print("Falha na conexão com banco de dados");
    }


?>