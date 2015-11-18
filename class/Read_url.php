<?php
class read_url {
    public $valid_user = false;  //this is to see if user is valid and loged in
    public $user_name = "";      //  user name of the user if valid and loged in
    public $end_point = "";     // this used to see what area of the site the user wanting to acces
    public $arg = array();    //this hold the parameters that needed for the end point
    public $action ="";    // what action to take like GET POST PUT DELETE
    public $section =false; //if it a section or whole page
    function __construct($request){
        $this->arg = explode("/", rtrim($request,"/"));
        $this->end_point = array_shift($this->arg);
        if($this->end_point == "section"){
            $this->section = true;
            $this->end_point = array_shift($this->arg);
        }
        $this->action = $_SERVER['REQUEST_METHOD'];
        if($this->action == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)){
            if($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE'){
                $this->action = 'DELETE';
            } else if($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT'){
                $this->action = 'PUT';
            } else {
                $this->action = false;
            }
        }
        if(isset($_COOKIE['jwt'])){     // this to check if jwt is set and if it is valid 
            if($jwt_parts = explode(".", $_COOKIE['jwt'])){
                if($jwt_body = base64_decode($jwt_parts[1])){
                    $jwt_signature = $jwt_parts[2];
                    $verify_signature = new $jwt_signature($jwt_body , $jwt_signature);
                    if($verify_signature->valid){
                        $this->valid_user = true;
                        $jwt_claims = json_decode($jwt_body);
                        $this->user_name = $jwt_claims['sub'];
                    }
                }
            }
        }
    }
}
?>