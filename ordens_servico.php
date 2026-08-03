<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexao.php");

$pagina = 'os';
$tituloPagina = 'Ordem de Serviço';

// Mensagens de retorno
$msgSucesso = isset($_GET['sucesso']) ? "Operação realizada com sucesso!" : "";
$msgErro = isset($_GET['erro']) ? "Ocorreu um erro ao processar a solicitação." : "";

// Captura valor da pesquisa
$pesquisa = trim($_GET['pesquisa'] ?? '');

// Consulta Principal
$sql = "
    SELECT
        os.idos,
        os.laudo_tecnico,
        os.valor_final,
        os.status_os,
        c.nome_clien,
        o.marca,
        o.modelo,
        o.imei
    FROM ordem_servico os
    LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
    LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
    WHERE 1=1
";

// Filtro SQL flexível para ID, Cliente ou IMEI
if ($pesquisa !== '') {
    $pesquisaEscapada = $conn->real_escape_string($pesquisa);
    $sql .= " AND (
        CAST(os.idos AS CHAR) LIKE '%{$pesquisaEscapada}%' 
        OR c.nome_clien LIKE '%{$pesquisaEscapada}%' 
        OR o.imei LIKE '%{$pesquisaEscapada}%'
    )";
}

$sql .= " ORDER BY os.idos DESC";
$result = $conn->query($sql);

// Totais dos Cards
$totalCadastrados = $conn->query("SELECT COUNT(*) as total FROM ordem_servico")->fetch_assoc()['total'] ?? 0;
$totalConcluidos = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os IN ('Concluído', 'Entregue')")->fetch_assoc()['total'] ?? 0;
$totalEmAndamento = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os = 'Em Andamento'")->fetch_assoc()['total'] ?? 0;
$totalAbertas = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os = 'Aberta'")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloPagina ?> - CELLMASTER</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 CSS & JS -->
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

        /* SIDEBAR */
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

        /* MAIN CONTENT */
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

        /* BADGES */
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

        /* TABELA */
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

        /* BOTÕES DE AÇÕES */
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
        .btn-action:hover {
            opacity: 0.85;
        }
        
        .btn-action-edit { background-color: #1b4d22; color: #ffffff !important; }
        .btn-action-delete { background-color: #dc3545; color: #ffffff !important; }
        .btn-action-view { background-color: #0d6efd; color: #ffffff !important; }

        @media (max-width: 991.98px) {
            .wrapper { flex-direction: column; }
            #sidebar { width: 100%; min-height: auto; }
            .main-content { max-width: 100%; padding: 1rem; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR CELLMASTER -->
    <nav id="sidebar" class="d-flex flex-column justify-content-between p-3">
        <div>
            <div class="logo d-flex align-items-center gap-2 mb-4 text-white px-2">
                <div class="bg-success p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #2e7d32 !important;">
                    <i class="fa-solid fa-mobile-screen-button fs-4"></i>
                </div>
                <h4 class="fw-bold mb-0" style="letter-spacing: -0.5px;">CELLMASTER</h4>
            </div>

            <ul class="nav flex-column gap-1">
                <li class="nav-item side-item <?= ($pagina == 'dashboard') ? 'active' : '' ?>">
                    <a href="dashboard.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'funcionarios') ? 'active' : '' ?>">
                    <a href="funcionarios.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-clipboard-user me-2"></i> Funcionários</a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'clientes') ? 'active' : '' ?>">
                    <a href="clientes.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-users me-2"></i> Clientes</a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'estoque') ? 'active' : '' ?>">
                    <a href="estoque.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-box-archive me-2"></i> Estoque</a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'servicos') ? 'active' : '' ?>">
                    <a href="servicos.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-screwdriver-wrench me-2"></i> Serviços</a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'orcamentos') ? 'active' : '' ?>">
                    <a href="orcamentos.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-file-invoice-dollar me-2"></i> Orçamento</a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'os') ? 'active' : '' ?>">
                    <a href="ordens_servico.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-file-contract me-2"></i> Ordem de Serviço</a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'relatorio') ? 'active' : '' ?>">
                    <a href="relatorio.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-file me-2"></i> Relatório</a>
                </li>
                <li class="nav-item side-item <?= ($pagina == 'configuracoes') ? 'active' : '' ?>">
                    <a href="configuracoes.php" class="nav-link text-white py-2 px-3"><i class="fa-solid fa-gear me-2"></i> Configurações</a>
                </li>
            </ul>
        </div>

        <div class="border-top pt-3 border-opacity-25" style="border-color: rgba(255,255,255,0.2) !important;">
            <a href="php/logout.php" class="btn text-white w-100 text-start px-3 py-2" style="background: transparent;">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
            </a>
        </div>
    </nav>

    <!-- ÁREA PRINCIPAL -->
    <main class="main-content">

        <!-- HEADER TOPO -->
        <header class="card-custom mb-4 p-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><?= $tituloPagina ?></h4>
                <nav style="--bs-breadcrumb-divider: '›';" class="small">
                    <ol class="breadcrumb mb-0 text-muted">
                        <li class="breadcrumb-item"><a href="home.php" class="text-success text-decoration-none fw-medium">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ordem de Serviço</li>
                    </ol>
                </nav>
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php
                    $nomeUsuario = $_SESSION['nome'] ?? 'Utilizador';
                    $cargoUsuario = $_SESSION['cargo'] ?? 'Atendente';
                    $fotoBD = $_SESSION["foto"] ?? '';
                    $temFoto = !empty($fotoBD) && file_exists("img/perfil/" . $fotoBD);
                    
                    $partesNome = explode(' ', trim($nomeUsuario));
                    $iniciais = strtoupper(substr($partesNome[0], 0, 1));
                    if (count($partesNome) > 1) {
                        $iniciais .= strtoupper(substr(end($partesNome), 0, 1));
                    }
                ?>
                <div class="d-flex align-items-center gap-3">
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
            </div>
        </header>

        <!-- AVISOS DE SUCESSO OU ERRO -->
        <?php if (!empty($msgSucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show card-custom border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                <?= $msgSucesso; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- CARDS DE RESUMO SUPERIORES -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card-custom card-stat p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">Total Cadastrados</small>
                        <h3 class="fw-bold text-dark m-0 mt-1"><?= $totalCadastrados ?></h3>
                    </div>
                    <div class="bg-light p-3 rounded-4 text-secondary">
                        <i class="fa-solid fa-file-contract fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom card-stat p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">Em Andamento</small>
                        <h3 class="fw-bold text-primary m-0 mt-1"><?= $totalEmAndamento ?></h3>
                    </div>
                    <div class="bg-primary-subtle p-3 rounded-4 text-primary">
                        <i class="fa-solid fa-spinner fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom card-stat p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">Abertas</small>
                        <h3 class="fw-bold text-warning m-0 mt-1"><?= $totalAbertas ?></h3>
                    </div>
                    <div class="bg-warning-subtle p-3 rounded-4 text-warning">
                        <i class="fa-solid fa-folder-open fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom card-stat p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">Concluídos / Entregues</small>
                        <h3 class="fw-bold text-success m-0 mt-1"><?= $totalConcluidos ?></h3>
                    </div>
                    <div class="bg-success-subtle p-3 rounded-4 text-success">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARRA DE PESQUISA COM BOTÃO DE SUBMETER E BUSCA RÁPIDA -->
        <div class="card-custom p-3.5 mb-4">
            <form method="GET" action="ordens_servico.php" class="w-100" id="formBusca">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 text-muted ps-3">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="pesquisa" id="inputPesquisa" class="form-control bg-light border-0 py-2.5" placeholder="Pesquisar por ID, Cliente ou IMEI..." value="<?= htmlspecialchars($pesquisa); ?>" autocomplete="off">
                    
                    <button type="submit" class="btn btn-green-main px-4 fw-semibold rounded-end-3">
                        Buscar
                    </button>

                    <?php if ($pesquisa !== ''): ?>
                        <a href="ordens_servico.php" class="btn btn-light border-0 text-muted d-flex align-items-center ms-2 rounded-3" title="Limpar busca">
                            <i class="fa-solid fa-xmark me-1"></i> Limpar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- TABELA DE ORDENS DE SERVIÇO -->
        <div class="card-custom p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelaOS">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>CLIENTE</th>
                            <th>IMEI</th>
                            <th>VALOR FINAL</th>
                            <th>STATUS</th>
                            <th class="text-center pe-4">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($os = $result->fetch_assoc()): ?>
                                <?php
                                    $status = $os['status_os'] ?? 'Aberta';
                                    $badgeClass = 'badge-aberta';
                                    if ($status == 'Em Andamento') $badgeClass = 'badge-andamento';
                                    else if ($status == 'Concluído' || $status == 'Entregue') $badgeClass = 'badge-concluido';
                                    else if ($status == 'Cancelada') $badgeClass = 'badge-cancelada';
                                ?>
                                <tr class="linha-os">
                                    <td class="ps-4 fw-bold text-dark campo-id">#<?= $os['idos']; ?></td>
                                    <td class="campo-cliente">
                                        <span class="fw-semibold text-dark"><?= htmlspecialchars($os['nome_clien'] ?? 'Cliente não informado'); ?></span>
                                    </td>
                                    <td class="campo-imei">
                                        <span class="text-muted fs-7"><?= htmlspecialchars($os['imei'] ?? 'N/A'); ?></span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        R$ <?= number_format($os['valor_final'] ?? 0, 2, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-status <?= $badgeClass; ?>"><?= htmlspecialchars($status); ?></span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-1.5">
                                            <!-- EDITAR -->
                                            <button type="button"
                                                    class="btn btn-action btn-action-edit btn-editar-os"
                                                    data-idos="<?= $os['idos']; ?>"
                                                    data-laudo="<?= htmlspecialchars($os['laudo_tecnico'] ?? ''); ?>"
                                                    data-valor="<?= $os['valor_final'] ?? '0.00'; ?>"
                                                    data-status="<?= htmlspecialchars($os['status_os'] ?? 'Aberta'); ?>"
                                                    title="Editar OS">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>

                                            <!-- EXCLUIR -->
                                            <button type="button"
                                                    class="btn btn-action btn-action-delete"
                                                    onclick="confirmarExclusao(<?= $os['idos']; ?>)"
                                                    title="Excluir OS">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>

                                            <!-- VER/IMPRIMIR -->
                                            <a href="visualizar_os.php?id=<?= $os['idos']; ?>"
                                               class="btn btn-action btn-action-view"
                                               title="Ver/Imprimir OS">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr id="linhaVazia">
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-folder-open fs-2 mb-2 text-black-50 d-block"></i>
                                    Nenhuma Ordem de Serviço encontrada.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- MODAL DE EDITAR OS -->
<div class="modal fade" id="modalEditarOS" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle p-3 rounded-3 text-success d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-pen-to-square fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark m-0" id="modalEditarOSLabel">Editar Ordem de Serviço</h5>
                        <small class="text-muted">Ajuste os dados técnicos da OS</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formEditarOS" method="POST" action="salvar_edicao_os.php">
                <div class="modal-body p-4">
                    <input type="hidden" name="idos" id="edit_idos">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Laudo Técnico</label>
                        <textarea name="laudo_tecnico" id="edit_laudo" class="form-control rounded-3" rows="3" placeholder="Insira as observações do reparo..."></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Valor Final (R$)</label>
                            <input type="number" step="0.01" name="valor_final" id="edit_valor" class="form-control rounded-3" placeholder="0.00" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Status da O.S.</label>
                            <select name="status_os" id="edit_status" class="form-select rounded-3" required>
                                <option value="Aberta">Aberta</option>
                                <option value="Em Andamento">Em Andamento</option>
                                <option value="Concluído">Concluído</option>
                                <option value="Entregue">Entregue</option>
                                <option value="Cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 pb-4 px-4 gap-2">
                    <button type="button" class="btn btn-light border fw-semibold px-4 py-2 rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-green-main fw-semibold px-4 py-2 rounded-3">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Lógica do Modal de Edição
    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarOS'));

    document.querySelectorAll('.btn-editar-os').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_idos').value = this.getAttribute('data-idos');
            document.getElementById('edit_laudo').value = this.getAttribute('data-laudo');
            document.getElementById('edit_valor').value = this.getAttribute('data-valor');
            document.getElementById('edit_status').value = this.getAttribute('data-status');

            document.getElementById('modalEditarOSLabel').innerText = 'Editar OS #' + this.getAttribute('data-idos');
            modalEditar.show();
        });
    });

    // 2. Filtro em tempo real (JS) - Filtra instantaneamente ao digitar
    const inputPesquisa = document.getElementById('inputPesquisa');
    const linhasOS = document.querySelectorAll('.linha-os');

    inputPesquisa.addEventListener('keyup', function() {
        const termo = this.value.toLowerCase().trim();

        linhasOS.forEach(linha => {
            const textoLinha = linha.innerText.toLowerCase();
            if (textoLinha.includes(termo)) {
                linha.style.display = '';
            } else {
                linha.style.display = 'none';
            }
        });
    });
});

// Confirmar exclusão com SweetAlert2
function confirmarExclusao(idos) {
    Swal.fire({
        title: 'Excluir Ordem de Serviço?',
        text: "Esta ação não poderá ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1b4d22',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'excluir_os.php?id=' + idos;
        }
    });
}
</script>

</body>
</html>