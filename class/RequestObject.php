<?php
/* 
this class read the incomming url request determine what is the end point.
breaks up argument into an array and set the method type used
determine if only section of a page or a whole page and if front pannel or back
checks to see if the user has a valid token and if valid sets the user name and account type
*/   
class RequestObject {
    public $valid_user = false;  
    public $user_name = ""; 
	public $user_id = "";
    public $account_priv = "member";      
    public $end_point = "";   
    public $arg = array();    
    public $action ="";    
    public $section =false; 
	public $back = false;
    public $log_err = "";
	public $sync_token ="";
	public $db_password, $db_username, $enc_key;
	private $params = array();
    function __construct(){
		$ini_file = parse_ini_file("CSFWGcinfig.ini.php");
		$this->db_password = $ini_file['dbPassword'];
		$this->db_username = $ini_file['dbUserName'];
		$this->enc_key = $ini_file['encrypttionKey'];
    }
	public function checkCookie($jwt_verify){
        if(isset($_COOKIE['jwt'])){     // checking if the person is loged in and their token is autentic
			if($jwt_parts = explode(".", $_COOKIE['jwt'])){
				if($jwt_body = base64_decode($jwt_parts[1])){
					$jwt_signature = $jwt_parts[2];
					if($jwt_verify->validateSignature($jwt_body , $jwt_signature)){
						$this->valid_user = true;
						$jwt_claims = json_decode($jwt_body);
						$this->user_name = $jwt_claims->sub;
						$this->account_priv = $jwt_claims->acc;
						$this->user_id = $jwt_claims->id;
						$this->sync_token = $jwt_claims->sync;
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
		if($this->end_point == "back"){
			$this->back = true;
			$this->end_point = array_shift($this->arg);
		}
        $this->action = $_SERVER['REQUEST_METHOD'];  
		/* 
		checking method and if it is delete or put place the 
		variables from the client into $_REQUEST global
		*/
        if($this->action == 'DELETE' or $this->action == "PUT"){
			parse_str(file_get_contents('php://input'), $this->params);
			foreach($this->params as $key => $value){
				$_REQUEST[$key] = $value;
			}
        }
    }
	public function checkSyncToken(){
		if(isset($_REQUEST['sync_token'])){
			if($this->sync_token != $_REQUEST['sync_token']){
				$this->valid_user = false;
				$this->account_priv = "member";
				$this->user_name = "";
				$this->user_id = "";
			}
		}
	}
}
?>