<?php 
class Logging {
    protected $user_name = "";
    protected $password = "";
    function __contstruct($log_type){
        if($log_type == "loggon"){
            $this->user_name = htmlspecialchars($_POST['user_name']);
            $this->password = htmlspecialchars($_POST['password']);
        }
    }
}
?>