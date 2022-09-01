<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);
    class CaixaController{
        public function despesas_hoje(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $datahoje = date('Y-m-d');
                $datasql = explode('-', $datahoje);
                $sql = "SELECT SUM(valor_despesas) FROM `user_despesas` WHERE `user_id` = ".$dados_de_usuario_sql->id;
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $total = 0;
                if($resultado[0]["SUM(valor_despesas)"] == null){
                    $total = 0;
                }
                else{
                    $total = $resultado[0]["SUM(valor_despesas)"];
                }
                $conexao = null;
                return $total;            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function caixa(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $datahoje = date('Y-m-d');
                $datasql = explode('-', $datahoje);
                $sql = "SELECT descricao, valor_despesas AS valor, data_pagamento FROM `user_despesas` WHERE (MONTH(data_pagamento), DAY(data_pagamento), YEAR(data_pagamento), `user_id`)  =  ('".$_POST["mes"]."' ,  '".$_POST["dia"]."', '".$_POST["ano"]."', ".$dados_de_usuario_sql->id.")";
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();

                $sql2 = "SELECT CONCAT(produto_nome, '(', quantidade, ')') AS descricao, valor_venda*quantidade AS valor FROM `user_vendas` WHERE (MONTH(data_venda), DAY(data_venda), YEAR(data_venda), `user_id`)  =  ('".$_POST["mes"]."' ,  '".$_POST["dia"]."', '".$_POST["ano"]."',".$dados_de_usuario_sql->id.")";
                $pesquisa2 = $conexao->query($sql2);
                $resultado2 = $pesquisa2->fetchAll();
                $result = [];
                $conexao = null;
                return array_merge($resultado, $resultado2);            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function nome(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $sql = "SELECT nome, situacao, data_vencimento, foto_perfil, endereco, email, telefone FROM `users_info` WHERE id= ".$dados_de_usuario_sql->id;
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $nome = '';
                $situacao = '';
                $vencimento = '';
                $foto_perfil = '';
                $endereco = '';  
                $email = ''; 
                $telefone = '';
                $conexao = null;
                foreach($resultado as $row){
                    $nome = $row['nome'];
                    $situacao = $row['situacao'];
                    $vencimento = $row["data_vencimento"];
                    $foto_perfil = $row["foto_perfil"];
                    $endereco = $row['endereco']; 
                    $email = $row['email']; 
                    $telefone = $row['telefone']; 
                }
                $conexao = null;
                return array($nome, $situacao, $vencimento, $foto_perfil, $endereco, $email, $telefone);            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function valor_caixa(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql();
                $sql = "SELECT caixa FROM user_financeiro WHERE `id`= ".$dados_de_usuario_sql->id;
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                
                return floatval($resultado[0]['caixa']);            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function recebido_mes(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $datahoje = date('Y-m-d');
                $datasql = explode('-', $datahoje);
                $sql = "SELECT SUM(valor_venda) FROM `user_vendas` WHERE (`user_id`, MONTH(data_venda),  YEAR(data_venda)) = (".$dados_de_usuario_sql->id.", '".$_POST["mes"]."', '".$_POST["ano"]."')";
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $total = 0;
                if($resultado[0]["SUM(valor_venda)"] == null){
                    $total = 0;
                }
                else{
                    $total = $resultado[0]["SUM(valor_venda)"];
                }
                $conexao = null;
                return $total;            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function pesquisa(){
            $nome = $this->nome();
            $despesas_hoje = $this->despesas_hoje();
            $valor_caixa = $this->valor_caixa();
            $caixa = $this->caixa();
            return array('despesas' => round(floatval($despesas_hoje), 2), 'valor_caixa' => round($valor_caixa, 2), 
            'nome' => $nome[0], 'situacao' => $nome[1], 
            'data_vencimento' => $nome[2], 'foto_perfil' => $nome[3], 'endereco' => $nome[4], 
            'email' => $nome[5], 'telefone' => $nome[6], 
            'caixa' => $caixa, round(floatval($this->recebido_mes()), 2));
        }
    }
?>