<?php
    namespace Map\Http\Controllers;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: *");
    class UsersController{
        public function get() {
            if(AuthController::checkAuth()){
                return array(1=>'Rafael', 2=> 'sucess');
            }
            else{
                return 'Usuário não autenticado';
            }
        }
    }
?>