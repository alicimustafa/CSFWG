    log in pannel
<?php 
// fallowing hold default values for these variable so there is no errors 
$log_err = ""; 
$form_field = '
  <label for="user-input">User Name:</label>
  <input type="text" name="user_name" id="user-input">
  <label for="password-input">Password:</label>
  <input type="password" name="password" id="password-input">
  <input type="reset">
';
$submit_val = "Logg On";
$logging_value = "loggon" ;
if(isset($_REQUEST['request'])){
    $action = htmlspecialchars($_SERVER["PHP_SELF"])."?request=".$_REQUEST['request'];
    $log_err = $read_url->log_err ;
    if($read_url->valid_user){
        $logging_value = "loggoff";
        $submit_val = "Logg Off";
        $form_field = "";
        echo "<p>Hello ".$read_url->user_name."</p>";
    }
}else { 
    $action = htmlspecialchars($_SERVER["PHP_SELF"]);
}
echo $log_err;
?>
<form action="<?php echo $action;?>" method="post">
<?php echo $form_field; ?>
  <input type="hidden" name="logging" value="<?php echo $logging_value; ?>">
  <input type="submit" value="<?php echo $submit_val; ?>">
</form>
