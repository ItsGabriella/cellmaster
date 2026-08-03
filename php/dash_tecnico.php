<?php
$idTecnico = $_SESSION['idfuncionario'] ?? 0;

// Métricas Técnicas
$sqlEmManutencao = "SELECT COUNT(*) as total FROM ordem_servico WHERE status_os = 'Em Andamento'";
$totalEmManutencao = $conn->query($sqlEmManutencao)->fetch_assoc()['total'] ?? 0;

$sqlSemLaudo = "SELECT COUNT(*) as total FROM ordem_servico WHERE (laudo_tecnico IS NULL OR laudo_tecnico = '') AND status_os = 'Em Andamento'";
$totalSemLaudo = $conn->query($sqlSemLaudo)->fetch_assoc()['total'] ?? 0;

// OSs em andamento na bancada
$sqlManutencaoLista = "
    SELECT os.idos, os.laudo_tecnico, c.nome_clien, o.marca, o.modelo, o.defeito
    FROM ordem_servico os
    LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
    LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
    WHERE os.status_os = 'Em Andamento'
    ORDER BY os.idos ASC 
    LIMIT 6
";

$resManutencao = $conn->query($sqlManutencaoLista);
?>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card bg-white shadow-sm border p-3 d-flex flex-row justify-content-between align-items-center rounded-3">
            <div>
                <small class="text-muted fw-semibold">Aparelhos em Manutenção</small>
                <h3 class="fw-bold text-primary m-0 mt-1"><?= $totalEmManutencao ?></h3>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4 text-primary">
                <i class="fa-solid fa-screwdriver-wrench fs-4"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card bg-white shadow-sm border p-3 d-flex flex-row justify-content-between align-items-center rounded-3">
            <div>
                <small class="text-muted fw-semibold">Aguardando Laudo Técnico</small>
                <h3 class="fw-bold text-warning m-0 mt-1"><?= $totalSemLaudo ?></h3>
            </div>
            <div class="bg-warning-subtle p-3 rounded-4 text-warning">
                <i class="fa-solid fa-file-pen fs-4"></i>
            </div>
        </div>
    </div>
</div>

<div class="card bg-white shadow-sm border p-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark m-0">
            <i class="fa-solid fa-microchip me-2 text-primary"></i> Bancada de Trabalho - Ordens em Andamento
        </h6>
        <a href="estoque.php" class="btn btn-sm btn-light border fw-semibold">
            <i class="fa-solid fa-box-archive me-1"></i> Consultar Peças
        </a>
    </div>

    <div class="row g-3">
        <?php if ($resManutencao && $resManutencao->num_rows > 0): ?>
            <?php while ($item = $resManutencao->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="border rounded-4 p-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark">OS #<?= $item['idos'] ?></span>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Em Manutenção</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">
                                <?= htmlspecialchars(trim(($item['marca'] ?? '') . ' ' . ($item['modelo'] ?? ''))) ?>
                            </h6>
                            <p class="small text-muted mb-2">
                                <strong>Cliente:</strong> <?= htmlspecialchars($item['nome_clien'] ?? 'N/A') ?>
                            </p>
                            <div class="bg-white p-2 rounded-3 border small mb-2">
                                <strong>Problema Relatado:</strong> <?= htmlspecialchars($item['defeito'] ?? 'Não informado') ?>
                            </div>
                        </div>

                        <div class="pt-2 border-top mt-2">
                            <a href="ordens_servico.php" class="btn btn-sm btn-success w-100 rounded-3 fw-semibold">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Preencher Laudo / Finalizar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted py-5">
                <i class="fa-solid fa-circle-check fs-2 mb-2 text-success d-block"></i>
                Nenhum aparelho em manutenção no momento!
            </div>
        <?php endif; ?>
    </div>
</div>