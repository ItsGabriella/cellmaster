<?php
    session_start();
    include("conexao.php"); // Certifique-se de que o caminho da conexão está correto

    // Recupera mensagens de retorno/sessão se existirem
    $msgSucesso = isset($_GET['sucesso']) ? "Operação realizada com sucesso!" : "";
    $msgErro    = isset($_GET['erro']) ? "Ocorreu um erro ao processar a solicitação." : "";

    // Captura os filtros individualmente
    $buscaOS = isset($_GET["nBuscaOS"]) ? trim($_GET["nBuscaOS"]) : "";
    $status  = isset($_GET["nStatus"])  ? $_GET["nStatus"]        : "Todos";

    // Consulta SQL base para listagem
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

    // Filtros dinâmicos
    if ($buscaOS !== '') {
        $pesquisaEscapada = $conn->real_escape_string($buscaOS);
        $sql .= " AND (
            CAST(os.idos AS CHAR) LIKE '%{$pesquisaEscapada}%' 
            OR c.nome_clien LIKE '%{$pesquisaEscapada}%' 
            OR o.imei LIKE '%{$pesquisaEscapada}%'
        )";
    }

    if ($status !== 'Todos') {
        $statusEscapado = $conn->real_escape_string($status);
        $sql .= " AND os.status_os = '{$statusEscapado}'";
    }

    $sql .= " ORDER BY os.idos DESC";
    $result = $conn->query($sql);

    // Totais dos Cards de Métricas
    $totalCadastrados = $conn->query("SELECT COUNT(*) as total FROM ordem_servico")->fetch_assoc()['total'] ?? 0;
    $totalEmAndamento = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os = 'Em Andamento'")->fetch_assoc()['total'] ?? 0;
    $totalAbertas    = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os = 'Aberta'")->fetch_assoc()['total'] ?? 0;
    $totalConcluidos = $conn->query("SELECT COUNT(*) as total FROM ordem_servico WHERE status_os IN ('Concluído', 'Entregue')")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordem de Serviço</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
        $pagina = 'os';
        include('php/sidebar.php');
    ?>

    <main class="flex-grow-1 p-4 bg-light">

        <?php 
            $tituloPagina = "Ordem de Serviço";
            $breadcrumb   = "Ordem de Serviço";
            include 'php/header.php'; 
        ?>

        <?php if (!empty($msgSucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?= $msgSucesso; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($msgErro)): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <?= $msgErro; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Total Cadastrados</h6>
                        <h2 class="fw-bold"><?= $totalCadastrados ?></h2>
                        <small>Ordens registradas</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Em Andamento</h6>
                        <h2 class="fw-bold text-primary"><?= $totalEmAndamento ?></h2>
                        <small>Em manutenção</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Abertas</h6>
                        <h2 class="fw-bold text-warning"><?= $totalAbertas ?></h2>
                        <small>Aguardando atendimento</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Concluídos / Entregues</h6>
                        <h2 class="fw-bold text-success"><?= $totalConcluidos ?></h2>
                        <small>Finalizadas com sucesso</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <form method="GET" action="ordens_servico.php" id="formFiltros">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Buscar O.S.</label>
                            <div class="input-group shadow-sm">
                                <input
                                    type="text"
                                    class="form-control border-success"
                                    placeholder="Pesquisar por ID, Cliente ou IMEI..."
                                    name="nBuscaOS"
                                    value="<?= htmlspecialchars($buscaOS) ?>">

                                <button class="btn btn-success px-3" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="nStatus" class="form-select select-verde">
                                <option value="Todos" <?= ($status == 'Todos') ? 'selected' : '' ?>>Todos</option>
                                <option value="Aberta" <?= ($status == 'Aberta') ? 'selected' : '' ?>>Aberta</option>
                                <option value="Em Andamento" <?= ($status == 'Em Andamento') ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="Concluído" <?= ($status == 'Concluído') ? 'selected' : '' ?>>Concluído</option>
                                <option value="Entregue" <?= ($status == 'Entregue') ? 'selected' : '' ?>>Entregue</option>
                                <option value="Cancelada" <?= ($status == 'Cancelada') ? 'selected' : '' ?>>Cancelada</option>
                            </select>
                        </div>

                        <div class="col-lg-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <a href="ordens_servico.php" class="btn btn-outline-success btn-filtrar flex-grow-1 text-center" title="Limpar Filtros">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>

                                <button type="submit" class="btn btn-success btn-filtrar flex-grow-1">
                                    <i class="fa-solid fa-filter"></i>
                                    Filtrar
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Cliente</th>
                            <th>IMEI</th>
                            <th>Valor Final</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($os = $result->fetch_assoc()): ?>
                                <?php
                                    $st = $os['status_os'] ?? 'Aberta';
                                    $badgeClass = 'bg-warning text-dark';
                                    if ($st == 'Em Andamento') $badgeClass = 'bg-primary';
                                    else if ($st == 'Concluído' || $st == 'Entregue') $badgeClass = 'bg-success';
                                    else if ($st == 'Cancelada') $badgeClass = 'bg-danger';
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#<?= $os['idos']; ?></td>
                                    <td>
                                        <span class="fw-semibold text-dark"><?= htmlspecialchars($os['nome_clien'] ?? 'Cliente não informado'); ?></span>
                                    </td>
                                    <td>
                                        <span class="text-muted small"><?= htmlspecialchars($os['imei'] ?? 'N/A'); ?></span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        R$ <?= number_format($os['valor_final'] ?? 0, 2, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?= $badgeClass; ?> p-2 px-3"><?= htmlspecialchars($st); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="visualizar_os.php?id=<?= $os['idos']; ?>"
                                               class="btn btn-outline-primary btn-sm rounded-2"
                                               title="Ver/Imprimir OS">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <button type="button"
                                                    class="btn btn-outline-success btn-sm rounded-2 btn-editar-os"
                                                    data-idos="<?= $os['idos']; ?>"
                                                    data-laudo="<?= htmlspecialchars($os['laudo_tecnico'] ?? ''); ?>"
                                                    data-valor="<?= $os['valor_final'] ?? '0.00'; ?>"
                                                    data-status="<?= htmlspecialchars($os['status_os'] ?? 'Aberta'); ?>"
                                                    title="Editar OS">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm rounded-2"
                                                    onclick="confirmarExclusao(<?= $os['idos']; ?>)"
                                                    title="Excluir OS">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-folder-open fs-2 mb-2 text-secondary d-block"></i>
                                    Nenhuma Ordem de Serviço encontrada.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white py-3">
                <nav>
                    <ul class="pagination justify-content-end mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                        <li class="page-item active"><a class="page-link bg-success border-success" href="#">1</a></li>
                        <li class="page-item"><a class="page-link text-success" href="#">Próximo</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </main>

    <div class="modal fade" id="modalEditarOS" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalEditarOSLabel"><i class="fa-solid fa-pen-to-square me-2"></i>Editar Ordem de Serviço</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formEditarOS" method="POST" action="salvar_edicao_os.php">
                    <div class="modal-body p-4">
                        <input type="hidden" name="idos" id="edit_idos">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Laudo Técnico</label>
                            <textarea name="laudo_tecnico" id="edit_laudo" class="form-control" rows="4" placeholder="Insira as observações do reparo..."></textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Valor Final (R$)</label>
                                <input type="number" step="0.01" name="valor_final" id="edit_valor" class="form-control" placeholder="0.00" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status da O.S.</label>
                                <select name="status_os" id="edit_status" class="form-select select-verde" required>
                                    <option value="Aberta">Aberta</option>
                                    <option value="Em Andamento">Em Andamento</option>
                                    <option value="Concluído">Concluído</option>
                                    <option value="Entregue">Entregue</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal de Edição
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarOS'));

        document.querySelectorAll('.btn-editar-os').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit_idos').value = this.getAttribute('data-idos');
                document.getElementById('edit_laudo').value = this.getAttribute('data-laudo');
                document.getElementById('edit_valor').value = this.getAttribute('data-valor');
                document.getElementById('edit_status').value = this.getAttribute('data-status');

                document.getElementById('modalEditarOSLabel').innerHTML = '<i class="fa-solid fa-pen-to-square me-2"></i>Editar OS #' + this.getAttribute('data-idos');
                modalEditar.show();
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
            confirmButtonColor: '#198754',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'excluir_os.php?id=' + idos;
            }
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>
</body>
</html>