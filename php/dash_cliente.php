<?php
$idCliente = $_SESSION['idcliente'] ?? 0;

// Busca OSs do cliente logado
$sqlMinhasOS = "
    SELECT os.idos, os.status_os, os.laudo_tecnico, os.valor_final, os.data_abertura,
           o.marca, o.modelo, o.defeito
    FROM ordem_servico os
    LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
    WHERE os.cliente_idcliente = {$idCliente}
    ORDER BY os.idos DESC
";

$resMinhasOS = $conn->query($sqlMinhasOS);
?>

<div class="card bg-success-subtle border border-success-subtle p-4 mb-4 rounded-3" role="alert">
    <div class="d-flex align-items-center gap-3 text-success-emphasis">
        <i class="fa-solid fa-mobile-screen-button fs-1 text-success"></i>
        <div>
            <h5 class="fw-bold mb-1">Bem-vindo à sua área do cliente CellMaster!</h5>
            <p class="mb-0 text-muted">Acompanhe abaixo o status dos seus aparelhos em manutenção na nossa assistência técnica.</p>
        </div>
    </div>
</div>

<div class="card bg-white shadow-sm border p-4 rounded-3">
    <h6 class="fw-bold text-dark mb-3">
        <i class="fa-solid fa-list-check me-2 text-success"></i> Meus Aparelhos / Ordens de Serviço
    </h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>CÓDIGO OS</th>
                    <th>APARELHO</th>
                    <th>DEFEITO</th>
                    <th>DATA ENTRADA</th>
                    <th>VALOR</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resMinhasOS && $resMinhasOS->num_rows > 0): ?>
                    <?php while ($os = $resMinhasOS->fetch_assoc()): ?>
                        <?php
                        $status = $os['status_os'] ?? 'Aberta';
                        
                        // Define a cor da badge de acordo com o status
                        $badgeClass = 'bg-primary-subtle text-primary border-primary-subtle';
                        if ($status == 'Em Andamento') {
                            $badgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                        } else if ($status == 'Concluído' || $status == 'Entregue') {
                            $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                        } else if ($status == 'Cancelada') {
                            $badgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                        }
                        ?>
                        <tr>
                            <td class="fw-bold text-dark">#<?= $os['idos'] ?></td>
                            <td class="fw-semibold">
                                <?= htmlspecialchars(trim(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? ''))) ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= htmlspecialchars($os['defeito'] ?? 'N/A') ?></small>
                            </td>
                            <td>
                                <?= !empty($os['data_abertura']) ? date('d/m/Y', strtotime($os['data_abertura'])) : 'N/A' ?>
                            </td>
                            <td class="fw-bold text-success">
                                R$ <?= number_format($os['valor_final'] ?? 0, 2, ',', '.') ?>
                            </td>
                            <td>
                                <span class="badge border <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fa-solid fa-box-open fs-2 mb-2 d-block text-black-50"></i>
                            Você ainda não possui Ordens de Serviço registradas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>