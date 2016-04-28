<?php 
$username = "root";
$password = "Must/Dev";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=csfwgDB', $username, $password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>