<?php
include("class/jwt_signature.php");
if(!function_exists('hash_equals')) {
  function hash_equals($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return false;
    } else {
      $res = $str1 ^ $str2;
      $ret = 0;
      for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
      return !$ret;
    }
  }
}

?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Untitled 1</title>
</head>

<body>
<form action="test.php" method="post" >
    <input type="text" name="json_text">
    <input type="submit">
</form>
<?php
if(isset($_POST['json_text'])){
    $sig_gen = new jwt_signature($_POST['json_text']);
    $sig = $sig_gen->signature;
    $json_text = $_POST['json_text'];
    echo "<p>this is the signature : $sig </p>";
    echo "<p>this the json : $json_text </p>";
}
if(isset($_POST['token'])){
    $sig_test = new Jwt_signature($_POST['json_test'] , $_POST['token']);
    $verified = $sig_test->valid;
    if($verified){ $val ="true";}
    else { $val ="false";}
    $json_test = $_POST['json_test'];
    $token = $_POST['token'];
    echo "<p>this is the signature : $token </p>";
    echo "<p>this is the json : $json_test </p>";
    echo "<p>is it valid : $val </p>";
}
?>

<form action="test.php" method="post" >
    jason:<input type="text" name="json_test">
    <input type="text" name="token" >
    <input type="submit">
</form>
</body>

</html>
