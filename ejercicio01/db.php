<?php
$host = "sql102.infinityfree.com";
$user = "if0_43281779";
$pass = "kdjahaaZ5CbMq";
$dbname = "if0_43281779_tarea4";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>