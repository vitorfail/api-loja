<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');

    $_POST= json_decode(file_get_contents('php://input'), true);
    class InserircustosController{
        public function pesquisa(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    $situacao = '';
                    if($_POST['situacao'] == false){
                        $situacao = "Aberto";
                    }
                    else{
                        $situacao = "Pago";
                    }
                    $venda = "INSERT INTO `user_custos_fixo` (user_id, descricao,valor_custo, data_vencimento, data_pagamento, situacao) 
                    VALUES (:user_id, :descricao ,:valor_despesas, :data_vencimento, :data_pagamento, :situacao)";
                    $inserir_venda = $conexao->prepare($venda);
                    $inserir_venda->bindValue(':user_id', $dados_de_usuario_sql->id);
                    $inserir_venda->bindValue(':descricao', $_POST['nome']);
                    $inserir_venda->bindValue(':valor_despesas', floatval($_POST['valor']));
                    $inserir_venda->bindValue(':data_vencimento', $_POST['data_vencimento']);
                    $inserir_venda->bindValue(':data_pagamento', $_POST['data']);
                    $inserir_venda->bindValue(':situacao', $situacao);
                    $inserir_venda->execute();

                    $conexao = null;    
                    return "1";
                }
                catch(Exception $ex){
                    $conexao = null;  
                    return '0';
                }
            }
        }
    }
?>