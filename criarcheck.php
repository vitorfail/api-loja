<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");

    class CheckController{
      public function pesquisa(){
        if(AuthController::checkAuth()){
          try{
            include_once('conexao.php');
            $dados_de_usuario_sql = AuthController::dados_de_sql(); 
            $code = random_bytes(32);
            $rec = str_split(bin2hex($code), 32);
            $cryp = $rec[0].$rec[1].$dados_de_usuario_sql->id;
  
            $check = "UPDATE `users_info` SET check_pay = :che WHERE id = ".$dados_de_usuario_sql->id;
            $criarcheck = $conexao->prepare($check);
            $criarcheck->bindValue(':che', $cryp);
            $criarcheck->execute();
            $conexao = null;    

            return $cryp;
          }
          catch(Exception $ex){
            $conexao = null;    
            return '1';
          }
        }
      }
    }


?>