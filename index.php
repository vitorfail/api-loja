<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    require_once('Http/Rest.php');
    use Map\Http\Rest;

    if(isset($_REQUEST) && !empty($_REQUEST)){
        $rest = new Rest($_REQUEST);
        echo $rest->run();
    }
?>