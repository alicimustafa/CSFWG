<?php
/* 
this class read the incomming url request determine what is the end point.
breaks up argument into an array and set the method type used
determine if only section of a page or a whole page
checks to see if the user has a valid token and if valid sets the user name and account type
*/   
class Request_obj {
    public $valid_user = false;  
    public $user_name = ""; 
    public $account_priv = "member";      
    public $end_point = "";   
    public $arg = array();    
    public $action ="";    
    public $section =false; 
    public $log_err = "";
    function __construct(){
        if(isset($_COOKIE['jwt'])){     // checking if the person is loged in and their token is autentic
                if($jwt_parts = explode(".", $_COOKIE['jwt'])){
                    if($jwt_body = base64_decode($jwt_parts[1])){
                        $jwt_signature = $jwt_parts[2];
                        $verify_signature = new Jwt_signature($jwt_body , $jwt_signature);
                        if($verify_signature->valid){
                            $this->valid_user = true;
                            $jwt_claims = json_decode($jwt_body);
                            $this->user_name = $jwt_claims->sub;
                            $this->account_priv = $jwt_claims->acc;
                        }
                    }
                }
        }

    }
    public function read_url($request){
        $this->arg = explode("/", rtrim($request,"/"));
        $this->end_point = array_shift($this->arg);
        if($this->end_point == "section"){
            $this->section = true;
            $this->end_point = array_shift($this->arg);
        }
        $this->action = $_SERVER['REQUEST_METHOD'];  // checking method
        if($this->action == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)){
            if($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE'){
                $this->action = 'DELETE';
            } else if($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT'){
                $this->action = 'PUT';
            } else {
                $this->action = false;
            }
        }
    }
}
?>