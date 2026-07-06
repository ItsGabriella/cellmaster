<?php
include("conexao.php");

$cliente_idcliente = $_POST['cliente_idcliente'] ?? null;
$funcionario_idfuncionario = $_POST['funcionario_idfuncionario'] ?? null;
$defeito = $_POST['defeito'] ?? null;
$observacoes = $_POST['observacoes'] ?? null;
$marca = $_POST['marca'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$imei = $_POST['imei'] ?? '';
$status = $_POST['status'] ?? 'Aguardando';
$valor_total = $_POST['valor_total'] ?? 0;
$data_dia = $_POST['data_dia'] ?? null;

$aprovado = null;
if ($status == "Aprovado") {
    $aprovado = "Sim";
} elseif ($status == "Reprovado") {
    $aprovado = "Nao";
}

$sql = "INSERT INTO orcamento 
(cliente_idcliente, funcionario_idfuncionario, defeito, observacoes, marca, modelo, imei, aprovado, valor_total, data_dia, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "iissssisdss",
    $cliente_idcliente,
    $funcionario_idfuncionario,
    $defeito,
    $observacoes,
    $marca,
    $modelo,
    $imei,
    $aprovado,
    $valor_total,
    $data_dia,
    $status
);

if ($stmt->execute()) {
    header("Location: orcamentos.php?sucesso=1");
} else {
    header("Location: orcamentos.php?erro=1");
}
exit;
?>
