<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');

    $_POST= json_decode(file_get_contents('php://input'), true);
    class InserirdespesaController{
        public function pesquisa(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    
                    $venda = "INSERT INTO `user_despesas` (user_id, descricao,valor_despesas, data_vencimento, data_pagamento) 
                    VALUES (:user_id, :descricao ,:valor_despesas, :data_vencimento, :data_pagamento)";
                    $inserir_venda = $conexao->prepare($venda);
                    $inserir_venda->bindValue( ':user_id', $dados_de_usuario_sql->id);
                    $inserir_venda->bindValue(':descricao', $_POST['nome']);
                    $inserir_venda->bindValue(':valor_despesas', floatval($_POST['valor']));
                    $inserir_venda->bindValue(':data_vencimento', $_POST['data_vencimento']);
                    $inserir_venda->bindValue(':data_pagamento', $_POST['data']);
                    $inserir_venda->execute();
                    
                    $add = "UPDATE `user_financeiro` SET caixa = caixa - :caixa WHERE id= :id";
                    $caixa = $conexao->prepare($add);
                    $caixa->bindValue( ':id', $dados_de_usuario_sql->id);
                    $caixa->bindValue(':caixa', (floatval($_POST['valor'])));
                    $caixa->execute();


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