<?php
include("conexao.php");

$orcamento_id = (int) ($_POST['orcamento_idorcamento'] ?? 0);
$cliente_id = (int) ($_POST['cliente_idcliente'] ?? 0);
$funcionario_id = (int) ($_POST['funcionario_idfuncionario'] ?? 0);
$data_abertura = $_POST['data_abertura'] ?? date('Y-m-d');
$status_os = trim($_POST['status_os'] ?? 'Aberta');
$laudo_tecnico = trim($_POST['laudo_tecnico'] ?? '');
$descricao_servico = trim($_POST['descricao_servico'] ?? '');
$observacoes_os = trim($_POST['observacoes_os'] ?? '');
$valor_pecas = (float) ($_POST['valor_pecas'] ?? 0);
$valor_mao_obra = (float) ($_POST['valor_mao_obra'] ?? 0);
$desconto = (float) ($_POST['desconto'] ?? 0);

// Cálculo do valor final
$valor_final = ($valor_pecas + $valor_mao_obra) - $desconto;
if ($valor_final < 0) { $valor_final = 0; }

if ($orcamento_id <= 0 || $cliente_id <= 0 || $funcionario_id <= 0 || $laudo_tecnico === '') {
    header("Location: ordens_servico.php?erro=1");
    exit;
}

// Verificar se já existe OS cadastrada para o mesmo orçamento
$check = $conn->prepare("SELECT idos FROM ordem_servico WHERE orcamento_idorcamento = ?");
$check->bind_param("i", $orcamento_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    $os = $res->fetch_assoc();
    header("Location: visualizar_os.php?id=" . $os['idos']);
    exit;
}
$check->close();

// Número sequencial amigável da OS
$numero_os = 'OS-' . date('Ymd') . '-' . str_pad($orcamento_id, 4, '0', STR_PAD_LEFT);

$sql = "INSERT INTO ordem_servico (
    numero_os,
    orcamento_idorcamento,
    cliente_idcliente,
    funcionario_idfuncionario,
    data_abertura,
    status_os,
    laudo_tecnico,
    descricao_servico,
    observacoes_os,
    valor_pecas,
    valor_mao_obra,
    desconto,
    valor_final
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param(
        "siiisssssdddd",
        $numero_os,
        $orcamento_id,
        $cliente_id,
        $funcionario_id,
        $data_abertura,
        $status_os,
        $laudo_tecnico,
        $descricao_servico,
        $observacoes_os,
        $valor_pecas,
        $valor_mao_obra,
        $desconto,
        $valor_final
    );

    if ($stmt->execute()) {
        header("Location: visualizar_os.php?id=" . $stmt->insert_id . "&sucesso=1");
    } else {
        header("Location: ordens_servico.php?erro=1");
    }
    $stmt->close();
} else {
    header("Location: ordens_servico.php?erro=1");
}
exit;
?>