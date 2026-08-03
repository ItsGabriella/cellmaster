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
$valor_total = (float)($_POST['valor_total'] ?? 0);
$data_dia = $_POST['data_dia'] ?? date('Y-m-d');

$aprovado = null;
if (strtolower($status) == "aprovado") {
    $aprovado = "Sim";
} elseif (strtolower($status) == "reprovado") {
    $aprovado = "Nao";
}

$sql = "INSERT INTO orcamento 
(cliente_idcliente, funcionario_idfuncionario, defeito, observacoes, marca, modelo, imei, aprovado, valor_total, data_dia, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
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
    $stmt->close();
} else {
    header("Location: orcamentos.php?erro=1");
}
exit;
?>