<?php
if($request_obj->account_priv == "Admin" and $request_obj->valid_user){
    //print_r($request_obj);
    if(isset($request_obj->arg[0])){
        switch($request_obj->arg[0]){
            case "resetKey" :
                resetkey($request_obj);
                break;
            case "changeDueDate" :
                changeDueDate($request_obj);
                break;
        }
    }
}
function resetkey($request_obj){
    $request_obj->enc_key = bin2hex(openssl_random_pseudo_bytes(10));
    writeToConfigFile($request_obj);
	setcookie("jwt", "", time() - 3600);
	$request_obj->valid_user = false;
}
function changeDueDate($request_obj){
    $request_obj->due_date_month = $_REQUEST['due_month'];
    $request_obj->due_date_day = $_REQUEST['due_day'];
    writeToConfigFile($request_obj);
}
function writeToConfigFile($request_obj){
    $db_username = $request_obj->db_username;
    $db_password = $request_obj->db_password;
    $enc_key = $request_obj->enc_key;
    $due_date_month = $request_obj->due_date_month;
    $due_date_day = $request_obj->due_date_day;
    $str ="
;<?php 
;die(); 
;/* 
[database info] 
dbUserName = $db_username 
dbPassword = $db_password

[encrypttion info] 
encrypttionKey = $enc_key

[due date]
dueMonth = $due_date_month
dueDay = $due_date_day
;*/  

;?> 
";
    file_put_contents("CSFWGconfig.ini.php", $str);
}
?>
<form id="member-due-date">
    <fieldset>
        <legend>Due Date Area:</legend>
        <p>current due date : <?php echo MONTH_NAMES[$request_obj->due_date_month-1]," ",$request_obj->due_date_day ?></p>
        <br>
        <p>Due date mont:
            <select id="due-date-month">
            <?php 
            foreach(MONTH_NAMES as $key => $value){
                echo '<option value="',$key+1,'">',$value,'</option>';
            }
            ?>
            </select>
         Due date day:<input id="due-date-day" type="number" value=""></p>
        <br>
        <input type="submit" value="Change due date">
    </fieldset>
</form>
<br>
<fieldset>
    <legend>Key Reset Area</legend>
    <button type='button' id='key-reset' >Reset key</button>
</fieldset>