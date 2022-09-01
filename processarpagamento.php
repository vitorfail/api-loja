<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");

	require("mercadopago/vendor/autoload.php");


	MercadoPago\SDK::setAccessToken("APP_USR-4390047053471485-080610-8552e3de7962295c5375753a253cd0f5-517207508");

  $preference = new MercadoPago\Preference();

  $plano = ['Plano', 1, '30,00'];
  try{
    include_once('conexao.php');
    $dados_de_usuario_sql = AuthController::dados_de_sql(); 
    $code = random_bytes(32);
    $rec = str_split(bin2hex($code), 32);
    $cryp = $rec[0].$rec[1].$dados_de_usuario_sql->id;

    $check = "UPDATE `users_info` SET check_pay = :che WHERE id = ".$dados_de_usuario_sql->id;
    $criarcheck = $conexao->prepare($check);
    $criarcheck->bindValue(':che', $cryp);
    $criarcheck->execute();
    $conexao = null;    

    $item2 = new MercadoPago\Item();
    $item2->id = "00002";
    $item2->title = $plano[0]; 
    $item2->quantity = $plano[1];
    $item2->unit_price = str_replace(',', '.', $plano[2]);

  
    
    $preference->items = array($item2);
    $preference->external_reference = 4545;
    $preference->back_urls = array(
      "success" => "http://localhost:3000/check/".$cryp,
      "failure" => "https://www.youtube.com/?hl=pt",
      "pending" => "https://www.youtube.com/?hl=pt"
    );
    $preference->save();
    echo $preference->init_point;    # Save the preference and send the HTTP Request to create
  
  }
  catch(Exception $ex){
    $conexao = null;    
    return '1';
  }

# Building an item


  
  # Return the HTML code for button
  
?>