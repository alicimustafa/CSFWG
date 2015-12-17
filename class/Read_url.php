<?php
/* 
this class read the incomming url request determine what is the end point.
breaks up argument into an array and set the method type used
determine if only section of a page or a whole page
checks to see if the user has a valid token and if valid sets the user name and account type
*/   
class Read_url {
    public $valid_user = false;  
    public $user_name = ""; 
    public $account_priv = "member";      
    public $end_point = "";   
    public $arg = array();    
    public $action ="";    
    public $section =false; 
    public $log_err = "";
    private $err_mess = "Your information is incorrect";//error message that will be put into $log_err if info is incorrect
    function __construct($request){
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
        if(isset($_POST['logging'])){
            /* this section is for checking if the person logging on or off
            if logging on checks if there password is correct and if it is 
            set jwt for the user */
            if($_POST['logging'] === "loggon"){
                $user_name = htmlspecialchars($_POST['user_name']);
                $user_password = htmlspecialchars($_POST['password']);
                if(empty($user_name) or empty($user_password)){
                    $log_err = $err_mess;
                } else {
                    include("class/connect.php");
                    $col_select = "
                      SELECT 
                      log_in_tbl.log_pw as pass,
                      members_tbl.member_rank as rank
                      FROM log_in_tbl
                      INNER JOIN members_tbl 
                      ON log_in_tbl.member_id = members_tbl.member_id
                      WHERE log_in_tbl.log_un = :user";
                    $stmt = $pdo->prepare($col_select);
                    $stmt->execute(array('user'=>$user_name));
                    if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        if($row['pass'] == $user_password){
                            $jwt_header = json_encode(array('typ'=>'JWT','alg'=>'HS256'));
                            $jwt_body = json_encode(array('sub'=>$user_name,'acc'=>$row['rank']));
                            $signature = new Jwt_signature($jwt_body);
                            $jwt_sig = $signature->signature;
                            $jwt_cookie = base64_encode($jwt_header).".".base64_encode($jwt_body).".".$jwt_sig;
                            $this->valid_user = true;
                            $this->user_name = $user_name;
                            $this->account_priv = $row['rank'];
                            setcookie('jwt',$jwt_cookie);
                        } else {
                            $this->log_err = $this->err_mess;
                        }
                    } else {
                        $this->log_err = $this->err_mess;
                    }
                }
            } else { 
                setcookie("jwt", "", time() - 3600);
            } 
        } else {
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
    }
}
?>