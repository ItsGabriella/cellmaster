<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexaoBD.php");
include("funcoes.php"); // Incluído para manter o padrão do estoque.php

$pagina = 'atendimento_os'; // Ativa o item 'Atendimento OS' no menu
$tituloPagina = 'Atendimento de OS';

// ID do funcionário logado
$idFuncionarioSessao = $_SESSION['idfuncionario'] ?? $_SESSION['id'] ?? 1;

// 1. PROCESSAR ATUALIZAÇÃO DE ATENDIMENTO / STATUS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar_os') {
    $idos = intval($_POST['idos']);
    $novoStatus = $conn->real_escape_string($_POST['status_os'] ?? '');
    $laudoTecnico = $conn->real_escape_string($_POST['laudo_tecnico'] ?? '');
    $obsHistorico = $conn->real_escape_string($_POST['obs_historico'] ?? '');

    // Busca status anterior
    $stmtAnt = $conn->prepare("SELECT status_os FROM ordem_servico WHERE idos = ?");
    $stmtAnt->bind_param("i", $idos);
    $stmtAnt->execute();
    $resAnt = $stmtAnt->get_result()->fetch_assoc();
    $statusAnterior = $resAnt['status_os'] ?? 'Aberta';
    $stmtAnt->close();

    // Data de fechamento caso a OS seja finalizada
    $dataFechamentoSql = "";
    if (in_array($novoStatus, ['Concluído', 'Entregue'])) {
        $dataFechamentoSql = ", data_fechamento = CURDATE()";
    }

    // Atualiza Ordem de Serviço
    $sqlUp = "UPDATE ordem_servico 
              SET status_os = '{$novoStatus}', laudo_tecnico = '{$laudoTecnico}' {$dataFechamentoSql} 
              WHERE idos = {$idos}";

    if ($conn->query($sqlUp)) {
        // Grava no Histórico de Edições
        $sqlHist = "INSERT INTO ordem_servico_historico (os_idos, funcionario_idfuncionario, status_anterior, status_novo, observacao)
                    VALUES ({$idos}, {$idFuncionarioSessao}, '{$statusAnterior}', '{$novoStatus}', '{$obsHistorico}')";
        $conn->query($sqlHist);

        header("Location: atendimento_os.php?sucesso=1");
        exit;
    } else {
        header("Location: atendimento_os.php?erro=1");
        exit;
    }
}

// 2. ENDPOINT AJAX PARA BUSCAR HISTÓRICO DA OS
if (isset($_GET['ajax_historico'])) {
    header('Content-Type: application/json');
    $idosHist = intval($_GET['ajax_historico']);

    $sqlH = "SELECT 
                h.status_anterior,
                h.status_novo,
                h.observacao,
                h.data_alteracao,
                f.nome_func
             FROM ordem_servico_historico h
             LEFT JOIN funcionario f ON h.funcionario_idfuncionario = f.idfuncionario
             WHERE h.os_idos = {$idosHist}
             ORDER BY h.idhistorico DESC";

    $resH = $conn->query($sqlH);
    $historico = [];

    if ($resH) {
        while ($row = $resH->fetch_assoc()) {
            $row['data_formatada'] = date('d/m/Y H:i', strtotime($row['data_alteracao']));
            $historico[] = $row;
        }
    }

    echo json_encode($historico);
    exit;
}

// Mensagens de retorno
$msgSucesso = isset($_GET['sucesso']) ? "Atendimento registrado e histórico atualizado!" : "";
$msgErro = isset($_GET['erro']) ? "Erro ao salvar alterações no atendimento." : "";

// Filtro de Busca
$pesquisa = trim($_GET['pesquisa'] ?? '');
$pesquisaEscapada = $conn->real_escape_string($pesquisa);
$whereBase = " WHERE 1=1 ";

if ($pesquisa !== '') {
    $whereBase .= " AND (
        CAST(os.idos AS CHAR) LIKE '%{$pesquisaEscapada}%'
        OR c.nome_clien LIKE '%{$pesquisaEscapada}%'
        OR o.imei LIKE '%{$pesquisaEscapada}%'
        OR o.modelo LIKE '%{$pesquisaEscapada}%'
    )";
}

// Query OSs Em Atendimento
$sqlEmAndamento = "SELECT 
                    os.idos, os.laudo_tecnico, os.valor_final, os.status_os, os.data_abertura,
                    c.nome_clien, c.tel_clien,
                    o.marca, o.modelo, o.imei, o.defeito
                   FROM ordem_servico os
                   LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
                   LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
                   {$whereBase} AND os.status_os IN ('Aberta', 'Em Andamento')
                   ORDER BY os.idos DESC";
$resultEmAndamento = $conn->query($sqlEmAndamento);

// Query OSs Atendidas / Finalizadas
$sqlAtendidas = "SELECT 
                    os.idos, os.laudo_tecnico, os.valor_final, os.status_os, os.data_abertura, os.data_fechamento,
                    c.nome_clien, c.tel_clien,
                    o.marca, o.modelo, o.imei, o.defeito
                 FROM ordem_servico os
                 LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
                 LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
                 {$whereBase} AND os.status_os IN ('Concluído', 'Entregue', 'Cancelada')
                 ORDER BY os.idos DESC";
$resultAtendidas = $conn->query($sqlAtendidas);

// Contadores dos Cards
$totalEmAndamentoCard = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os IN ('Aberta', 'Em Andamento')")->fetch_assoc()['total'] ?? 0;
$totalConcluidosCard  = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os IN ('Concluído', 'Entregue')")->fetch_assoc()['total'] ?? 0;
$totalCanceladosCard  = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os = 'Cancelada'")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloPagina ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-custom">

<div class="d-flex">
    <?php include ('sidebar.php'); ?>

    <main class="flex-grow-1 p-4 bg-light">
        <?php 
            $tituloPagina = "Atendimento de OS";
            $breadcrumb   = "Atendimento OS";
            include 'header.php'; 
        ?>

        <?php if (!empty($msgSucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?= $msgSucesso; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($msgErro)): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <?= $msgErro; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Em Atendimento / Abertas</h6>
                        <h2 class="fw-bold text-primary"><?= $totalEmAndamentoCard ?></h2>
                        <small>Ordens em andamento</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Concluídos / Entregues</h6>
                        <h2 class="fw-bold text-success"><?= $totalConcluidosCard ?></h2>
                        <small>Serviços finalizados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Canceladas</h6>
                        <h2 class="fw-bold text-danger"><?= $totalCanceladosCard ?></h2>
                        <small>Ordens canceladas</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <form method="GET" action="atendimento_os.php" class="w-100">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-9">
                            <label class="form-label fw-semibold">Pesquisar Ordem de Serviço</label>
                            <div class="input-group shadow-sm">
                                <input type="text" name="pesquisa" id="inputPesquisa" class="form-control border-success" placeholder="Pesquisar por ID, Cliente, Aparelho ou IMEI..." value="<?= htmlspecialchars($pesquisa); ?>" autocomplete="off">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end">
                            <?php if ($pesquisa !== ''): ?>
                                <a href="atendimento_os.php" class="btn btn-outline-success w-100 py-2 text-center" title="Limpar busca">
                                    <i class="fa-solid fa-rotate-left me-2"></i> Limpar Filtro
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-3 bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs" id="osTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold text-success" id="andamento-tab" data-bs-toggle="tab" data-bs-target="#andamento-pane" type="button" role="tab">
                            <i class="fa-solid fa-clock-rotate-left me-2"></i>Em Atendimento
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold text-secondary" id="atendidas-tab" data-bs-toggle="tab" data-bs-target="#atendidas-pane" type="button" role="tab">
                            <i class="fa-solid fa-check-double me-2"></i>Já Atendidas / Finalizadas
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="osTabContent">
                <div class="tab-pane fade show active" id="andamento-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID OS</th>
                                    <th>Cliente / Contato</th>
                                    <th>Aparelho / IMEI</th>
                                    <th>Defeito Relatado</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($resultEmAndamento && $resultEmAndamento->num_rows > 0): ?>
                                    <?php while ($os = $resultEmAndamento->fetch_assoc()): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">#<?= $os['idos']; ?></td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= htmlspecialchars($os['nome_clien'] ?? 'Cliente não informado'); ?></div>
                                                <small class="text-muted"><i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($os['tel_clien'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= htmlspecialchars(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? '')); ?></div>
                                                <small class="text-muted">IMEI: <?= htmlspecialchars($os['imei'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 220px;" title="<?= htmlspecialchars($os['defeito'] ?? ''); ?>">
                                                    <?= htmlspecialchars($os['defeito'] ?? 'N/A'); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php $badgeClass = ($os['status_os'] === 'Em Andamento') ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle'; ?>
                                                <span class="badge rounded-pill px-3 py-2 <?= $badgeClass; ?>"><?= htmlspecialchars($os['status_os']); ?></span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <button type="button" class="btn btn-sm btn-success btn-atender-os" 
                                                            data-idos="<?= $os['idos']; ?>" 
                                                            data-laudo="<?= htmlspecialchars($os['laudo_tecnico'] ?? ''); ?>" 
                                                            data-status="<?= htmlspecialchars($os['status_os']); ?>" 
                                                            title="Atender / Editar OS">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-secondary btn-ver-historico" 
                                                            data-idos="<?= $os['idos']; ?>" 
                                                            title="Ver Histórico de Edição">
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                    </button>
                                                    <a href="visualizar_os.php?id=<?= $os['idos']; ?>" class="btn btn-sm btn-primary" title="Ver Detalhes">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fa-solid fa-folder-open fs-2 mb-2 text-black-50 d-block"></i>
                                            Nenhuma Ordem de Serviço em atendimento no momento.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="atendidas-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID OS</th>
                                    <th>Cliente</th>
                                    <th>Aparelho / IMEI</th>
                                    <th>Data Fechamento</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($resultAtendidas && $resultAtendidas->num_rows > 0): ?>
                                    <?php while ($os = $resultAtendidas->fetch_assoc()): 
                                        $dtFechamento = !empty($os['data_fechamento']) ? date('d/m/Y', strtotime($os['data_fechamento'])) : 'N/A';
                                    ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">#<?= $os['idos']; ?></td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= htmlspecialchars($os['nome_clien'] ?? 'Cliente não informado'); ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= htmlspecialchars(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? '')); ?></div>
                                                <small class="text-muted">IMEI: <?= htmlspecialchars($os['imei'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td><?= $dtFechamento; ?></td>
                                            <td>
                                                <?php $badgeClass = ($os['status_os'] === 'Cancelada') ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-success-subtle text-success border border-success-subtle'; ?>
                                                <span class="badge rounded-pill px-3 py-2 <?= $badgeClass; ?>"><?= htmlspecialchars($os['status_os']); ?></span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <button type="button" class="btn btn-sm btn-secondary btn-ver-historico" 
                                                            data-idos="<?= $os['idos']; ?>" 
                                                            title="Ver Histórico de Edição">
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                    </button>
                                                    <a href="visualizar_os.php?id=<?= $os['idos']; ?>" class="btn btn-sm btn-primary" title="Ver OS">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fa-solid fa-folder-open fs-2 mb-2 text-black-50 d-block"></i>
                                            Nenhuma Ordem de Serviço finalizada até o momento.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white py-3">
                <nav>
                    <ul class="pagination justify-content-end mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalAtenderOS" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAtenderLabel"><i class="fa-solid fa-pen-to-square me-2"></i>Atender / Editar OS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="atendimento_os.php">
                <input type="hidden" name="acao" value="atualizar_os">
                <input type="hidden" name="idos" id="atender_idos">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status da OS</label>
                        <select name="status_os" id="atender_status" class="form-select select-verde" required>
                            <option value="Aberta">Aberta</option>
                            <option value="Em Andamento">Em Andamento</option>
                            <option value="Concluído">Concluído</option>
                            <option value="Entregue">Entregue</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Laudo Técnico</label>
                        <textarea name="laudo_tecnico" id="atender_laudo" class="form-control" rows="4" placeholder="Descreva o diagnóstico, serviços efetuados ou testes realizados..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observação para Histórico</label>
                        <input type="text" name="obs_historico" class="form-control" placeholder="Ex: Substituição de peça realizada, aguardando aprovação, etc.">
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHistoricoOS" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tituloModalHistorico"><i class="fa-solid fa-clock-rotate-left me-2"></i>Histórico da OS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="conteudoHistorico">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/home.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal de Edição / Atendimento
    const modalAtender = new bootstrap.Modal(document.getElementById('modalAtenderOS'));
    document.querySelectorAll('.btn-atender-os').forEach(button => {
        button.addEventListener('click', function() {
            const idos = this.getAttribute('data-idos');
            const laudo = this.getAttribute('data-laudo');
            const status = this.getAttribute('data-status');

            document.getElementById('atender_idos').value = idos;
            document.getElementById('atender_laudo').value = laudo;
            document.getElementById('atender_status').value = status;
            document.getElementById('modalAtenderLabel').innerHTML = '<i class="fa-solid fa-pen-to-square me-2"></i>Atender / Editar OS #' + idos;

            modalAtender.show();
        });
    });

    // Modal de Histórico via AJAX
    const modalHistorico = new bootstrap.Modal(document.getElementById('modalHistoricoOS'));
    const conteudoHistorico = document.getElementById('conteudoHistorico');

    document.querySelectorAll('.btn-ver-historico').forEach(button => {
        button.addEventListener('click', function() {
            const idos = this.getAttribute('data-idos');
            document.getElementById('tituloModalHistorico').innerHTML = '<i class="fa-solid fa-clock-rotate-left me-2"></i>Histórico da OS #' + idos;
            conteudoHistorico.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success" role="status"></div></div>';
            
            modalHistorico.show();

            fetch('atendimento_os.php?ajax_historico=' + idos)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        conteudoHistorico.innerHTML = '<p class="text-muted text-center py-4 mb-0">Nenhum histórico registrado para esta OS.</p>';
                        return;
                    }

                    let html = '<div class="timeline p-2">';
                    data.forEach(item => {
                        html += `
                            <div class="timeline-item border-start ps-3 pb-3 mb-3 border-success">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark">
                                        Status: <span class="badge bg-secondary">${item.status_anterior || 'Abertura'}</span>
                                        <i class="fa-solid fa-arrow-right mx-1 text-muted"></i>
                                        <span class="badge bg-success">${item.status_novo}</span>
                                    </span>
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>${item.data_formatada}</small>
                                </div>
                                <div class="text-muted small mb-1">
                                    <strong>Funcionário:</strong> ${item.nome_func || 'Sistema/Desconhecido'}
                                </div>
                                ${item.observacao ? `<div class="bg-light p-2 rounded text-secondary small"><em>"${item.observacao}"</em></div>` : ''}
                            </div>
                        `;
                    });
                    html += '</div>';
                    conteudoHistorico.innerHTML = html;
                })
                .catch(err => {
                    conteudoHistorico.innerHTML = '<p class="text-danger text-center py-4 mb-0">Erro ao carregar o histórico.</p>';
                });
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/home.js"></script>
</body>
</html>