<?php
include("conexaoBD.php");

// Consultas do Administrador / Gerente
$totalFaturamento = $conn->query("SELECT SUM(valor_final) as total FROM ordem_servico WHERE status_os IN ('Concluído', 'Entregue')")->fetch_assoc()['total'] ?? 0.00;
$totalOS = $conn->query("SELECT COUNT(*) as total FROM ordem_servico")->fetch_assoc()['total'] ?? 0;
$totalClientes = $conn->query("SELECT COUNT(*) as total FROM cliente")->fetch_assoc()['total'] ?? 0;
$pecasBaixoEstoque = $conn->query("SELECT COUNT(*) as total FROM peca WHERE qtdade_peca <= estoque_min")->fetch_assoc()['total'] ?? 0;

// Consulta Única para Status dos Orçamentos (Grafico)
$sqlOrcamentos = "
    SELECT 
        SUM(CASE WHEN LOWER(status) LIKE 'aprovad%' THEN 1 ELSE 0 END) as aprovados,
        SUM(CASE WHEN LOWER(status) LIKE 'reprovad%' OR LOWER(status) LIKE 'cancelad%' THEN 1 ELSE 0 END) as reprovados,
        SUM(CASE WHEN LOWER(status) LIKE 'aguard%' OR LOWER(status) LIKE 'pendent%' THEN 1 ELSE 0 END) as aguardando
    FROM orcamento
";
$resOrc = $conn->query($sqlOrcamentos);
if ($resOrc && $rowOrc = $resOrc->fetch_assoc()) {
    $orcAprovados  = (int)($rowOrc['aprovados'] ?? 0);
    $orcReprovados = (int)($rowOrc['reprovados'] ?? 0);
    $orcAguardando = (int)($rowOrc['aguardando'] ?? 0);
} else {
    $orcAprovados  = 0;
    $orcReprovados = 0;
    $orcAguardando = 0;
}

// Últimas OS Cadastradas
$sqlUltimasOS = "
    SELECT os.idos, os.valor_final, os.status_os, c.nome_clien, o.modelo
    FROM ordem_servico os
    LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
    LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
    ORDER BY os.idos DESC
    LIMIT 5
";
$resUltimas = $conn->query($sqlUltimasOS);
?>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-white shadow-sm border p-3 d-flex flex-row justify-content-between align-items-center rounded-3">
            <div>
                <small class="text-muted fw-semibold">Faturamento Total</small>
                <h4 class="fw-bold text-success m-0 mt-1">R$ <?= number_format($totalFaturamento, 2, ',', '.') ?></h4>
            </div>
            <div class="bg-success-subtle p-3 rounded-4 text-success">
                <i class="fa-solid fa-dollar-sign fs-4"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white shadow-sm border p-3 d-flex flex-row justify-content-between align-items-center rounded-3">
            <div>
                <small class="text-muted fw-semibold">Total de OSs</small>
                <h4 class="fw-bold text-dark m-0 mt-1"><?= $totalOS ?></h4>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4 text-primary">
                <i class="fa-solid fa-file-lines fs-4"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white shadow-sm border p-3 d-flex flex-row justify-content-between align-items-center rounded-3">
            <div>
                <small class="text-muted fw-semibold">Clientes Cadastrados</small>
                <h4 class="fw-bold text-dark m-0 mt-1"><?= $totalClientes ?></h4>
            </div>
            <div class="bg-info-subtle p-3 rounded-4 text-info">
                <i class="fa-solid fa-users fs-4"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white shadow-sm border p-3 d-flex flex-row justify-content-between align-items-center rounded-3">
            <div>
                <small class="text-muted fw-semibold">Baixo Estoque</small>
                <h4 class="fw-bold text-danger m-0 mt-1"><?= $pecasBaixoEstoque ?></h4>
            </div>
            <div class="bg-danger-subtle p-3 rounded-4 text-danger">
                <i class="fa-solid fa-triangle-exclamation fs-4"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card bg-white shadow-sm border p-4 h-100 rounded-3">
            <h6 class="fw-bold text-dark mb-3">
                <i class="fa-solid fa-chart-pie me-2 text-success"></i> Status dos Orçamentos
            </h6>
            <div style="position: relative; height: 260px;">
                <canvas id="graficoOrcamentos"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card bg-white shadow-sm border p-4 h-100 rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-clock-rotate-left me-2 text-success"></i> Últimas Ordens de Serviço
                </h6>
                <a href="ordens_servico.php" class="btn btn-sm btn-light border fw-semibold">Ver Todas</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>OS</th>
                            <th>CLIENTE</th>
                            <th>APARELHO</th>
                            <th>VALOR</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resUltimas && $resUltimas->num_rows > 0): ?>
                            <?php while ($os = $resUltimas->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?= $os['idos'] ?></td>
                                    <td><?= htmlspecialchars($os['nome_clien'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($os['modelo'] ?? 'N/A') ?></td>
                                    <td class="fw-bold text-success">
                                        R$ <?= number_format($os['valor_final'] ?? 0, 2, ',', '.') ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= htmlspecialchars($os['status_os'] ?? 'Aberta') ?></span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Nenhuma OS recente.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoOrcamentos');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Aprovados', 'Reprovados', 'Aguardando'],
                datasets: [{
                    data: [<?= $orcAprovados ?>, <?= $orcReprovados ?>, <?= $orcAguardando ?>],
                    backgroundColor: ['#198754', '#dc3545', '#ffc107'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>