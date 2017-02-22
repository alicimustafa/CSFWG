<?php
if($request_obj->account_priv == "Admin" and $request_obj->valid_user){
    print_r($request_obj);
    /*
 	$new_key = bin2hex(openssl_random_pseudo_bytes(10));
	$db_pass = $request_obj->db_password;
	$db_user = $request_obj->db_username;
	$str = "
;<?php 
;die(); 
;/* 
[database info] 
dbUserName = $db_user 
dbPassword = $db_pass 

[encrypttion info] 
encrypttionKey = $new_key 
;  

;?>
		";
	file_put_contents("paswordstuff.php", $str);
	setcookie("jwt", "", time() - 3600);
	$request_obj->valid_user = false;
 */  
}
?>
<form id="member-due-date">
    <p>Due date mont:<input type="text" value=""></p>
    <p>Due date day:<input type="text" value=""></p>
    <input type="submit" value="Change due date">
</form>