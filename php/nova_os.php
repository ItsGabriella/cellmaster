<?php
include("conexaoBD.php");

$orcamento_id = (int)($_GET['orcamento_id'] ?? 0);

if ($orcamento_id <= 0) {
    header("Location: ../orcamento.php?erro=1");
    exit;
}

// Buscar dados do orçamento
$sql = "
    SELECT 
        o.*,
        c.nome_clien,
        c.tel_clien,
        f.nome_func
    FROM orcamento o
    LEFT JOIN cliente c ON o.cliente_idcliente = c.idcliente
    LEFT JOIN funcionario f ON o.funcionario_idfuncionario = f.idfuncionario
    WHERE o.idorcamento = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orcamento_id);
$stmt->execute();
$orcamento = $stmt->get_result()->fetch_assoc();

if (!$orcamento) {
    header("Location: ../orcamentos.php?erro=1");
    exit;
}

// Se já existir OS gerada, redireciona para a visualização
$check = $conn->prepare("SELECT idos FROM ordem_servico WHERE orcamento_idorcamento = ?");
$check->bind_param("i", $orcamento_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    $os = $res->fetch_assoc();
    header("Location: visualizar_os.php?id=" . $os['idos']);
    exit;
}

$funcionarios = $conn->query("SELECT idfuncionario, nome_func FROM funcionario ORDER BY nome_func ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Nova OS - CellMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Abrir Ordem de Serviço (A partir do Orçamento #<?php echo $orcamento_id; ?>)</h2>
        <a href="../orcamento.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a>
    </div>

    <!-- Resumo do Orçamento -->
    <div class="card p-3 mb-4 border-start border-4 border-primary shadow-sm">
        <h6 class="fw-bold text-primary mb-2">Dados do Orçamento Base</h6>
        <div class="row">
            <div class="col-md-4"><strong>Cliente:</strong> <?php echo htmlspecialchars($orcamento['nome_clien'] ?? '-'); ?></div>
            <div class="col-md-4"><strong>Aparelho:</strong> <?php echo htmlspecialchars($orcamento['marca'] . ' ' . $orcamento['modelo']); ?></div>
            <div class="col-md-4"><strong>IMEI:</strong> <code><?php echo htmlspecialchars($orcamento['imei']); ?></code></div>
            <div class="col-12 mt-2"><strong>Defeito Informado:</strong> <?php echo htmlspecialchars($orcamento['defeito']); ?></div>
        </div>
    </div>

    <div class="card p-4 shadow-sm border-0">
        <form action="salvar_os.php" method="POST">
            <input type="hidden" name="orcamento_idorcamento" value="<?php echo $orcamento_id; ?>">
            <input type="hidden" name="cliente_idcliente" value="<?php echo $orcamento['cliente_idcliente']; ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Técnico Responsável <span class="text-danger">*</span></label>
                    <select name="funcionario_idfuncionario" class="form-select" required>
                        <option value="">Selecione o Técnico...</option>
                        <?php if ($funcionarios): ?>
                            <?php while($f = $funcionarios->fetch_assoc()): ?>
                                <option value="<?php echo $f['idfuncionario']; ?>" <?php echo ($f['idfuncionario'] == $orcamento['funcionario_idfuncionario']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($f['nome_func']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Data de Abertura</label>
                    <input type="date" name="data_abertura" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Status Inicial</label>
                    <select name="status_os" class="form-select" required>
                        <option value="Em Andamento" selected>Em Andamento</option>
                        <option value="Aguardando Peça">Aguardando Peça</option>
                        <option value="Finalizada">Finalizada</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Laudo Técnico <span class="text-danger">*</span></label>
                    <textarea name="laudo_tecnico" class="form-control" rows="3" required placeholder="Diagnóstico técnico detalhado dos componentes..."></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Descrição do Serviço / Peças Utilizadas</label>
                    <textarea name="descricao_servico" class="form-control" rows="2" placeholder="Ex: Troca de tela frontal + conector..."></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Observações de Entrega / Garantia</label>
                    <textarea name="observacoes_os" class="form-control" rows="2" placeholder="Garantia de 90 dias..."></textarea>
                </div>

                <!-- Valores com Cálculo Automático -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Valor de Peças (R$)</label>
                    <input type="number" step="0.01" min="0" id="valor_pecas" name="valor_pecas" class="form-control" value="0.00" oninput="calcularTotal()">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Mão de Obra (R$)</label>
                    <input type="number" step="0.01" min="0" id="valor_mao_obra" name="valor_mao_obra" class="form-control" value="<?php echo number_format((float)$orcamento['valor_total'], 2, '.', ''); ?>" oninput="calcularTotal()">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Desconto (R$)</label>
                    <input type="number" step="0.01" min="0" id="desconto" name="desconto" class="form-control" value="0.00" oninput="calcularTotal()">
                </div>

                <div class="col-12 bg-light p-3 rounded d-flex justify-content-between align-items-center mt-3">
                    <span class="fs-5 fw-bold text-secondary">Valor Total da OS:</span>
                    <span class="fs-4 fw-bold text-success" id="display_total">R$ <?php echo number_format((float)$orcamento['valor_total'], 2, ',', '.'); ?></span>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                    <a href="../orcamento.php" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Confirmar e Gerar OS</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function calcularTotal() {
    let pecas = parseFloat(document.getElementById('valor_pecas').value) || 0;
    let maoObra = parseFloat(document.getElementById('valor_mao_obra').value) || 0;
    let desconto = parseFloat(document.getElementById('desconto').value) || 0;

    let total = (pecas + maoObra) - desconto;
    if (total < 0) total = 0;

    document.getElementById('display_total').innerText = 'R$ ' + total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>