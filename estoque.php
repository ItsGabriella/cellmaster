<?php
    include ('php/funcoes.php');



    $buscaE = "";

    if(isset($_GET["nBuscaEstoque"]))
    {
        $buscaE = $_GET["nBuscaEstoque"];
    }

    $totalPecas = TotalPecas();
    $estoqueTotal = EstoqueTotal();
    $pecasBaixas = PecasBaixas();
    $valorTotal = ValorTotalEstoque();


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <!-- Seu CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
        $pagina = 'estoque';
        include 'php/sidebar.php'; ?>

        <!-- Conteúdo -->
    <main class="flex-grow-1 p-4 bg-light">

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h3 class="fw-bold mb-1">
                            Estoque
                        </h3>

                        <nav style="--bs-breadcrumb-divider: '>';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="#" class="text-success text-decoration-none">
                                        Home
                                    </a>
                                </li>

                                <li class="breadcrumb-item active">
                                    Estoque
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <button class="btn btn-success px-4 py-2"
                        data-bs-toggle="modal"
                        data-bs-target="#modalProduto">
                        <i class="fa-solid fa-plus me-2"></i>
                        Nova Peça
                    </button>

                </div>

            </div>

        </div>
        <!-- Cards -->
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

        <!-- Tabela -->
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white p-4">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Buscar Peça
                        </label>

                    <form method="GET" action="estoque.php">

                        <div class="input-group shadow-sm">

                            <input
                                type="text"
                                class="form-control border-success"
                                placeholder="Buscar peça..."
                                name= "nBuscaEstoque"
                                value="<?= $buscaE ?>">
                            

                            <button
                                class="btn btn-success px-4"
                                type="submit">
                                <i class="fa-solid fa-magnifying-glass me-0"></i>
                                
                            </button>

                        </div>

                    </form>
                </div>

                    <!-- Categoria -->
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">
                            Categoria
                        </label>

                        <select class="form-select select-verde">
                            <option selected>Todas</option>
                            <option>Tela</option>
                            <option>Bateria</option>
                            <option>Botões</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select class="form-select select-verde">
                            <option selected>Todos</option>
                            <option>Em estoque</option>
                            <option>Estoque baixo</option>
                            <option>Esgotado</option>
                        </select>
                    </div>

                    <!-- Botões -->
                <div class="col-lg-auto d-flex align-items-end">

                    <div class="col-lg-3"">

                        <button
                            class="btn btn-outline-success btn-filtro"
                            title="Limpar">

                            <i class="fa-solid fa-rotate-left"></i>

                        </button>

                        <button
                            class="btn btn-success btn-filtrar">

                            <i class="fa-solid fa-filter"></i>

                            Filtrar

                        </button>

                    </div>

                    </div>

                </div>

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

        if($buscaE == "")
        {
            echo listaProduto();
        }
        else
        {
            echo BuscarEstoque($buscaE);
        }

        ?>
        
<!-- Modal Novo Produto -->
<div class="modal fade" id="modalProduto" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <!-- Cabeçalho -->
            <div class="modal-header bg-success text-white">

                <h5 class="modal-title">
                    <i class="fa-solid fa-box-archive me-2"></i>
                    Nova Peça
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- Corpo -->
            <div class="modal-body">

                <form method="POST" action="php/salvarEstoque.php?funcao=I" enctype="multipart/form-data">   

                    <div class="row g-3">

                    <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                ID
                            </label>

                            <input type="text"
                                class="form-control"
                                placeholder="0001" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Nome da Peça
                            </label>

                            <input type="text"
                                id="iPeca" name="nPeca"
                                class="form-control"
                                placeholder="Digite o nome">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                Categoria
                            </label>

                            <select id="iCategoria" name="nCategoria" class="form-select">
                                <option>Tela</option>
                                <option>Bateria</option>
                                <option>Botões</option>
                                <option>Conectores</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Quantidade
                            </label>

                            <input id="iQuantidade" name="nQuantidade" type="number"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Estoque Mínimo
                            </label>

                            <input type="number" id="iEstoqueMin" name="nEstoqueMin"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Valor Unitário
                            </label>

                            <input type="number"
                                step="0.01"
                                id="iValor" name="nValor"
                                class="form-control">
                        </div>


                </div>

            </div>

            <!-- Rodapé -->
            <div class="modal-footer">

                <button class="btn btn-outline-danger"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    Salvar Peça
                </button>

            </div>

        </form>

        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/home.js"></script>

</body>
</html>