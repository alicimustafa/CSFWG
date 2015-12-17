<?php
/*this class either veryfies the signature or creates one for JWT
if only on argamunt is provided it creates signature for the argument
if two arguments provided it compares them to see if they match */
class Jwt_signature {
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