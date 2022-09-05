<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);
    function Check_user($usuario, $senha){
        include_once("./conexao.php");

        $sql = "SELECT id, nome, situacao, email, telefone, endereco FROM users_info WHERE email=:usuario and senha=:senha ";
        $pesquisa = $conexao->prepare($sql);
        $pesquisa->execute(array(
            ':usuario'=> $usuario
            ,':senha' => $senha));
        $puxar= $pesquisa->fetchAll();
        $conexao = null;

        $id = null;
        $nome = null;
        $situacao = null;
        $email = null;
        $telefone = null;
        $endereco = null;

        $check = 0;
        if(count($puxar) >0 ){
            $check = 1;
            foreach($puxar as $row){
                $id= $row['id'];
                $nome = $row['nome'];
                $situacao = $row['situacao'];
                $email = $row['email'];
                $telefone = $row['telefone'];
                $endereco = $row['endereco'];

            }
        }
        return array($check, $id, $nome, $situacao, 'email' => $email, 'telefone' => $telefone, 'endereco' => $endereco);
    }
    function Check_user_email($usuario){
        include("./conexao.php");
        $sql = "SELECT id, nome, situacao FROM users_info WHERE email=:usuario";
        $pesquisa = $conexao->prepare($sql);
        $pesquisa->execute(array(
            ':usuario'=> $usuario));
        $puxar= $pesquisa->fetchAll();
        $conexao = null;

        $id = null;
        $nome = null;
        $situacao = null;

        $check = 0;
        if(count($puxar) >0 ){
            $check = 1;
            foreach($puxar as $row){
                $id= $row['id'];
                $nome = $row['nome'];
                $situacao = $row['situacao'];
            }
        }
        return array($check, $id, $nome, $situacao);
    }
    class AuthController{
        public function login(){
            if(isset($_POST['user']) && isset($_POST['password'])){
                $resultado = Check_user($_POST['user'], md5($_POST['password']));

                if($resultado[0] >0){
                    $key = 'KILLLAKILLERUIM';
    
                    //Header Token
                    $header = [
                        'typ' => 'JWT',
                        'alg' => 'HS256'
                    ];
            
                    //Payload - Content
                    $payload = [
                        'id' => $resultado[1],
                        'name' => $_POST['user']
                    ];
            
                    //JSON
                    $header = json_encode($header);
                    $payload = json_encode($payload);
            
                    //Base 64
                    $header = base64_encode($header);
                    $payload = base64_encode($payload);
            
                    //Sign
                    $sign = hash_hmac('sha256', $header . "." . $payload, $key, true);
                    $sign = base64_encode($sign);
            
                    //Token
                    $token = $header . '.' . $payload . '.' . $sign;
                    return array($token, $resultado[2], $resultado[3],  'email' => $resultado['email'], 'telefone' => $resultado['telefone'], 'endereco' => $resultado['endereco']);  
                }
                else{
                    return 'Usuário não encontrado';
                }    
            }
            else{
                return 'Operação inválida';
            }
        }
        public function registro(){
            if(isset($_POST['user_nome']) && isset($_POST['user_email']) && isset($_POST['password'])){
                include_once("./conexao.php");
                $nome = $_POST['user_nome'];
                $email = $_POST['user_email'];
                $endereco = $_POST['endereco'];
                $telefone = $_POST['telefone'];
                $ip= $_POST['ip'];
                $plano = $_POST['plano_'];
                $valor_plano = '';
                $data_venci = date('Y-m-d', strtotime('+1 month'));
                $data_contratacao = date('Y-m-d');
                $password = md5($_POST['password']);
                if($plano == 'Normal'){
                    $valor_plano = '20';
                }
                if($plano == 'Médio'){
                    $valor_plano = '30';
                }
                if($plano == 'Avançado'){
                    $valor_plano = '40';
                }
                $resultado = Check_user_email($email);
                if($resultado[0] >0){
                    return $resultado[1];
                }
                else{
                    try{
                        $verif = strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9));
                        $sql= "INSERT INTO `users_info` (`nome`, `email`, `telefone`, `ip`, `plano`, `valor-plano`, `data_vencimento`, `data-contratacao`, `situacao`, `senha`, endereco, verif_code) VALUES
                        (:nome, :email, :telefone, :ip, 
                        :plano, :valor_plano, :data_venci, :data_contratacao, 'Aberto', :endereco
                        :password, :verif_code)";
                        $inserir = $conexao->prepare($sql);
                        $inserir->bindValue(':nome' ,$nome);
                        $inserir->bindValue(':email' ,$email);
                        $inserir->bindValue(':telefone' ,$telefone);
                        $inserir->bindValue(':ip' ,$ip);
                        $inserir->bindValue(':plano' ,$plano);
                        $inserir->bindValue(':valor_plano' ,$valor_plano);
                        $inserir->bindValue(':data_venci' ,$data_venci);
                        $inserir->bindValue(':data_contratacao' ,$data_contratacao);
                        $inserir->bindValue(':endereco' ,$endereco);
                        $inserir->bindValue(':password' ,$password);
                        $inserir->bindValue(':verif_code' ,$verif);
                        $inserir->execute();
    
                        $sql2= "INSERT INTO `user_financeiro`(caixa, custos_fixos, user_email) VALUES(0, 0, :email)";
                        $inserir2 = $conexao->prepare($sql2);
                        $inserir2->bindValue(':email' ,$email);
                        $inserir2->execute();
                        $conexao =null;
                        return '1';    
                    }
                    catch(Excepition $ex){
                        return '0';
                    }
                }
            }
            else{
                return 'Operação inválida';
            }
        }
        public static function checkAuth(){
            $http_header = apache_request_headers();
            if(isset($http_header[$GLOBALS['a']]) && $http_header[$GLOBALS['a']] != null){
                $bearer = explode(' ', $http_header[$GLOBALS['a']]);
                $token = explode('.', $bearer[1]);
                $header = $token[0];
                $payload = $token[1];
                $sign = $token[2];

                $valid = hash_hmac('sha256', $header . '.' . $payload, 'KILLLAKILLERUIM', true);
                $valid = base64_encode($valid);
                if($sign === $valid ){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        public static function dados_de_sql(){
            $http_header = apache_request_headers();
            $bearer = explode(' ', $http_header[$GLOBALS['a']]);
            $decode = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $bearer[1])[1]))));
            return $decode;
        }
        public function pesquisa_nome(){
            if(AuthController::checkAuth()){
                include_once("./conexao.php");
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $sql = "SELECT nome from users_info WHERE id=1";
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $nome = '';
                $tema = '';
                $array = array();
                $conexao = null;
                foreach($resultado as $row){
                    $nome = $row['nome'];
                    $tema = $row['tema'];
                }
                array_push($array, $nome, $tema);
                return $array;            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
    }
?>