<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");

    class CheckcodeController{
      public function pesquisa(){
        if(AuthController::checkAuth()){
          try{
            include_once('conexao.php');
            $dados_de_usuario_sql = AuthController::dados_de_sql();
            $code = str_split($_POST['code'], 64);
            $check = "SELECT check_pay FROM `users_info` WHERE id = ".intval($code[1]);
            $criarcheck = $conexao->query($check);
            if($criarcheck == false){
              $conexao = null;
              return '0';     
            }
            else{
              $resultado = $criarcheck->fetchAll();
              if(count($resultado) == 0){
                $conexao = null;
                return '0';
              }
              else{
                $inserir = "UPDATE `users_info` SET  situacao = :sit WHERE id = ".intval($code[1]);
                $insert = $conexao->prepare($inserir);
                $insert->bindValue(':sit', "Pago");
                $insert->execute();
                $conexao = null;
                return '1'; 
              }
            }
          }
          catch(PDOException $ex){
            $conexao = null;    
            return '0';
          }
        }
      }
    }


?>