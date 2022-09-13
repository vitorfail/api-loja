<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);
    class EstoqueController{
        public function valor_estoque(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $sql = "SELECT SUM((produto_valor+ custo_indireto) * quantidade) FROM `user-produtos` WHERE `user-id`= ".$dados_de_usuario_sql->id;
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $total = 0;
                $array = array();
                $conexao = null;
                return floatval($resultado[0]['SUM((produto_valor+ custo_indireto) * quantidade)']);            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function numero_de_roupas(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $sql = "SELECT SUM(quantidade) FROM `user-produtos` WHERE Vendido='Não' AND `user-id`= ".$dados_de_usuario_sql->id;
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $total = $resultado[0]["SUM(quantidade)"];
                $conexao = null;
                return $total;            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function estoque_descri(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $sql = "SELECT * FROM `user-produtos` WHERE `user-id`= ".$dados_de_usuario_sql->id." ORDER BY id DESC";
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $total = 0;
                $conexao = null;
                return array_slice($resultado, $_POST['index'], $_POST['tamanho'] );            
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function nome(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $sql = "SELECT nome, situacao, data_vencimento, foto_perfil , endereco, email, telefone FROM `users_info` WHERE id= ".$dados_de_usuario_sql->id;
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
        public function custo_fixo(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $ano = date("Y");
                $mes = date("m");
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $custo_fixo = "SELECT SUM(valor_custo) FROM user_custos_fixo WHERE (user_id, YEAR(data_pagamento), MONTH(data_pagamento), situacao) = (".$dados_de_usuario_sql->id.", ".$ano.", ".$mes.", 'Pago')";
                $pesquisa = $conexao->query($custo_fixo);
                $resultado = $pesquisa->fetchAll();
                if($resultado[0]["SUM(valor_custo)"] == null){
                    return 0;
                }
                else{
                    return $resultado[0]["SUM(valor_custo)"];
                }    
            }
            else{
                return 'Usuário não autenticado';              
            }
        }
        public function pesquisa(){
            $nome = $this->nome();
            $valor_estoque = $this->valor_estoque();
            $estoque_descri = $this->estoque_descri();
            $custo_fixo = floatval($this->custo_fixo()) /intval($this->numero_de_roupas());
            return array('valor_estoque' => $valor_estoque, 
            'descricao' => $estoque_descri,'nome' => $nome[0],
            'situacao' =>  $nome[1], 'data_vencimento' =>  $nome[2],
             'foto_perfil' => $nome[3] , 'endereco' => $nome[4], 
             'email' => $nome[5], 'telefone' => $nome[6], "custo_fixo" => $custo_fixo);
        }
    }
?>