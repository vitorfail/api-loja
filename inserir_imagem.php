<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');

    $_POST= json_decode(file_get_contents('php://input'), true);
    class InseririmagemController{
        public function pesquisa(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $url = 'fotos_perfil/';
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    if($_REQUEST['alt'] === 'imagem'){
                        $file_tmp = $_FILES['file']['tmp_name'];
                        $tmp = explode('.',$_FILES['file']['name']);
                        $file_ext = strtolower(end($tmp));
                        $file = $url . uniqid() . '.'.$file_ext;
                        move_uploaded_file($file_tmp, $file);
                        if($_REQUEST['username'] !== ''){
                            unlink($_REQUEST['username']);
                        }
                        $sql = "UPDATE `users_info` SET foto_perfil = '".$file."', nome = :nome, endereco = :endereco, email = :email, telefone = :telefone WHERE id= :id";    
                        $salvar = $conexao->prepare($sql);
                        $salvar->bindValue(':id',  $dados_de_usuario_sql->id);
                        $salvar->bindValue(':nome',  $_REQUEST['nome']);
                        $salvar->bindValue(':endereco',  $_REQUEST['endereco']);
                        $salvar->bindValue(':email',  $_REQUEST['email']);
                        $salvar->bindValue(':telefone',  $_REQUEST['numero']);
                        $salvar->execute();
                        return '1';    
                    }
                    $sql2 = "UPDATE `users_info` SET  nome = :nome, endereco = :endereco, email = :email, telefone = :telefone WHERE id= :id";
                    $salvar = $conexao->prepare($sql2);
                    $salvar->bindValue(':id',  $dados_de_usuario_sql->id);
                    $salvar->bindValue(':nome',  $_REQUEST['nome']);
                    $salvar->bindValue(':endereco',  $_REQUEST['endereco']);
                    $salvar->bindValue(':email',  $_REQUEST['email']);
                    $salvar->bindValue(':telefone',  $_REQUEST['numero']);

                    $salvar->execute();

                      
                    return '1';
                }
                catch(Exception $ex){
                    $conexao = null;  
                    return '0';
                }
            }
            else{
                return "Usuário não autenticado";
            }
        }
    }
?>