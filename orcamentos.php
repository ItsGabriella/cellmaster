<?php
// Inicia a sessão para capturar as informações de usuário no header caso existam
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexao.php");

// 1. Definições para o Sidebar e Header
$pagina = 'orcamentos';
$tituloPagina = 'Orçamentos';
$breadcrumb   = 'Orçamentos';

$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : "";

// 2. Buscar clientes e funcionários para o formulário do modal
$clientes = $conn->query("SELECT idcliente, nome_clien FROM cliente ORDER BY nome_clien ASC");
$funcionarios = $conn->query("SELECT idfuncionario, nome_func FROM funcionario ORDER BY nome_func ASC");

// 3. Consultas estatísticas para os cards
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM orcamento");
$totalCadastrados = $totalQuery->fetch_assoc()['total'] ?? 0;

$mesAtualQuery = $conn->query("SELECT COUNT(*) as total FROM orcamento WHERE MONTH(data_dia) = MONTH(CURRENT_DATE()) AND YEAR(data_dia) = YEAR(CURRENT_DATE())");
$totalMes = $mesAtualQuery->fetch_assoc()['total'] ?? 0;

$pendentesQuery = $conn->query("SELECT COUNT(*) as total FROM orcamento WHERE status = 'Aguardando' OR status IS NULL OR status = ''");
$totalPendentes = $pendentesQuery->fetch_assoc()['total'] ?? 0;

$aprovadosQuery = $conn->query("SELECT COUNT(*) as total FROM orcamento WHERE status = 'Aprovado'");
$totalAprovados = $aprovadosQuery->fetch_assoc()['total'] ?? 0;

// 4. Consulta principal dos orçamentos
$sql = "
    SELECT 
        o.idorcamento,
        o.cliente_idcliente,
        o.imei,
        o.valor_total,
        o.data_dia,
        o.status,
        c.nome_clien,
        os.idos
    FROM orcamento o
    LEFT JOIN cliente c ON o.cliente_idcliente = c.idcliente
    LEFT JOIN ordem_servico os ON os.orcamento_idorcamento = o.idorcamento
";

if ($pesquisa != "") {
    $sql .= " WHERE c.nome_clien LIKE ? OR o.imei LIKE ? ORDER BY o.idorcamento DESC";
    $stmt = $conn->prepare($sql);
    $like = "%{$pesquisa}%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $sql .= " ORDER BY o.idorcamento DESC";
    $resultado = $conn->query($sql);
}

function classeStatus($status) {
    $status = strtolower(trim($status));
    if ($status == "aprovado") return "badge bg-success";
    if ($status == "reprovado") return "badge bg-danger";
    if (in_array($status, ["em andamento", "em_andamento"])) return "badge bg-primary";
    return "badge bg-warning text-dark";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamentos - CellMaster</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --sidebar-width: 260px;
            --bg-body: #f8f9fa;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ESTILOS DO SIDEBAR ENVIADO */
        #sidebar {
            width: var(--sidebar-width);
            background-color: #1b4d22;
            min-height: 100vh;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        #sidebar .side-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        #sidebar .side-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
        }

        #logout_btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            text-align: left;
            padding: 8px;
        }

        #logout_btn:hover {
            color: #ff6b6b;
        }

        #open_btn {
            display: none; /* Mantido conforme seu modelo */
        }

        /* CONTEÚDO PRINCIPAL */
        .main-content {
            flex-grow: 1;
            padding: 1.5rem;
            max-width: calc(100% - var(--sidebar-width));
        }

        /* NOTIFICAÇÕES BELL */
        .btn-bell {
            width: 40px;
            height: 40px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }

        .btn-bell:hover {
            background-color: #e9ecef;
        }

        /* AÇÕES TABELA */
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: none;
            color: white;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .btn-edit { background-color: #198754; }
        .btn-delete { background-color: #dc3545; }

        @media (max-width: 991.98px) {
            .wrapper { flex-direction: column; }
            #sidebar { width: 100%; min-height: auto; }
            .main-content { max-width: 100%; padding: 1rem; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- INÍCIO DO SIDEBAR ENVIADO -->
    <nav id="sidebar" class="d-flex flex-column justify-content-between">
        <div class="p-3">
            <div class="logo d-flex align-items-center gap-2 mb-4 text-white">
                <div class="bg-success p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-mobile-screen-button fs-4"></i>
                </div>
                <h4 class="fw-bold mb-0 item-description">CELLMASTER</h4>
            </div>

            <ul class="nav flex-column gap-2">
                <li class="nav-item side-item <?= ($pagina == 'dashboard') ? 'active' : '' ?>">
                    <a href="dashboard.php" class="nav-link text-white">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="item-description ms-2">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'funcionarios') ? 'active' : '' ?>">
                    <a href="funcionarios.php" class="nav-link text-white">
                        <i class="fa-solid fa-clipboard-user"></i>
                        <span class="item-description ms-2">Funcionários</span>
                    </a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'clientes') ? 'active' : '' ?>">
                    <a href="clientes.php" class="nav-link text-white">
                        <i class="fa-solid fa-users"></i>
                        <span class="item-description ms-2">Clientes</span>
                    </a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'estoque') ? 'active' : '' ?>">
                    <a href="estoque.php" class="nav-link text-white">
                        <i class="fa-solid fa-box-archive"></i>
                        <span class="item-description ms-2">Estoque</span>
                    </a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'servicos') ? 'active' : '' ?>">
                    <a href="servicos.php" class="nav-link text-white">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                        <span class="item-description ms-2">Serviços</span>
                    </a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'orcamentos') ? 'active' : '' ?>">
                    <a href="orcamentos.php" class="nav-link text-white">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span class="item-description ms-2">Orçamento</span>
                    </a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'os') ? 'active' : '' ?>">
                    <a href="ordem_servico.php" class="nav-link text-white">
                        <i class="fa-solid fa-file-contract"></i>
                        <span class="item-description ms-2">Ordem de Serviço</span>
                    </a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'relatorio') ? 'active' : '' ?>">
                    <a href="relatorio.php" class="nav-link text-white">
                        <i class="fa-solid fa-file"></i>
                        <span class="item-description ms-2">Relatório</span>
                    </a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'configuracoes') ? 'active' : '' ?>">
                    <a href="configuracoes.php" class="nav-link text-white">
                        <i class="fa-solid fa-gear"></i>
                        <span class="item-description ms-2">Configurações</span>
                    </a>
                </li>
            </ul>

            <button id="open_btn">
                <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div class="border-top p-3 border-opacity-25" style="border-color: rgba(255,255,255,0.2) !important;">
            <button id="logout_btn" class="w-100" onclick="window.location.href='php/logout.php';">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span class="item-description ms-2">Logout</span>
            </button>
        </div>
    </nav>
    <!-- FIM DO SIDEBAR -->

    <!-- ÁREA DE CONTEÚDO PRINCIPAL -->
    <main class="main-content">

        <!-- INÍCIO DO HEADER ENVIADO -->
        <header class="navbar navbar-expand bg-white border-0 shadow-sm rounded-4 mb-4 px-4 py-3">
            <div class="container-fluid d-flex justify-content-between align-items-center p-0">
                
                <div>
                    <h3 class="fw-bold mb-1 text-dark"><?= $tituloPagina ?></h3>
                    <nav style="--bs-breadcrumb-divider: '>';" class="small">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="home.php" class="text-success text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><?= $breadcrumb ?></li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <?php
                    // Consulta notificações com tratamento de erros
                    $resNotif = @mysqli_query($conn, "SELECT * FROM notificacoes ORDER BY data_criacao DESC LIMIT 5");
                    $resNaoLidas = @mysqli_query($conn, "SELECT COUNT(*) as total FROM notificacoes WHERE lida = 0");
                    $totalNaoLidas = ($resNaoLidas) ? (mysqli_fetch_assoc($resNaoLidas)['total'] ?? 0) : 0;
                    ?>

                    <div class="dropdown">
                        <button class="btn btn-bell rounded-circle d-flex align-items-center justify-content-center p-0 position-relative" 
                                type="button" 
                                id="btnNotificacoes"
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                            <i class="fa-solid fa-bell text-success fs-5"></i>
                            
                            <?php if ($totalNaoLidas > 0): ?>
                                <span id="badgeNotificacao" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                            <?php endif; ?>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2 p-2" style="width: 350px;">
                            <li class="dropdown-header fw-bold text-dark border-bottom pb-2 mb-1">Notificações</li>
                            
                            <?php 
                            if ($resNotif && mysqli_num_rows($resNotif) > 0) {
                                while ($notif = mysqli_fetch_assoc($resNotif)) {
                                    $tempo = date('d/m H:i', strtotime($notif['data_criacao']));
                                    $usuario = htmlspecialchars($notif['usuario'] ?? 'Sistema');
                                    $mensagem = htmlspecialchars($notif['mensagem']);

                                    echo "
                                    <li class='my-1'>
                                        <div class='dropdown-item rounded-3 p-2 small bg-white'>
                                            <div class='text-dark'>{$mensagem}</div>
                                            <div class='d-flex justify-content-between align-items-center mt-1' style='font-size: 0.75rem;'>
                                                <span class='badge bg-light text-secondary border'>por {$usuario}</span>
                                                <span class='text-muted'>{$tempo}</span>
                                            </div>
                                        </div>
                                    </li>";
                                }
                            } else {
                                echo "<li><span class='dropdown-item text-muted small text-center py-3'>Nenhuma notificação</span></li>";
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="d-flex align-items-center gap-2 ps-2">
                        <?php 
                            $fotoBD = $_SESSION["foto"] ?? '';
                            if (!empty($fotoBD) && file_exists("img/perfil/" . $fotoBD)) {
                                $caminhoFoto = "img/perfil/" . $fotoBD;
                            } else {
                                $caminhoFoto = "img/user.jpeg"; 
                            }
                        ?>
                        <img
                            src="<?= $caminhoFoto; ?>?v=<?= time(); ?>"
                            alt="Foto de Perfil" 
                            class="rounded-circle object-fit-cover border" 
                            width="42" 
                            height="42"
                            onerror="this.onerror=null; this.src='img/user.jpeg';">
                        
                        <div class="d-flex flex-column justify-content-center lh-1">
                            <span class="fw-bold text-dark fs-6 mb-1">
                                <?= htmlspecialchars($_SESSION['nome'] ?? 'Utilizador'); ?>
                            </span>
                            <span class="text-secondary small fw-normal">
                                <?= htmlspecialchars($_SESSION['cargo'] ?? 'Atendente'); ?>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </header>
        <!-- FIM DO HEADER -->

        <!-- BARRA SUPERIOR DE AÇÕES -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark m-0">Lista de Orçamentos</h4>
            <button type="button" class="btn btn-success d-flex align-items-center gap-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovoOrcamento">
                <i class="fa-solid fa-plus"></i> Novo Orçamento
            </button>
        </div>

        <!-- CARDS ESTATÍSTICOS -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="bg-white p-3 rounded-4 shadow-sm border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Total Cadastrados</div>
                        <div class="fs-3 fw-bold text-dark"><?= $totalCadastrados ?></div>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle"><i class="fa-solid fa-file-invoice fs-4"></i></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="bg-white p-3 rounded-4 shadow-sm border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Gerados no Mês</div>
                        <div class="fs-3 fw-bold text-dark"><?= $totalMes ?></div>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle"><i class="fa-regular fa-calendar-check fs-4"></i></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="bg-white p-3 rounded-4 shadow-sm border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Aguardando</div>
                        <div class="fs-3 fw-bold text-dark"><?= $totalPendentes ?></div>
                    </div>
                    <div class="bg-warning-subtle text-warning p-3 rounded-circle"><i class="fa-solid fa-clock fs-4"></i></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="bg-white p-3 rounded-4 shadow-sm border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Aprovados</div>
                        <div class="fs-3 fw-bold text-dark"><?= $totalAprovados ?></div>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle"><i class="fa-solid fa-circle-check fs-4"></i></div>
                </div>
            </div>
        </div>

        <!-- FILTRO DE BUSCA -->
        <div class="bg-white p-3 rounded-4 shadow-sm border-0 mb-4">
            <form method="GET" class="row g-2">
                <div class="col-12 col-md-10">
                    <input type="text" name="pesquisa" class="form-control border-light-subtle py-2" placeholder="Pesquisar por cliente ou IMEI..." value="<?= htmlspecialchars($pesquisa); ?>">
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">Pesquisar</button>
                </div>
            </form>
        </div>

        <!-- TABELA DE ORÇAMENTOS -->
        <div class="bg-white rounded-4 shadow-sm border-0 p-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-secondary small text-uppercase">
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>IMEI</th>
                            <th>Valor Total</th>
                            <th>Data</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <?php 
                                $dataFormatada = '-';
                                if (!empty($row['data_dia']) && $row['data_dia'] != '0000-00-00') {
                                    $dataObj = new DateTime($row['data_dia']);
                                    $dataFormatada = $dataObj->format('d/m/Y');
                                }
                                $statusAtual = strtolower(trim($row['status'] ?? ''));
                                $temOS = !empty($row['idos']);
                            ?>
                            <tr>
                                <td class="fw-bold">#<?= $row['idorcamento']; ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['nome_clien'] ?? '-'); ?></td>
                                <td><code><?= htmlspecialchars($row['imei']); ?></code></td>
                                <td class="fw-bold text-success">R$ <?= number_format((float)$row['valor_total'], 2, ',', '.'); ?></td>
                                <td><?= $dataFormatada; ?></td>
                                <td class="text-center">
                                    <span class="<?= classeStatus($row['status'] ?: 'Aguardando'); ?>">
                                        <?= htmlspecialchars($row['status'] ?: 'Aguardando'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                                        <a href="editar_orcamento.php?id=<?= $row['idorcamento']; ?>" class="action-btn btn-edit" title="Editar">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>

                                        <button type="button" 
                                                class="action-btn btn-delete" 
                                                title="Excluir"
                                                onclick="confirmarExclusao(<?= $row['idorcamento']; ?>, '<?= addslashes($statusAtual); ?>')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        <?php if ($statusAtual === 'aprovado'): ?>
                                            <?php if ($temOS): ?>
                                                <a href="visualizar_os.php?id=<?= $row['idos']; ?>" class="btn btn-outline-primary btn-sm rounded px-2 py-1" style="font-size: 11px;">Ver OS</a>
                                            <?php else: ?>
                                                <a href="nova_os.php?orcamento_id=<?= $row['idorcamento']; ?>" class="btn btn-success btn-sm rounded px-2 py-1" style="font-size: 11px;">Abrir OS</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Nenhum orçamento encontrado.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- MODAL NOVO ORÇAMENTO (COM DATA d/m/Y) -->
<div class="modal fade" id="modalNovoOrcamento" tabindex="-1" aria-labelledby="modalNovoOrcamentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="modalNovoOrcamentoLabel"><i class="fa-solid fa-file-circle-plus me-2"></i>Novo Orçamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="salvar_orcamento.php" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cliente <span class="text-danger">*</span></label>
                            <select name="cliente_idcliente" class="form-select" required>
                                <option value="">Selecione o Cliente...</option>
                                <?php if ($clientes): ?>
                                    <?php 
                                    $clientes->data_seek(0);
                                    while($c = $clientes->fetch_assoc()): 
                                    ?>
                                        <option value="<?= $c['idcliente']; ?>"><?= htmlspecialchars($c['nome_clien']); ?></option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Atendente / Técnico <span class="text-danger">*</span></label>
                            <select name="funcionario_idfuncionario" class="form-select" required>
                                <option value="">Selecione o Funcionário...</option>
                                <?php if ($funcionarios): ?>
                                    <?php 
                                    $funcionarios->data_seek(0);
                                    while($f = $funcionarios->fetch_assoc()): 
                                    ?>
                                        <option value="<?= $f['idfuncionario']; ?>"><?= htmlspecialchars($f['nome_func']); ?></option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Marca</label>
                            <input type="text" name="marca" class="form-control" placeholder="Ex: Apple, Samsung">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Modelo</label>
                            <input type="text" name="modelo" class="form-control" placeholder="Ex: iPhone 12, Galaxy S21">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">IMEI / Nº Série</label>
                            <input type="text" name="imei" class="form-control" placeholder="358293049128394">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Defeito Informado <span class="text-danger">*</span></label>
                            <textarea name="defeito" class="form-control" rows="3" required placeholder="Relato do cliente sobre o problema..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Observações</label>
                            <textarea name="observacoes" class="form-control" rows="2" placeholder="Avarias visíveis, riscos, estado da bateria..."></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Valor do Orçamento (R$)</label>
                            <input type="number" step="0.01" min="0" name="valor_total" class="form-control" value="0.00" required>
                        </div>

                        <!-- CAMPO COM FORMATO d/m/Y -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Data do Orçamento</label>
                            <input type="text" 
                                   id="data_dia" 
                                   name="data_dia" 
                                   class="form-control" 
                                   placeholder="DD/MM/AAAA" 
                                   maxlength="10" 
                                   value="<?= date('d/m/Y'); ?>" 
                                   onkeyup="mascaraData(this)" 
                                   required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status do Orçamento</label>
                            <select name="status" class="form-select" required>
                                <option value="Aguardando" selected>Aguardando</option>
                                <option value="Aprovado">Aprovado</option>
                                <option value="Reprovado">Reprovado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-semibold"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Orçamento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Máscara para formatar a data como DD/MM/AAAA no input
function mascaraData(input) {
    let v = input.value.replace(/\D/g, "");
    if (v.length > 8) v = v.substring(0, 8);
    
    if (v.length >= 5) {
        input.value = v.replace(/^(\d{2})(\d{2})(\d{0,4})/, "$1/$2/$3");
    } else if (v.length >= 3) {
        input.value = v.replace(/^(\d{2})(\d{0,2})/, "$1/$2");
    } else {
        input.value = v;
    }
}

// Confirmação de exclusão
function confirmarExclusao(id, status) {
    if (status === 'reprovado') {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja realmente excluir este orçamento reprovado?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'excluir_orcamento.php?id=' + id + '&confirmado=1';
            }
        });
    } else {
        window.location.href = 'excluir_orcamento.php?id=' + id;
    }
}

// Lógica de limpar notificações (fornecida no Header)
document.addEventListener('DOMContentLoaded', function () {
    const btnNotificacoes = document.getElementById('btnNotificacoes');
    const badgeNotificacao = document.getElementById('badgeNotificacao');

    if (btnNotificacoes) {
        btnNotificacoes.addEventListener('show.bs.dropdown', function () {
            if (badgeNotificacao) {
                badgeNotificacao.style.display = 'none';
            }
            fetch('php/marcarLidas.php')
                .then(response => response.json())
                .then(data => {})
                .catch(error => console.error('Erro ao marcar notificações:', error));
        });
    }
});
</script>
</body>
</html>