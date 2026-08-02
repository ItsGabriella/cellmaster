<?php
session_start();
include("conexaoBD.php");

// Atualiza todas as notificações que ainda não foram lidas
$sql = "UPDATE notificacoes SET lida = 1 WHERE lida = 0";
mysqli_query($conn, $sql);

mysqli_close($conn);

// Retorna uma resposta simples em JSON para o JavaScript
header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit();
?>