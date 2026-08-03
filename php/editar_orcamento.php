<?php
include("conexaoBD.php");

$idorcamento = (int)($_GET['id'] ?? 0);

if ($idorcamento <= 0) {
    header("Location: ../orcamento.php?erro=1");
    exit;
}

// Buscar dados do orçamento
$stmt = $conn->prepare("SELECT * FROM orcamento WHERE idorcamento = ?");
$stmt->bind_param("i", $idorcamento);
$stmt->execute();
$orcamento = $stmt->get_result()->fetch_assoc();

if (!$orcamento) {
    header("Location: ../orcamento.php?erro=1");
    exit;
}

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $sqlUpdate = "UPDATE orcamento SET 
        cliente_idcliente = ?,
        funcionario_idfuncionario = ?,
        defeito = ?,
        observacoes = ?,
        marca = ?,
        modelo = ?,
        imei = ?,
        aprovado = ?,
        valor_total = ?,
        data_dia = ?,
        status = ?
        WHERE idorcamento = ?";

    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param(
        "iissssisdssi",
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
        $status,
        $idorcamento
    );

    if ($stmtUpdate->execute()) {
        header("Location: ../orcamento.php?sucesso=1");
    } else {
        header("Location: ../orcamento.php?erro=1");
    }
    exit;
}

$clientes = $conn->query("SELECT idcliente, nome_clien FROM cliente ORDER BY nome_clien ASC");
$funcionarios = $conn->query("SELECT idfuncionario, nome_func FROM funcionario ORDER BY nome_func ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Orçamento #<?php echo $idorcamento; ?> - CellMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Orçamento #<?php echo $idorcamento; ?></h2>
        <a href="../orcamento.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a>
    </div>

    <div class="card p-4 shadow-sm border-0">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Cliente <span class="text-danger">*</span></label>
                    <select name="cliente_idcliente" class="form-select" required>
                        <?php if ($clientes): ?>
                            <?php while($c = $clientes->fetch_assoc()): ?>
                                <option value="<?php echo $c['idcliente']; ?>" <?php echo ($c['idcliente'] == $orcamento['cliente_idcliente']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c['nome_clien']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Atendente / Técnico <span class="text-danger">*</span></label>
                    <select name="funcionario_idfuncionario" class="form-select" required>
                        <?php if ($funcionarios): ?>
                            <?php while($f = $funcionarios->fetch_assoc()): ?>
                                <option value="<?php echo $f['idfuncionario']; ?>" <?php echo ($f['idfuncionario'] == $orcamento['funcionario_idfuncionario']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($f['nome_func']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Marca</label>
                    <input type="text" name="marca" class="form-control" value="<?php echo htmlspecialchars($orcamento['marca']); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Modelo</label>
                    <input type="text" name="modelo" class="form-control" value="<?php echo htmlspecialchars($orcamento['modelo']); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">IMEI / Nº Série</label>
                    <input type="text" name="imei" class="form-control" value="<?php echo htmlspecialchars($orcamento['imei']); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Defeito Informado <span class="text-danger">*</span></label>
                    <textarea name="defeito" class="form-control" rows="3" required><?php echo htmlspecialchars($orcamento['defeito']); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Observações</label>
                    <textarea name="observacoes" class="form-control" rows="2"><?php echo htmlspecialchars($orcamento['observacoes']); ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Valor do Orçamento (R$)</label>
                    <input type="number" step="0.01" min="0" name="valor_total" class="form-control" value="<?php echo number_format((float)$orcamento['valor_total'], 2, '.', ''); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Data do Orçamento</label>
                    <input type="date" name="data_dia" class="form-control" value="<?php echo htmlspecialchars($orcamento['data_dia']); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Status do Orçamento</label>
                    <select name="status" class="form-select" required>
                        <option value="Aguardando" <?php echo ($orcamento['status'] == 'Aguardando' || empty($orcamento['status'])) ? 'selected' : ''; ?>>Aguardando</option>
                        <option value="Aprovado" <?php echo (strtolower($orcamento['status']) == 'aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                        <option value="Reprovado" <?php echo (strtolower($orcamento['status']) == 'reprovado') ? 'selected' : ''; ?>>Reprovado</option>
                    </select>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                    <a href="../orcamento.php" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning"><i class="fa-solid fa-floppy-disk me-1"></i> Atualizar Orçamento</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>