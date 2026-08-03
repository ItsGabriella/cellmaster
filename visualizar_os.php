<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexao.php");

$idos = (int)($_GET['id'] ?? 0);

if ($idos <= 0) {
    header("Location: ordens_servico.php");
    exit();
}

// Mensagens de Sucesso ou Erro após salvar
$msgSucesso = isset($_GET['sucesso']) ? "Ordem de Serviço atualizada com sucesso!" : "";
$msgErro = isset($_GET['erro']) ? "Erro ao atualizar a Ordem de Serviço." : "";

// Consulta ajustada para a estrutura REAL do banco 'os_cellmaster'
$sql = "
    SELECT 
        os.idos,
        os.laudo_tecnico,
        os.valor_final,
        os.status_os,
        c.nome_clien,
        c.tel_clien AS telefone_clien,
        c.email_clien,
        o.defeito AS defeito_informado,
        o.marca,
        o.modelo,
        o.imei
    FROM ordem_servico os
    LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
    LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
    WHERE os.idos = {$idos}
    LIMIT 1
";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo "<h3>Ordem de Serviço não encontrada.</h3><a href='ordens_servico.php'>Voltar</a>";
    exit();
}

$os = $result->fetch_assoc();

// Formatação do código exibido no topo (ex: OS-0001)
$codigoOS = "OS-" . str_pad($os['idos'], 4, "0", STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Ordem de Serviço - CELLMASTER</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
        }

        .btn-yellow {
            background-color: #ffc107;
            color: #000;
            font-weight: 600;
            border: none;
        }
        .btn-yellow:hover {
            background-color: #e0a800;
            color: #000;
        }

        .btn-red {
            background-color: #dc3545;
            color: #fff;
            font-weight: 600;
            border: none;
        }
        .btn-red:hover {
            background-color: #bb2d3b;
            color: #fff;
        }

        .btn-green-main {
            background-color: var(--green-primary);
            color: #fff;
            border: none;
        }
        .btn-green-main:hover {
            background-color: var(--green-hover);
            color: #fff;
        }

        .badge-status {
            padding: 8px 18px;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 20px;
        }
        .badge-aberta { background-color: #fff8e1; color: #b78103; }
        .badge-andamento { background-color: #e3f2fd; color: #0277bd; }
        .badge-concluido { background-color: #6c757d; color: #ffffff; }
        .badge-cancelada { background-color: #ffebee; color: #c62828; }

        @media (max-width: 991.98px) {
            .wrapper { flex-direction: column; }
            #sidebar { width: 100%; min-height: auto; }
            .main-content { max-width: 100%; padding: 1rem; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- MAIN CONTENT -->
    <main class="main-content mx-auto" style="max-width: 1200px;">

        <!-- HEADER DA PÁGINA -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Detalhes da Ordem de Serviço</h4>
                <nav style="--bs-breadcrumb-divider: '›';" class="small">
                    <ol class="breadcrumb mb-0 text-muted">
                        <li class="breadcrumb-item"><a href="home.php" class="text-success text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="ordens_servico.php" class="text-success text-decoration-none">Ordem de Serviço</a></li>
                        <li class="breadcrumb-item active"><?= $codigoOS; ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if (!empty($msgSucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show card-custom border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?= $msgSucesso; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- BARRA SUPERIOR DE AÇÕES -->
        <div class="card-custom p-4 mb-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success-subtle p-3 rounded-3 text-success">
                    <i class="fa-solid fa-file-signature fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase fs-7">ORDEM DE SERVIÇO</small>
                    <h4 class="fw-bold m-0 text-dark"><?= $codigoOS; ?></h4>
                </div>
            </div>

            <!-- BOTÕES: EDITAR (ABRE O POP-UP), GERAR PDF E VOLTAR -->
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-yellow px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#modalEditarOS">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Editar
                </button>
                <a href="gerar_pdf.php?id=<?= $os['idos']; ?>" target="_blank" class="btn btn-red px-3 py-2 rounded-3">
                    <i class="fa-solid fa-file-pdf me-1"></i> Gerar PDF
                </a>
                <a href="ordens_servico.php" class="btn btn-light border px-3 py-2 rounded-3 text-secondary fw-semibold">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>

        <!-- CABEÇALHO DO CLIENTE -->
        <div class="card-custom p-4 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold text-dark m-0"><?= htmlspecialchars($os['nome_clien'] ?? 'Cliente não informado'); ?></h3>
                <span class="text-muted"><i class="fa-solid fa-mobile-screen me-1"></i> <?= htmlspecialchars(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? '')); ?></span>
            </div>
            <div>
                <?php 
                    $status = $os['status_os'] ?? 'Aberta';
                    $badgeClass = 'badge-aberta';
                    if ($status == 'Em Andamento') $badgeClass = 'badge-andamento';
                    else if ($status == 'Concluído' || $status == 'Entregue') $badgeClass = 'badge-concluido';
                    else if ($status == 'Cancelada') $badgeClass = 'badge-cancelada';
                ?>
                <span class="badge badge-status <?= $badgeClass; ?>"><?= htmlspecialchars($status); ?></span>
            </div>
        </div>

        <!-- GRID DE DETALHES -->
        <div class="row g-4">
            <!-- DADOS DO CLIENTE -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100">
                    <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-user me-2"></i>Dados do Cliente</h5>
                    <p class="mb-2"><strong>Nome:</strong> <?= htmlspecialchars($os['nome_clien'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong>Telefone:</strong> <?= htmlspecialchars($os['telefone_clien'] ?? 'N/A'); ?></p>
                    <p class="mb-0"><strong>E-mail:</strong> <?= htmlspecialchars($os['email_clien'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <!-- DADOS DO APARELHO -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100">
                    <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-mobile-screen-button me-2"></i>Dados do Aparelho</h5>
                    <p class="mb-2"><strong>Marca:</strong> <?= htmlspecialchars($os['marca'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong>Modelo:</strong> <?= htmlspecialchars($os['modelo'] ?? 'N/A'); ?></p>
                    <p class="mb-0"><strong>IMEI:</strong> <span class="text-danger fw-semibold"><?= htmlspecialchars($os['imei'] ?? 'N/A'); ?></span></p>
                </div>
            </div>

            <!-- INFORMAÇÕES TÉCNICAS -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100">
                    <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-wrench me-2"></i>Informações Técnicas</h5>
                    <p class="mb-2"><strong>Defeito Informado:</strong> <?= htmlspecialchars($os['defeito_informado'] ?? 'N/A'); ?></p>
                    <p class="mb-0"><strong>Laudo Técnico:</strong> <?= nl2br(htmlspecialchars($os['laudo_tecnico'] ?? 'Nenhuma observação informada.')); ?></p>
                </div>
            </div>

            <!-- RESUMO FINANCEIRO -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100">
                    <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-calculator me-2"></i>Resumo Financeiro</h5>
                    <p class="mb-0 fs-5"><strong>Valor Final:</strong> <span class="text-success fw-bold">R$ <?= number_format($os['valor_final'] ?? 0, 2, ',', '.'); ?></span></p>
                </div>
            </div>
        </div>

    </main>
</div>

<!-- POP-UP (MODAL) PARA EDITAR A OS NA MESMA PÁGINA -->
<div class="modal fade" id="modalEditarOS" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle p-3 rounded-3 text-success d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-pen-to-square fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark m-0">Editar Ordem de Serviço</h5>
                        <small class="text-muted">Altere as informações da <?= $codigoOS; ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="salvar_edicao_os.php">
                <input type="hidden" name="origem" value="visualizar">
                <input type="hidden" name="idos" value="<?= $os['idos']; ?>">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Laudo Técnico</label>
                        <textarea name="laudo_tecnico" class="form-control rounded-3" rows="4" placeholder="Insira o laudo do reparo..."><?= htmlspecialchars($os['laudo_tecnico'] ?? ''); ?></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Valor Final (R$)</label>
                            <input type="number" step="0.01" name="valor_final" class="form-control rounded-3" value="<?= $os['valor_final'] ?? '0.00'; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Status da O.S.</label>
                            <select name="status_os" class="form-select rounded-3" required>
                                <option value="Aberta" <?= ($os['status_os'] == 'Aberta') ? 'selected' : ''; ?>>Aberta</option>
                                <option value="Em Andamento" <?= ($os['status_os'] == 'Em Andamento') ? 'selected' : ''; ?>>Em Andamento</option>
                                <option value="Concluído" <?= ($os['status_os'] == 'Concluído') ? 'selected' : ''; ?>>Concluído</option>
                                <option value="Entregue" <?= ($os['status_os'] == 'Entregue') ? 'selected' : ''; ?>>Entregue</option>
                                <option value="Cancelada" <?= ($os['status_os'] == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
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
</body>
</html>