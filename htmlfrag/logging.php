<?php 
$err_mess = "<span class='error'>Your information is incorrect</span>";//error message that will be put into $log_err if info is incorrect
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
          log_in.member_id,
          log_in.log_pw AS pass,
          ranks.rank_name AS rank,
          members.first_nm as name
          FROM log_in
          INNER JOIN members 
          ON log_in.member_id = members.member_id
		  INNER JOIN ranks
          ON members.rank_id = ranks.rank_id
          WHERE log_in.log_un = :user";
          $stmt = $pdo->prepare($col_select);
          $stmt->execute(array(':user'=>$user_name));
          if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              if($row['pass'] == $user_password){
                  $jwt_header = json_encode(array('typ'=>'JWT','alg'=>'HS256'));
                  $jwt_body = json_encode(array('sub'=>$row['name'],'acc'=>$row['rank'],'id'=>$row['member_id']));
                  $signature = new Jwt_signature($jwt_body);
                  $jwt_sig = $signature->signature;
                  $jwt_cookie = base64_encode($jwt_header).".".base64_encode($jwt_body).".".$jwt_sig;
                  $request_obj->valid_user = true;
                  $request_obj->user_name = $row['name'];
				  $request_obj->user_id = $row['member_id'];
                  $request_obj->account_priv = $row['rank'];
                  setcookie('jwt',$jwt_cookie);
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
    echo "<p>Hello <a href='index.php?request=profile/".$request_obj->user_id."' id='profile-log' data-link='profile/".$request_obj->user_id."'>".$request_obj->user_name."</p>";
}
echo $log_err;
?>
<form id="logging-form">
<?php echo $form_field; ?>
  <input type="hidden" id="logging-val" value="<?php echo $logging_value; ?>">
  <input type="submit" id="log-submit" value="<?php echo $submit_val; ?>">
</form>
