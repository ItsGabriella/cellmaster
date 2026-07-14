<?php

include("php/funcoes.php");



    $buscaS = "";

    if(isset($_GET["nBuscaServico"]))
    {
        $buscaS = $_GET["nBuscaServico"];
    }



    $totalServicos = TotalServicos();
    $servicosAtivos = ServicosAtivos();
    $servicosInativos = ServicosInativos();
    $valorMedio = ValorMedioServico();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
    $pagina = 'servicos';
    include 'php/sidebar.php'; ?>


    <main class="flex-grow-1 p-4 bg-light">

    <div class="card border-0 shadow-sm rounded-4 mb-4">

<div class="card-body p-4">

    <div class="d-flex justify-content-between align-items-center">

        <div>
            <h3 class="fw-bold mb-1">
                Serviços
            </h3>

            <nav style="--bs-breadcrumb-divider: '>';">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-success text-decoration-none">
                            Home
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Serviços
                    </li>
                </ol>
            </nav>
        </div>

        <button class="btn btn-success px-4 py-2"
        data-bs-toggle="modal"
        data-bs-target="#modalServico">
            <i class="fa-solid fa-plus me-2"></i>
            Novo Serviço
        </button>

    </div>

</div>

</div>

    <div class="row g-4 mb-4">

<!-- Total de Serviços -->
        <div class="col-md-3">
            <div class="card dashboard-card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-secondary mb-2">
                                Total de Serviços
                            </h6>

                            <h2 class="fw-bold mb-1">
                                <?= $totalServicos ?>
                            </h2>

                            <small class="text-secondary">
                                Cadastrados
                            </small>
                        </div>

                        <div class="icon-circle">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- Serviços Ativos -->
        <div class="col-md-3">
            <div class="card dashboard-card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-secondary mb-2">
                                Serviços Ativos
                            </h6>

                            <h2 class="fw-bold mb-1">
                                <?= $servicosAtivos ?>
                            </h2>

                            <small class="text-secondary">
                                Disponíveis
                            </small>
                        </div>

                        <div class="icon-circle">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- Serviços Inativos -->
        <div class="col-md-3">
            <div class="card dashboard-card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-secondary mb-2">
                                Serviços Inativos
                            </h6>

                            <h2 class="fw-bold mb-1">
                                <?= $servicosInativos ?>
                            </h2>

                            <small class="text-secondary">
                                Desativados
                            </small>
                        </div>

                        <div class="icon-circle warning">
                            <i class="fa-solid fa-circle-pause"></i>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- Valor Médio -->
        <div class="col-md-3">
            <div class="card dashboard-card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-secondary mb-2">
                                Valor Médio
                            </h6>

                            <h2 class="fw-bold mb-1">
                                R$ <?= number_format($valorMedio, 2, ',', '.') ?>
                            </h2>

                            <small class="text-secondary">
                                Por serviço
                            </small>
                        </div>

                        <div class="icon-circle">
                            <i class="fa-solid fa-dollar-sign"></i>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white p-4">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Buscar Serviço
                        </label>

                    <form method="GET" action="servicos.php">

                        <div class="input-group">

                            <input
                                type="text"
                                class="form-control"
                                name="nBuscaServico"
                                placeholder="Buscar serviço..."
                                value="<?= $buscaS ?>">

                            <button class="btn btn-success" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>

                        </div>

                    </form>

                    </form>
                    </div>




                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select class="form-select select-verde">
                            <option selected>Ativo</option>
                            <option>Inativo</option>
                        </select>
                    </div>


                <div class="col-lg-auto d-flex align-items-end">

                    <div class="d-flex gap-2">

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
                            <th>Serviço</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Tempo estimado</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>

                    </thead>



            <div class="card-footer bg-white">

                <nav>

                    <ul class="pagination justify-content-end mb-0">

                        <li class="page-item">
                            <a class="page-link" href="#">Anterior</a>
                        </li>

                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#">Próximo</a>
                        </li>

                    </ul>

                </nav>

            </div>

        </div>

    </main>

    </div>

    <?php

    if($buscaS == "")
    {
        echo listaServico();
    }
    else
    {
        echo BuscarServico($buscaS);
    }

    ?>

    <!-- Modal Novo Produto -->
<div class="modal fade" id="modalServico" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <!-- Cabeçalho -->
            <div class="modal-header bg-success text-white">

                <h5 class="modal-title">
                    <i class="fa-solid fa-box-archive me-2"></i>
                    Novo Serviço
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

        <!-- Corpo -->
        <div class="modal-body">
            <form method="POST" action="php/salvarServico.php?funcao=I" enctype="multipart/form-data">  

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nome do Serviço
                        </label>

                        <input type="text"
                            class="form-control"
                            id="iServico" name="nServico"
                            placeholder="Digite o nome do serviço">
                    </div>



                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Código
                        </label>

                        <input type="text"
                            class="form-control"
                            placeholder="0001"
                            readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Valor
                        </label>

                        <input type="number"
                            id="iValor" name="nValor"
                            step="0.01"
                            class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Tempo Estimado
                        </label>

                        <input type="time"
                            id="iTempo" name="nTempo"
                            class="form-control">
                    </div>

                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select class="form-select" id="iStatus" name="nStatus">
                            <option>Ativo</option>
                            <option>Inativo</option>
                        </select>
                    </div>

                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Descrição
                        </label>

                        <textarea class="form-control"
                                id="iDescricao" name="nDescricao"
                                rows="4"></textarea>
                    </div>

                </div>

            </div>

            <!-- Rodapé -->
            <div class="modal-footer">

                <button class="btn btn-outline-danger"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button class="btn btn-success" type="submit">
                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    Salvar Serviço
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