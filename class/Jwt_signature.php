<?php
class jwt_signature {
    public $valid = false;
    public $signature = "not working";
    function __construct(){
        $arg = func_get_args();
        if(func_num_args() == "1"){
            $this->signature = hash_hmac("sha256", $arg[0], 'key');
        } else {
            $this->valid = hash_equals( hash_hmac("sha256", $arg[0], 'key') , $arg[1]); 
        }
    }
}
?>