<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);
    class TipopagamentoController{
        public function tipos_de_pagamento(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql();
                $ano = md5($_POST['ano']);
                $sql = '';
                if($ano == '3fc59b42e26cc666d4e37b68933a05b9'){
                    $sql = "SELECT tipo_de_pagamento, SUM(quantidade) FROM user_vendas WHERE `user_id`= ".$dados_de_usuario_sql->id." GROUP BY tipo_de_pagamento";
                }
                else{
                    $sql = "SELECT tipo_de_pagamento, SUM(quantidade) FROM user_vendas WHERE YEAR(data_venda) = ".$_POST['ano']." AND `user_id`= ".$dados_de_usuario_sql->id." GROUP BY tipo_de_pagamento";
                } 
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
        public function pesquisa(){
            $tipos_de_pagamento = $this->tipos_de_pagamento();
            return $tipos_de_pagamento;
        }
    }
?>