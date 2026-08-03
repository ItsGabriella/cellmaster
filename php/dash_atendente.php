<?php
// Métricas do Atendente
$totalAbertasAtendente = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os = 'Aberta'")->fetch_assoc()['total'] ?? 0;

$totalEmAndamentoAtendente = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os = 'Em Andamento'")->fetch_assoc()['total'] ?? 0;

$totalOrcamentosPendente = $conn->query("SELECT COUNT(*) as total FROM orcamento WHERE status = 'Aguardando'")->fetch_assoc()['total'] ?? 0;

// OSs Abertas aguardando recepção
$sqlAbertas = "
    SELECT os.idos, c.nome_clien, c.tel_clien, o.marca, o.modelo, o.defeito
    FROM ordem_servico os
    LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
    LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
    WHERE os.status_os = 'Aberta'
    ORDER BY os.idos DESC 
    LIMIT 5
";

$resAbertas = $conn->query($sqlAbertas);
?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="php/atendimento_os.php" class="card bg-white shadow-sm border p-4 d-flex flex-row align-items-center gap-3 text-decoration-none rounded-3">
            <div class="bg-success p-3 rounded-4 text-white">
                <i class="fa-solid fa-headset fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Atender OSs</h5>
                <small class="text-muted">Iniciar ou atualizar atendimento</small>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="clientes.php" class="card bg-white shadow-sm border p-4 d-flex flex-row align-items-center gap-3 text-decoration-none rounded-3">
            <div class="bg-primary p-3 rounded-4 text-white">
                <i class="fa-solid fa-user-plus fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Cadastrar Cliente</h5>
                <small class="text-muted">Adicionar novo cliente ao sistema</small>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="orcamento.php" class="card bg-white shadow-sm border p-4 d-flex flex-row align-items-center gap-3 text-decoration-none rounded-3">
            <div class="bg-warning p-3 rounded-4 text-white">
                <i class="fa-solid fa-file-circle-plus fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Novo Orçamento</h5>
                <small class="text-muted">Gerar orçamento de entrada</small>
            </div>
        </a>
    </div>
</div>

<div class="card bg-white shadow-sm border p-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark m-0">
            <i class="fa-solid fa-clock me-2 text-warning"></i> Ordens de Serviço Abertas (Aguardando Atendimento)
        </h6>
        <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold fs-7"><?= $totalAbertasAtendente ?> Abertas</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>OS</th>
                    <th>CLIENTE</th>
                    <th>CONTATO</th>
                    <th>APARELHO</th>
                    <th>DEFEITO</th>
                    <th class="text-center">AÇÃO</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resAbertas && $resAbertas->num_rows > 0): ?>
                    <?php while ($os = $resAbertas->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold text-dark">#<?= $os['idos'] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($os['nome_clien'] ?? 'N/A') ?></td>
                            <td>
                                <i class="fa-solid fa-phone me-1 text-muted"></i><?= htmlspecialchars($os['tel_clien'] ?? 'N/A') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(trim(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? ''))) ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= htmlspecialchars($os['defeito'] ?? 'Não informado') ?></small>
                            </td>
                            <td class="text-center">
                                <a href="php/atendimento_os.php" class="btn btn-sm btn-success px-3 rounded-3 fw-semibold">
                                    <i class="fa-solid fa-headset me-1"></i> Atender
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Nenhuma Ordem de Serviço pendente no momento.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>