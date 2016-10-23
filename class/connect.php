<?php 
try {
    $pdo = new PDO('mysql:host=localhost;dbname=csfwgDB', $request_obj->db_username, $request_obj->db_password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>