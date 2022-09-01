<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);
    class FaturamentoController{
        public function faturamento_mensal(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql();
                $ano = md5($_POST['ano']);
                $sql = '';
                if($ano == '3fc59b42e26cc666d4e37b68933a05b9'){
                    $sql = "SELECT data_venda, (valor_venda*quantidade) AS total FROM `user_vendas` WHERE `user_id`= ".$dados_de_usuario_sql->id; 
                }
                else{
                    $sql = "SELECT data_venda, (valor_venda*quantidade) AS total FROM `user_vendas` WHERE YEAR(data_venda) = ".$_POST['ano']." AND `user_id`= ".$dados_de_usuario_sql->id;
                }
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
        public function pesquisa(){
            $faturamento_mensal = $this->faturamento_mensal();
            return  $faturamento_mensal;
        }
    }
?>