<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');
    $GLOBALS['a'] = 'Authorization';
    $_POST = json_decode(file_get_contents("php://input"), true);
    class ContasController{
        public function contas(){
            if(AuthController::checkAuth()){
                include('conexao.php');
                $dados_de_usuario_sql = AuthController::dados_de_sql();
                $ano = md5($_POST['ano']);
                $mes = md5($_POST['mes']);
                $sql = '';
                if($mes == "ce01cfda7eb894982ea7d3623e050298"){
                    $sql = "SELECT data_vencimento AS data_vencimento, data_pagamento AS data_pagamento, descricao AS descricao , valor_despesas AS valor_despesas, situacao AS situacao   FROM `user_despesas` WHERE YEAR(data_vencimento) = ".$_POST['ano']." and `user_id` = ".$dados_de_usuario_sql->id;
                }
                else{
                    $sql = "SELECT data_vencimento AS data_vencimento, data_pagamento AS data_pagamento, descricao AS descricao , valor_despesas AS valor_despesas, situacao AS situacao  FROM `user_despesas` WHERE (YEAR(data_vencimento), MONTH(data_vencimento), `user_id` ) = (".$_POST['ano'].", ".$_POST['mes'].",".$dados_de_usuario_sql->id.")";
                }
                $pesquisa = $conexao->query($sql);
                $resultado = $pesquisa->fetchAll();
                $conexao = null;
                if($resultado != false){
                    if(count($resultado) > 0){
                        return $resultado;            
                    }
                    else{
                        return 0;
                    }
                }
                else{
                    return 0;
                }
            }
            else{
                return 'Usuário não autenticado';              
            }

        }
        public function pesquisa(){
            $contas = $this->contas();
            return  $contas;
        }
    }
?>