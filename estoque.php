<?php
    session_start();
    include("php/funcoes.php"); // Certifique-se de que o caminho do arquivo de funções está correto aqui

    // Recupera o nome do usuário para registro de logs
    $usuarioLogado = $_SESSION['usuario_nome'] ?? 'Atendente';

    // Captura os filtros individualmente
    $buscaE    = isset($_GET["nBuscaEstoque"]) ? trim($_GET["nBuscaEstoque"]) : "";
    $categoria = isset($_GET["nCategoria"])    ? $_GET["nCategoria"]          : "Todas";
    $status    = isset($_GET["nStatus"])       ? $_GET["nStatus"]             : "Todos";

    $totalPecas   = TotalPecas();
    $estoqueTotal = EstoqueTotal();
    $pecasBaixas  = PecasBaixas();
    $valorTotal   = ValorTotalEstoque();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
        $pagina = 'estoque';
        include ('php/sidebar.php');?>

    <main class="flex-grow-1 p-4 bg-light">

    <?php 
        $tituloPagina = "Estoque";
        $breadcrumb   = "Estoque";
        include 'php/header.php'; 
    ?>
    <?php if (isset($_SESSION['mensagem_erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <?= htmlspecialchars($_SESSION['mensagem_erro']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['mensagem_erro']); // Limpa a mensagem após exibir ?>
    <?php endif; ?>

    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-success px-4 py-2 rounded-3 shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalProduto">
            <i class="fa-solid fa-plus me-2"></i>
            Nova Peça
        </button>
    </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Total de Peças</h6>
                        <h2 class="fw-bold"><?= $totalPecas ?></h2>
                        <small>Peças cadastradas</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Estoque Total</h6>
                        <h2 class="fw-bold"><?= number_format($estoqueTotal, 0, ',', '.') ?></h2>
                        <small>Unidades em estoque</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Peças Baixas</h6>
                        <h2 class="fw-bold text-warning"><?= $pecasBaixas ?></h2>
                        <small>Abaixo do mínimo</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Valor Total</h6>
                        <h2 class="fw-bold text-success">
                            R$ <?= number_format($valorTotal, 2, ',', '.') ?>
                        </h2>
                        <small>Valor do estoque</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <form method="GET" action="estoque.php" id="formFiltros">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Buscar Peça</label>
                            <div class="input-group shadow-sm">
                                <input
                                    type="text"
                                    class="form-control border-success"
                                    placeholder="Buscar peça..."
                                    name="nBuscaEstoque"
                                    value="<?= htmlspecialchars($buscaE) ?>">

                                <button class="btn btn-success px-3" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Categoria</label>
                            <select name="nCategoria" class="form-select select-verde">
                                <option value="Todas" <?= ($categoria == 'Todas') ? 'selected' : '' ?>>Todas</option>
                                <option value="Tela" <?= ($categoria == 'Tela') ? 'selected' : '' ?>>Tela</option>
                                <option value="Bateria" <?= ($categoria == 'Bateria') ? 'selected' : '' ?>>Bateria</option>
                                <option value="Botões" <?= ($categoria == 'Botões') ? 'selected' : '' ?>>Botões</option>
                                <option value="Conectores" <?= ($categoria == 'Conectores') ? 'selected' : '' ?>>Conectores</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="nStatus" class="form-select select-verde">
                                <option value="Todos" <?= ($status == 'Todos') ? 'selected' : '' ?>>Todos</option>
                                <option value="Em estoque" <?= ($status == 'Em estoque') ? 'selected' : '' ?>>Em estoque</option>
                                <option value="Estoque baixo" <?= ($status == 'Estoque baixo') ? 'selected' : '' ?>>Estoque baixo</option>
                            </select>
                        </div>

                        <div class="col-lg-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <a href="estoque.php" class="btn btn-outline-success btn-filtrar flex-grow-1 text-center" title="Limpar">
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
                            <th>ID</th>
                            <th>Peça</th>
                            <th>Categoria</th>
                            <th>Estoque</th>
                            <th>Estoque Mínimo</th>
                            <th>Valor Unitário (R$)</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Passa todas as variáveis para a função realizar o filtro dinâmico
                            echo filtrarEstoque($buscaE, $categoria, $status);
                        ?>
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

    <div class="modal fade" id="modalProduto" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-box-archive me-2"></i>Nova Peça</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="php/salvarEstoque.php?funcao=I" enctype="multipart/form-data">   
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">ID</label>
                                <input type="text" class="form-control" placeholder="0001" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nome da Peça</label>
                                <input type="text" id="iPeca" name="nPeca" class="form-control" placeholder="Digite o nome">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Categoria</label>
                                <select id="iCategoria" name="nCategoria" class="form-select">
                                    <option>Tela</option>
                                    <option>Bateria</option>
                                    <option>Botões</option>
                                    <option>Conectores</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Quantidade</label>
                                <input id="iQuantidade" name="nQuantidade" type="number" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Estoque Mínimo</label>
                                <input type="number" id="iEstoqueMin" name="nEstoqueMin" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Valor Unitário</label>
                                <input type="number" step="0.01" id="iValor" name="nValor" class="form-control">
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Salvar Peça</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>
</body>
</html>