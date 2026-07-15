<?php
include("conexao.php");

if (!isset($_GET['id'])) {
    header("Location: clientes.php?erro=1");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM cliente WHERE idcliente = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: clientes.php?sucesso=1");
} else {
    header("Location: clientes.php?erro=1");
}
exit;
?>
