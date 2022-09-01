<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');

    $_POST= json_decode(file_get_contents('php://input'), true);
    class InserirprodutosController{
        public function pesquisa(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    $sql = "INSERT into `user-produtos` (`user-id`, `produto-nome`, produto_valor , percentual, Vendido, quantidade, custo_indireto) 
                    values ( :user_id, :produto_nome, :produto_valor, :percentual, :Vendido, :quantidade,  :custo_indireto)";
                    $salvar = $conexao->prepare($sql);
                    $salvar->bindValue( ':user_id', $dados_de_usuario_sql->id);
                    $salvar->bindValue(':produto_nome', $_POST['produto_nome']);
                    $salvar->bindValue(':produto_valor', $_POST['produto_valor']);
                    $salvar->bindValue(':percentual', $_POST['percentual']);
                    $salvar->bindValue(':Vendido', 'Não');
                    $salvar->bindValue(':quantidade', $_POST['quantidade']);
                    $salvar->bindValue(':custo_indireto', 0);
                    $salvar->execute();
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