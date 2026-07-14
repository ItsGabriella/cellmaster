<?php
include("conexao.php");

if (!isset($_GET['id'])) {
    header("Location: funcionarios.php?erro=1");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM funcionario WHERE idfuncionario = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: funcionarios.php?sucesso=1");
} else {
    header("Location: funcionarios.php?erro=1");
}
exit;
?>
