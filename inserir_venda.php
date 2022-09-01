<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');

    $_POST= json_decode(file_get_contents('php://input'), true);
    class InserirvendaController{
        public function pesquisa(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    $sql = "UPDATE `user-produtos` SET quantidade = quantidade - :quantidade WHERE id= :id AND `user-id`= :user_id";
                    $salvar = $conexao->prepare($sql);
                    $salvar->bindValue( ':user_id', $dados_de_usuario_sql->id);
                    $salvar->bindValue(':id',  intval($_POST['id']));
                    $salvar->bindValue(':quantidade', intval($_POST['qtd']));
                    $salvar->execute();
                    
                    $venda = "INSERT INTO `user_vendas` (user_id, produto_nome, data_venda, 
                    produto_id, valor_venda, tipo_de_pagamento, quantidade) VALUES ( :user_id,  :produto_nome,  :data_venda,  
                    :produto_id,  :valor_venda, :tipo_de_pagamento, :quantidade)";
                    $inserir_venda = $conexao->prepare($venda);
                    $inserir_venda->bindValue( ':user_id', $dados_de_usuario_sql->id);
                    $inserir_venda->bindValue(':produto_nome', $_POST['nome']);
                    $inserir_venda->bindValue(':data_venda', $_POST['data']);
                    $inserir_venda->bindValue(':produto_id', intval($_POST['id']));
                    $inserir_venda->bindValue(':valor_venda', $_POST['valor']);
                    $inserir_venda->bindValue(':tipo_de_pagamento', $_POST['tipo']);
                    $inserir_venda->bindValue(':quantidade', intval($_POST['qtd']));
                    $inserir_venda->execute();

                    $add = "UPDATE `user_financeiro` SET caixa = caixa + :caixa WHERE id= :id";
                    $caixa = $conexao->prepare($add);
                    $caixa->bindValue( ':id', $dados_de_usuario_sql->id);
                    $caixa->bindValue(':caixa', (floatval($_POST['valor'])* intval($_POST['qtd'])));
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