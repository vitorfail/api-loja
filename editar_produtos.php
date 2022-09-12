<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');

    $_POST= json_decode(file_get_contents('php://input'), true);
    class EditarController{
        public function pesquisa(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    $sql = "UPDATE `user-produtos` SET  `produto-nome`= :nome, quantidade = :quantidade, 
                    produto_valor = :valor, custo_indireto =  :custo_indireto , percentual = :percentual 
                     WHERE `user-id` = ".$dados_de_usuario_sql->id." AND id = :id";
                    $pesquisa = $conexao->prepare($sql);
                    $pesquisa->bindValue(':nome', $_POST['nome']);
                    $pesquisa->bindValue(':quantidade', $_POST['quantidade']);
                    $pesquisa->bindValue(':valor', $_POST['valor']);
                    $pesquisa->bindValue(':custo_indireto', $_POST['custo_indireto']);
                    $pesquisa->bindValue(':percentual', $_POST['percentual']);
                    $pesquisa->bindValue(':id', $_POST['id']);
                    $pesquisa->execute();
                    $conexao = null;
                    return '1';
                }
                catch(Exception $ex){
                    $conexao = null;  
                    return '0';
                }
            }
            else{
                return 'Usuário não autenticado';
            }
        }
        public function puxar_info(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    $sql = "SELECT `produto-nome`, quantidade, produto_valor, custo_indireto , percentual FROM `user-produtos` 
                     WHERE `user-id` = ".$dados_de_usuario_sql->id." AND id = ".$_POST['id'];
                    $pesquisa = $conexao->query($sql);
                    $pesquisa->execute();
                    $result = $pesquisa->fetchAll();
                    if($result){
                        $conexao = null;
                        return $result[0];    
                    }
                    else{
                        $conexao = null;
                        return '0';    
                    }
                }
                catch(Exception $ex){
                    $conexao = null;  
                    return '0';
                }
            }
            else{
                return 'Usuário não autenticado';
            }
        }
    }
?>