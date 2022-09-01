<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    date_default_timezone_set('America/Sao_Paulo');

    $_POST= json_decode(file_get_contents('php://input'), true);
    class ProdutosController{
        public function pesquisa(){
            if(AuthController::checkAuth()){
                try{
                    include_once('conexao.php');
                    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
                    $sql = "SELECT id, `produto-nome`, quantidade, produto_valor, custo_indireto, percentual FROM `user-produtos`
                     WHERE `user-id` = ".$dados_de_usuario_sql->id." ORDER BY `produto-nome` ASC";
                    $resultado = $conexao->query($sql);
                    $pesquisa = $resultado->fetchAll();
                    $conexao = null;
                    $ids = [];
                    $nomes = [];
                    $qtd = [];
                    $produto_valor = [];
                    $custo_indireto = [];
                    $percentual = [];
                    if(count($pesquisa) == 0){
                        $ids = 0;
                        $nomes = 0; 
                        $qtd = 0;
                        $produto_valor = 0;
                        $custo_indireto = 0;
                        $percentual = 0;    
                    }
                    else{
                        foreach($pesquisa as $linha){
                            array_push($ids, $linha['id']);
                            array_push($nomes, $linha['produto-nome']);
                            array_push($qtd, $linha['quantidade']);
                            array_push($produto_valor, $linha['produto_valor']);
                            array_push($custo_indireto, $linha['custo_indireto']);
                            array_push($percentual, $linha['percentual']);
                        }
                    }    
                    return array('id'=> $ids, 'nomes' => $nomes, 'qtd' => $qtd, 'produto_valor' => $produto_valor, 'custo_indireto' => $custo_indireto, 'percentual' => $percentual);
                }
                catch(Exception $ex){
                    $conexao = null;  
                    return '0';
                }
            }
            else{
                return 'Usuário não autenticado';
            }
        }
    }
?>