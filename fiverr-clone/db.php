<?php
$host = 'sql109.infinityfree.com';       
$db   = 'if0_39233935_Rrevif_db'; 
$user = 'if0_39233935';           
$pass = 'XO6FpXK7DMDXy43';       
$port = 3306;           

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_errno) {
    die('Database connection failed: (' . $conn->connect_errno . ') '
        . $conn->connect_error);
}
?>