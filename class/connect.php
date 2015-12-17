<?php 
$username = "admin";
$password = "Whiteout2";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=csfwg_database', $username, $password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>