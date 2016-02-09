<?php 
$err_mess = "Your information is incorrect";//error message that will be put into $log_err if info is incorrect
$log_err = ""; 
$javascript = "";
if($request_obj->action == "POST"){
    if(empty($_REQUEST['user_name']) or empty($_REQUEST['password'])){
        $log_err = $err_mess;
    } else {
        $user_name = htmlspecialchars($_REQUEST['user_name']);
        $user_password = htmlspecialchars($_REQUEST['password']);
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
                  $request_obj->valid_user = true;
                  $request_obj->user_name = $user_name;
                  $request_obj->account_priv = $row['rank'];
                  setcookie('jwt',$jwt_cookie);
                  $javascript = "<script>
    window.alert('stuf');
                  </script>";
              } else {
                  $log_err = $err_mess;
              }
          } else {
              $log_err = $err_mess;
          }
    }
} elseif($request_obj->action == "DELETE"){
    setcookie("jwt", "", time() - 3600);
    $request_obj->valid_user = false;
    $javascript = "<script>
    window.alert('stuf');
    </script>";
}
// fallowing hold default values for these variable so there is no errors 
$form_field = '
  <label for="user-input">User Name:</label>
  <input type="text" id="user-input">
  <label for="password-input">Password:</label>
  <input type="password" id="password-input">
  <input type="reset">
';
$submit_val = "Logg On";
$logging_value = "loggon" ;
if($request_obj->valid_user){
    $logging_value = "loggoff";
    $submit_val = "Logg Off";
    $form_field = "";
    echo "<p>Hello ".$request_obj->user_name."</p>";
}
echo $log_err;
?>
<form id="logging-form">
<?php echo $form_field; ?>
  <input type="hidden" id="logging-val" value="<?php echo $logging_value; ?>">
  <input type="submit" id="log-submit" value="<?php echo $submit_val; ?>">
</form>
<?php echo $javascript; ?>
