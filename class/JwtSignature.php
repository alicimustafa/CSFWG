<?php
/*this class either veryfies the signature or creates one for JWT
if only on argamunt is provided it creates signature for the argument
if two arguments provided it compares them to see if they match */
class JwtSignature {
    //public $valid = false;
    //public $signature = "not working";
	private $key;
    function __construct($enc_key){
		$this->key = $enc_key;
    }
	public function createSignature($jwt_body){
		return hash_hmac("sha256", $jwt_body, $this->key);
	}
	public function validateSignature($jwt_body, $jwt_signature){
		return hash_equals( hash_hmac("sha256", $jwt_body, $this->key) , $jwt_signature);
	}
}
?>