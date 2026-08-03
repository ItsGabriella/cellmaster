<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "os_cellmaster";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>