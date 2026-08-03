<?php
// Inicia a sessão para capturar as informações de usuário no header/sidebar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("php/conexaoBD.php");

// Recupera o nome do usuário para registro de logs
$usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';

// Definições para a Sidebar e Header
$pagina       = 'orcamento';
$tituloPagina = "Orçamento";
$breadcrumb   = "Orçamento";

// Captura do filtro de pesquisa
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : "";

// Consultas estatísticas para os cards
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM orcamento");
$totalCadastrados = $totalQuery->fetch_assoc()['total'] ?? 0;

$mesAtualQuery = $conn->query("SELECT COUNT(*) as total FROM orcamento WHERE MONTH(data_dia) = MONTH(CURRENT_DATE()) AND YEAR(data_dia) = YEAR(CURRENT_DATE())");
$totalMes = $mesAtualQuery->fetch_assoc()['total'] ?? 0;

$pendentesQuery = $conn->query("SELECT COUNT(*) as total FROM orcamento WHERE status = 'Aguardando' OR status IS NULL OR status = ''");
$totalPendentes = $pendentesQuery->fetch_assoc()['total'] ?? 0;

$aprovadosQuery = $conn->query("SELECT COUNT(*) as total FROM orcamento WHERE status = 'Aprovado'");
$totalAprovados = $aprovadosQuery->fetch_assoc()['total'] ?? 0;

// Buscar clientes e funcionários para o formulário do modal
$clientes = $conn->query("SELECT idcliente, nome_clien FROM cliente ORDER BY nome_clien ASC");
$funcionarios = $conn->query("SELECT idfuncionario, nome_func FROM funcionario ORDER BY nome_func ASC");

// Consulta principal dos orçamentos
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
    <title>Orçamentos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
        include('php/sidebar.php');
    ?>

    <main class="flex-grow-1 p-4 bg-light">

    <?php 
        include 'php/header.php'; 
    ?>

    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-success px-4 py-2 rounded-3 shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalNovoOrcamento">
            <i class="fa-solid fa-plus me-2"></i>
            Novo Orçamento
        </button>
    </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Total Cadastrados</h6>
                        <h2 class="fw-bold"><?= $totalCadastrados ?></h2>
                        <small>Orçamentos gerais</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Gerados no Mês</h6>
                        <h2 class="fw-bold"><?= $totalMes ?></h2>
                        <small>Mês atual</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Aguardando</h6>
                        <h2 class="fw-bold text-warning"><?= $totalPendentes ?></h2>
                        <small>Pendentes de resposta</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Aprovados</h6>
                        <h2 class="fw-bold text-success"><?= $totalAprovados ?></h2>
                        <small>Prontos para OS</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <form method="GET" action="orcamento.php" id="formFiltros">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-9">
                            <label class="form-label fw-semibold">Buscar Orçamento</label>
                            <div class="input-group shadow-sm">
                                <input
                                    type="text"
                                    class="form-control border-success"
                                    placeholder="Pesquisar por cliente ou IMEI..."
                                    name="pesquisa"
                                    value="<?= htmlspecialchars($pesquisa) ?>">

                                <button class="btn btn-success px-3" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-30">
                                <a href="orcamento.php" class="btn btn-outline-success btn-filtrar flex-grow-1 text-center" title="Limpar">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>

                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>IMEI</th>
                            <th>Valor Total</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ações</th>
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
                                    <td>
                                        <span class="<?= classeStatus($row['status'] ?: 'Aguardando'); ?>">
                                            <?= htmlspecialchars($row['status'] ?: 'Aguardando'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <a href="php/editar_orcamento.php?id=<?= $row['idorcamento']; ?>" class="btn btn-sm btn-outline-success" title="Editar">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>

                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Excluir"
                                                    onclick="confirmarExclusao(<?= $row['idorcamento']; ?>, '<?= addslashes($statusAtual); ?>')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                            <?php if ($statusAtual === 'aprovado'): ?>
                                                <?php if ($temOS): ?>
                                                    <a href="php/visualizar_os.php?id=<?= $row['idos']; ?>" class="btn btn-sm btn-outline-primary" style="font-size: 0.8rem;">Ver OS</a>
                                                <?php else: ?>
                                                    <a href="php/nova_os.php?orcamento_id=<?= $row['idorcamento']; ?>" class="btn btn-sm btn-success" style="font-size: 0.8rem;">Abrir OS</a>
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

    <div class="modal fade" id="modalNovoOrcamento" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Novo Orçamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="php/salvar_orcamento.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
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
                                <label class="form-label fw-semibold">Atendente / Técnico <span class="text-danger">*</span></label>
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
                                <label class="form-label fw-semibold">Marca</label>
                                <input type="text" name="marca" class="form-control" placeholder="Ex: Apple, Samsung">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Modelo</label>
                                <input type="text" name="modelo" class="form-control" placeholder="Ex: iPhone 12, Galaxy S21">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">IMEI / Nº Série</label>
                                <input type="text" name="imei" class="form-control" placeholder="358293049128394">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Defeito Informado <span class="text-danger">*</span></label>
                                <textarea name="defeito" class="form-control" rows="3" required placeholder="Relato do cliente sobre o problema..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Observações</label>
                                <textarea name="observacoes" class="form-control" rows="2" placeholder="Avarias visíveis, riscos, estado da bateria..."></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Valor do Orçamento (R$)</label>
                                <input type="number" step="0.01" min="0" name="valor_total" class="form-control" value="0.00" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Data do Orçamento</label>
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
                                <label class="form-label fw-semibold">Status do Orçamento</label>
                                <select name="status" class="form-select" required>
                                    <option value="Aguardando" selected>Aguardando</option>
                                    <option value="Aprovado">Aprovado</option>
                                    <option value="Reprovado">Reprovado</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Salvar Orçamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>

    <script>
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
                    window.location.href = 'php/excluir_orcamento.php?id=' + id + '&confirmado=1';
                }
            });
        } else {
            window.location.href = 'php/excluir_orcamento.php?id=' + id;
        }
    }
    </script>
</body>
</html>