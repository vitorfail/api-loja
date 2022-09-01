<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);
    class HomeController{
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
        public function faturamento_mensal(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $sql = "SELECT data_venda, (valor_venda*quantidade) AS total FROM `user_vendas` WHERE YEAR(data_venda) AND `user_id`= ".$dados_de_usuario_sql->id;
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $janeiro = 0;
                $fevereiro = 0;
                $marco = 0;
                $abril = 0;
                $maio = 0;
                $junho = 0;
                $julho = 0;
                $agosto = 0;
                $setembro = 0;
                $outubro = 0;
                $novembro = 0;
                $dezembro = 0;
                $array = array();
                $conexao = null;
                if(count($resultado) == 0){
                    $janeiro = 0;
                    $fevereiro = 0;
                    $marco = 0;
                    $abril = 0;
                    $maio = 0;
                    $junho = 0;
                    $julho = 0;
                    $agosto = 0;
                    $setembro = 0;
                    $outubro = 0;
                    $novembro = 0;
                    $dezembro = 0;    
                }
                else{
                    foreach($resultado as $row){
                        $dataf =  explode('-', $row['data_venda']);
                        if($dataf[1] == '01'){
                            $janeiro = $janeiro + $row['total'];
                        }
                        if($dataf[1] == '02'){
                            $fevereiro = $fevereiro + $row['total'];
                        }
                        if($dataf[1] == '03'){
                            $marco = $marco + $row['total'];
                        }
                        if($dataf[1] == '04'){
                            $abril = $abril + $row['total'];
                        }
                        if($dataf[1] == '05'){
                            $maio = $maio + $row['total'];
                        }
                        if($dataf[1] == '06'){
                            $junho = $junho + $row['total'];
                        }
                        if($dataf[1] == '07'){
                            $julho = $julho + $row['total'];
                        }
                        if($dataf[1] == '08'){
                            $agosto = $agosto + $row['total'];
                        }
                        if($dataf[1] == '09'){
                            $setembro = $setembro + $row['total'];
                        }
                        if($dataf[1] == '10'){
                            $outubro = $outubro + $row['total'];
                        }
                        if($dataf[1] == '11'){
                            $novembro = $novembro + $row['total'];
                        }
                        if($dataf[1] == '12'){
                            $dezembro = $dezembro + $row['total'];
                        }
                    }    
                }
                return array($janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro);            }
            else{
                return 'Usuário não autenticado';              
            }

        }
        public function tipos_de_pagamento(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                $sql = "SELECT tipo_de_pagamento, SUM(quantidade) FROM user_vendas WHERE `user_id`= ".$dados_de_usuario_sql->id." GROUP BY tipo_de_pagamento";
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $array = array();
                $avista = 0;
                $cartao = 0;
                $boleto = 0;
                $pix = 0;  

                $conexao = null;
                if(count($resultado) == 0){
                    $avista = 0;
                    $cartao = 0;
                    $boleto = 0;
                    $pix = 0;  
                }
                else{
                    foreach($resultado as $row){
                        if($row['tipo_de_pagamento'] == 'A vista'){
                            $avista = intval($row['SUM(quantidade)']);
                        }
                        if($row['tipo_de_pagamento'] == 'Parcelado'){
                            $cartao = intval($row['SUM(quantidade)']);
                        }
                        if($row['tipo_de_pagamento'] == 'Boleto'){
                            $boleto = intval($row['SUM(quantidade)']);
                        }
                        if($row['tipo_de_pagamento'] == 'Pix'){
                            $pix = intval($row['SUM(quantidade)']);
                        }
                    }
                }
                return array($avista, $cartao, $boleto, $pix);            
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
        public function pesquisa(){
            $nome = $this->nome();
            $valor_estoque = $this->valor_estoque();
            $numero_de_roupas = $this->numero_de_roupas();
            $valor_caixa = $this->valor_caixa();
            $faturamento_mensal = $this->faturamento_mensal();
            $tipos_de_pagamento = $this->tipos_de_pagamento();
            return array('numero_roupas' => $numero_de_roupas, 'valor_estoque' => $valor_estoque, 
            'nome' => $nome[0], 'situacao' => $nome[1], 'data_vencimento' => $nome[2], 
            'foto_perfil' => $nome[3], 'endereco' => $nome[4], 
            'email' => $nome[5], 'telefone' => $nome[6], 
            'valor_caixa' => $valor_caixa, 'faturamento' => $faturamento_mensal, 'tipos_de_pagamento' => $tipos_de_pagamento);
        }
    }
?>