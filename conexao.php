<?php
$host = "127.0.0.1";
$usuario = "root";
$senha = "";
$banco = "os_cellmaster";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexao: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
