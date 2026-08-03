<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexaoBD.php");

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
    <title><?= $tituloPagina ?> - CELLMASTER</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --sidebar-width: 260px;
            --bg-body: #f4f6f9;
            --green-primary: #1b4d22;
            --green-hover: #143b1a;
        }
        body {
            background-color: var(--bg-body);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #333;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        #sidebar {
            width: var(--sidebar-width);
            background-color: var(--green-primary);
            min-height: 100vh;
            flex-shrink: 0;
        }
        #sidebar .side-item {
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        #sidebar .side-item.active {
            background-color: rgba(255, 255, 255, 0.18);
            font-weight: 600;
        }
        #sidebar .side-item:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.08);
        }
        .main-content {
            flex-grow: 1;
            padding: 2rem;
            max-width: calc(100% - var(--sidebar-width));
            width: 100%;
        }
        .card-custom {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
        }
        .btn-green-main {
            background-color: var(--green-primary);
            color: #fff;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-green-main:hover {
            background-color: var(--green-hover);
            color: #fff;
        }
        .badge-status {
            padding: 6px 14px;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }
        .badge-aberta { background-color: #fff8e1; color: #b78103; border: 1px solid #ffe082; }
        .badge-andamento { background-color: #e3f2fd; color: #0277bd; border: 1px solid #90caf9; }
        .badge-concluido { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .badge-cancelada { background-color: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        
        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 12px 24px;
            border-bottom: 3px solid transparent;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .nav-tabs-custom .nav-link.active {
            color: var(--green-primary);
            border-bottom-color: var(--green-primary);
            background: transparent;
        }
        .table thead th {
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
            border-bottom: 2px solid #edf2f7;
            padding: 12px 16px;
        }
        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }
        .btn-action:hover { opacity: 0.85; }
        .btn-action-edit { background-color: #1b4d22; color: #ffffff !important; }
        .btn-action-history { background-color: #6c757d; color: #ffffff !important; }
        .btn-action-view { background-color: #0d6efd; color: #ffffff !important; }
        
        .timeline-item {
            position: relative;
            padding-left: 28px;
            margin-bottom: 20px;
            border-left: 2px solid #e9ecef;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--green-primary);
        }
        @media (max-width: 991.98px) {
            .wrapper { flex-direction: column; }
            #sidebar { width: 100%; min-height: auto; }
            .main-content { max-width: 100%; padding: 1rem; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <?php include("sidebar.php"); ?>

    <main class="main-content">
        <header class="card-custom mb-4 p-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><?= $tituloPagina ?></h4>
                <nav style="--bs-breadcrumb-divider: '>';" class="small">
                    <ol class="breadcrumb mb-0 text-muted">
                        <li class="breadcrumb-item"><a href="home.php" class="text-success text-decoration-none fw-medium">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Atendimento OS</li>
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <?php
                $nomeUsuario = $_SESSION['nome'] ?? 'Atendente';
                $cargoUsuario = $_SESSION['cargo'] ?? 'Atendimento';
                $fotoBD = $_SESSION['foto'] ?? '';
                $temFoto = !empty($fotoBD) && file_exists("img/perfil/" . $fotoBD);
                
                $partesNome = explode(' ', trim($nomeUsuario));
                $iniciais = strtoupper(substr($partesNome[0], 0, 1));
                if (count($partesNome) > 1) {
                    $iniciais .= strtoupper(substr(end($partesNome), 0, 1));
                }
                ?>
                <?php if ($temFoto): ?>
                    <img src="img/perfil/<?= $fotoBD; ?>?v=<?= time(); ?>" alt="Perfil" class="rounded-circle object-fit-cover border border-2 border-white shadow-sm" width="42" height="42">
                <?php else: ?>
                    <div class="rounded-circle bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                        <?= $iniciais ?>
                    </div>
                <?php endif; ?>
                <div class="d-flex flex-column justify-content-center lh-sm">
                    <span class="fw-bold text-dark fs-6 mb-0"><?= htmlspecialchars($nomeUsuario); ?></span>
                    <span class="text-muted small"><?= htmlspecialchars($cargoUsuario); ?></span>
                </div>
            </div>
        </header>

        <?php if (!empty($msgSucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show card-custom border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                <?= $msgSucesso; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($msgErro)): ?>
            <div class="alert alert-danger alert-dismissible fade show card-custom border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2 fs-5"></i>
                <?= $msgErro; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-custom card-stat p-3.5 d-flex justify-content-between align-items-center p-3">
                    <div>
                        <small class="text-muted fw-semibold">Em Atendimento / Abertas</small>
                        <h3 class="fw-bold text-primary m-0 mt-1"><?= $totalEmAndamentoCard ?></h3>
                    </div>
                    <div class="bg-primary-subtle p-3 rounded-4 text-primary">
                        <i class="fa-solid fa-headset fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom card-stat p-3.5 d-flex justify-content-between align-items-center p-3">
                    <div>
                        <small class="text-muted fw-semibold">Concluídos / Entregues</small>
                        <h3 class="fw-bold text-success m-0 mt-1"><?= $totalConcluidosCard ?></h3>
                    </div>
                    <div class="bg-success-subtle p-3 rounded-4 text-success">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom card-stat p-3.5 d-flex justify-content-between align-items-center p-3">
                    <div>
                        <small class="text-muted fw-semibold">Canceladas</small>
                        <h3 class="fw-bold text-danger m-0 mt-1"><?= $totalCanceladosCard ?></h3>
                    </div>
                    <div class="bg-danger-subtle p-3 rounded-4 text-danger">
                        <i class="fa-solid fa-ban fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-custom p-3 mb-4">
            <form method="GET" action="atendimento_os.php" class="w-100">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 text-muted ps-3">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="pesquisa" id="inputPesquisa" class="form-control bg-light border-0 py-2.5" placeholder="Pesquisar por ID, Cliente, Aparelho ou IMEI..." value="<?= htmlspecialchars($pesquisa); ?>" autocomplete="off">
                    <button type="submit" class="btn btn-green-main px-4 fw-semibold rounded-end-3">Buscar</button>
                    <?php if ($pesquisa !== ''): ?>
                        <a href="atendimento_os.php" class="btn btn-light border-0 text-muted d-flex align-items-center ms-2 rounded-3" title="Limpar busca">
                            <i class="fa-solid fa-xmark me-1"></i> Limpar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card-custom p-3 overflow-hidden">
            <ul class="nav nav-tabs nav-tabs-custom mb-3" id="osTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="andamento-tab" data-bs-toggle="tab" data-bs-target="#andamento-pane" type="button" role="tab">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i>Em Atendimento
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="atendidas-tab" data-bs-toggle="tab" data-bs-target="#atendidas-pane" type="button" role="tab">
                        <i class="fa-solid fa-check-double me-2"></i>Já Atendidas / Finalizadas
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="osTabContent">
                <div class="tab-pane fade show active" id="andamento-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
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
                                        <tr class="linha-os">
                                            <td class="ps-4 fw-bold text-dark campo-id">#<?= $os['idos']; ?></td>
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
                                                <?php
                                                $badgeClass = ($os['status_os'] === 'Em Andamento') ? 'badge-andamento' : 'badge-aberta';
                                                ?>
                                                <span class="badge badge-status <?= $badgeClass; ?>"><?= htmlspecialchars($os['status_os']); ?></span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <button type="button" class="btn btn-action btn-action-edit btn-atender-os" 
                                                            data-idos="<?= $os['idos']; ?>" 
                                                            data-laudo="<?= htmlspecialchars($os['laudo_tecnico'] ?? ''); ?>" 
                                                            data-status="<?= htmlspecialchars($os['status_os']); ?>" 
                                                            title="Atender / Editar OS">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-action btn-action-history btn-ver-historico" 
                                                            data-idos="<?= $os['idos']; ?>" 
                                                            title="Ver Histórico de Edição">
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                    </button>
                                                    <a href="visualizar_os.php?id=<?= $os['idos']; ?>" class="btn btn-action btn-action-view" title="Ver Detalhes">
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
                        <table class="table align-middle">
                            <thead>
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
                                        <tr class="linha-os">
                                            <td class="ps-4 fw-bold text-dark campo-id">#<?= $os['idos']; ?></td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= htmlspecialchars($os['nome_clien'] ?? 'Cliente não informado'); ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= htmlspecialchars(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? '')); ?></div>
                                                <small class="text-muted">IMEI: <?= htmlspecialchars($os['imei'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td><?= $dtFechamento; ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = 'badge-concluido';
                                                if ($os['status_os'] === 'Cancelada') {
                                                    $badgeClass = 'badge-cancelada';
                                                }
                                                ?>
                                                <span class="badge badge-status <?= $badgeClass; ?>"><?= htmlspecialchars($os['status_os']); ?></span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <button type="button" class="btn btn-action btn-action-history btn-ver-historico" 
                                                            data-idos="<?= $os['idos']; ?>" 
                                                            title="Ver Histórico de Edição">
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                    </button>
                                                    <a href="visualizar_os.php?id=<?= $os['idos']; ?>" class="btn btn-action btn-action-view" title="Ver OS">
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
        </div>
    </main>
</div>

<div class="modal fade" id="modalAtenderOS" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalAtenderLabel">Atender / Editar OS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="atendimento_os.php">
                <input type="hidden" name="acao" value="atualizar_os">
                <input type="hidden" name="idos" id="atender_idos">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Status da OS</label>
                        <select name="status_os" id="atender_status" class="form-select rounded-3" required>
                            <option value="Aberta">Aberta</option>
                            <option value="Em Andamento">Em Andamento</option>
                            <option value="Concluído">Concluído</option>
                            <option value="Entregue">Entregue</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Laudo Técnico</label>
                        <textarea name="laudo_tecnico" id="atender_laudo" class="form-control rounded-3" rows="4" placeholder="Descreva o diagnóstico, serviços efetuados ou testes realizados..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Observação para Histórico</label>
                        <input type="text" name="obs_historico" class="form-control rounded-3" placeholder="Ex: Substituição de peça realizada, aguardando aprovação, etc.">
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-green-main rounded-3 px-4">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHistoricoOS" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-secondary-subtle p-3 rounded-3 text-secondary d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark m-0" id="tituloModalHistorico">Histórico da OS</h5>
                        <small class="text-muted">Registro completo de edições e mudanças de status</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="conteudoHistorico">
                </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
            document.getElementById('modalAtenderLabel').innerText = 'Atender / Editar OS #' + idos;

            modalAtender.show();
        });
    });

    // Modal de Histórico via AJAX
    const modalHistorico = new bootstrap.Modal(document.getElementById('modalHistoricoOS'));
    const conteudoHistorico = document.getElementById('conteudoHistorico');

    document.querySelectorAll('.btn-ver-historico').forEach(button => {
        button.addEventListener('click', function() {
            const idos = this.getAttribute('data-idos');
            document.getElementById('tituloModalHistorico').innerText = 'Histórico da OS #' + idos;
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
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark fs-6">
                                        Status: <span class="badge bg-secondary-subtle text-dark">${item.status_anterior || 'Abertura'}</span>
                                        <i class="fa-solid fa-arrow-right mx-1 text-muted fs-7"></i>
                                        <span class="badge bg-success-subtle text-success">${item.status_novo}</span>
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
</body>
</html>