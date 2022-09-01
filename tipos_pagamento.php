<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Headers: *");

    class TipospagamentoController{
        public function pesquisa(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    $sql = "SELECT * from pagamentos_".$dados_de_usuario_sql->name."_".$dados_de_usuario_sql->id;
                    $resultado = $conexao->query($sql);
                    $pesquisa = $resultado->fetchAll();
                    $debito_ = array();
                    $credito_ = array();
                    $credito_parcelado_ = array();
                    $a_vista_ = array();
                    $boleto_ = array();
                    $cheque_ = array();
                    $array = array();
                    $conexao =null;
                    if(count($pesquisa) > 0 ){
                        foreach($pesquisa as $row){
                            if($row['tipo'] ==  'debito'){
                                array_push($debito_, 1);
                            }
                            if($row['tipo'] ==  'credito'){
                                array_push($credito_, 1);
                            } 
                            if($row['tipo'] == 'credito-parcelado'){
                                array_push($credito_parcelado_, 1);
                            } 
                            if($row['tipo'] == 'a-vista'){
                                array_push($a_vista_, 1);
                            }
                            if($row['tipo'] ==  'boleto'){
                                array_push($boleto_, 1);
                            }
                            if($row['tipo'] ==  'cheque'){
                                array_push($cheque_, 1);
                            }
                        }
                        array_push($array, count($debito_), count($credito_), count($credito_parcelado_), count($a_vista_), count($boleto_), count($cheque_));
                        return $array;
                    }
                    else{
                        return "1";
                    }
                }
                catch(Exception $ex){
                    $conexao =null;
                    return "2";   
                }
            }
            else{
                return 'Usuário não autenticado';
            }
        }
    }

?>