<?php
include("conexao.php");

if (!isset($_GET['id'])) {
    header("Location: orcamentos.php?erro=1");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM orcamento WHERE idorcamento = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: orcamentos.php?sucesso=1");
} else {
    header("Location: orcamentos.php?erro=1");
}
exit;
?>
