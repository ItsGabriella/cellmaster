<?php
include("conexao.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orcamentos.php?erro=1");
    exit;
}

$idorcamento = (int) $_GET['id'];

$sql = "
    SELECT 
        idorcamento,
        cliente_idcliente,
        funcionario_idfuncionario,
        defeito,
        observacoes,
        valor_total,
        data_dia,
        status
    FROM orcamento
    WHERE idorcamento = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idorcamento);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: orcamentos.php?erro=1");
    exit;
}

$orcamento = $resultado->fetch_assoc();

$verifica = $conn->prepare("SELECT idos FROM ordem_servico WHERE orcamento_idorcamento = ?");
$verifica->bind_param("i", $idorcamento);
$verifica->execute();
$resVerifica = $verifica->get_result();

if ($resVerifica->num_rows > 0) {
    $os = $resVerifica->fetch_assoc();
    header("Location: visualizar_os.php?id=" . $os['idos']);
    exit;
}

$laudo = $orcamento['defeito'];
$observacoes_os = $orcamento['observacoes'];
$valor_final = $orcamento['valor_total'];
$data_abertura = !empty($orcamento['data_dia']) ? $orcamento['data_dia'] : date('Y-m-d');
$status_os = "Aberta";

$insert = $conn->prepare("
    INSERT INTO ordem_servico 
    (orcamento_idorcamento, cliente_idcliente, funcionario_idfuncionario, data_abertura, status_os, laudo_tecnico, observacoes_os, valor_final)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$insert->bind_param(
    "iiissssd",
    $idorcamento,
    $orcamento['cliente_idcliente'],
    $orcamento['funcionario_idfuncionario'],
    $data_abertura,
    $status_os,
    $laudo,
    $observacoes_os,
    $valor_final
);

if ($insert->execute()) {
    header("Location: visualizar_os.php?id=" . $insert->insert_id . "&sucesso=1");
} else {
    header("Location: orcamentos.php?erro=1");
}
exit;
?>
