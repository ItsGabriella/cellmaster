

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    
</head>

<body class="bg-custom">

    <?php 
        $pagina = 'relatorio';
        include 'php/sidebar.php'; ?>


    <main class="flex-grow-1 p-4 bg-light">

    <div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

    <div class="d-flex justify-content-between align-items-center">

        <div>
            <h3 class="fw-bold mb-1">
                Relatório
            </h3>

            <nav style="--bs-breadcrumb-divider: '>';">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-success text-decoration-none">
                            Home
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Relatório
                    </li>
                </ol>
            </nav>
        </div>

        <button class="btn btn-success px-4 py-2">
            <i class="fa-solid fa-plus me-2"></i>
            Novo Relatório
        </button>

    </div>

    </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card dashboard-card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-secondary mb-2">
                                Total de Relatórios
                            </h6>

                        <h2 class="fw-bold mb-1">
                            <?= $totalRelatorios ?>
                        </h2>

                            <small class="text-secondary">
                                Cadastrados
                            </small>
                        </div>

                        <div class="icon-circle">
                            <i class="fa-solid fa-file-contract"></i>
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
                                Este mês
                            </h6>

                            <h2 class="fw-bold mb-1">
                                <?= $relatoriosMes ?>
                            </h2>

                            <small class="text-secondary">
                                Gerados
                            </small>
                        </div>

                        <div class="icon-circle">
                            <i class="fa-solid fa-file-circle-check"></i>
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
                                Pendentes
                            </h6>

                            <h2 class="fw-bold mb-1">
                                <?= $pendentes ?>
                            </h2>

                            <small class="text-secondary">
                                Aguardando
                            </small>
                        </div>

                        <div class="icon-circle warning">
                            <i class="fa-solid fa-file-circle-exclamation"></i>
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

                            <h2 class="fw-bold mb-1">
                                <?= $exportados ?>
                            </h2>

                            <h2 class="fw-bold mb-1">
                                190
                            </h2>

                            <small class="text-secondary">
                                Downloads
                            </small>
                        </div>

                        <div class="icon-circle">
                            <i class="fa-solid fa-file-zipper"></i>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        </div>

        <div class="row g-4 mb-4">

    <!-- Gráfico de Barras -->


    <!-- Gráfico de Barras -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-60">
            <div class="card-body">

                <h6 class="fw-bold mb-4 text-center">
                    Relatórios gerados por mês
                </h6>

                <div class="chart-container">
                    <canvas id="graficoMes"></canvas>
                </div>

            </div>
        </div>
    </div>

    <!-- Gráfico Donut -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-60">
            <div class="card-body">

                <h6 class="fw-bold mb-4 text-center">
                    Relatórios por tipo
                </h6>

                <div class="chart-container">
                    <canvas id="graficoTipo"></canvas>
                </div>

            </div>
        </div>
    </div>

</div>


    <div class="row g-4 mb-4">
        <!-- Tabela -->
        <div class="card border-0 shadow-sm" style="width:66%; ">

            <div class="card-header bg-white p-4">
                <h6>
                    Relatórios Recentes
                </h6>

                <div class="row g-1 align-items-end">


            <div class="table-responsive">

               <table class="table table-sm table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Relatório</th>
                            <th>Tipo</th>
                            <th>Data</th>
                            <th>Responsável</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <td>1</td>
                            <td>Estoque Mensal</td>
                            <td>Estoque</td>
                            <td>10/06/2026</td>
                            <td>João Silva</td>
                            <td>
                                <span class="badge text-bg-success">Concluído</span>
                            </td>

                            <td>
                                <button class="btn btn-success btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalExcluir">
                                <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                        <td>2</td>
                            <td>Estoque Mensal</td>
                            <td>Estoque</td>
                            <td>10/06/2026</td>
                            <td>João Silva</td>
                            <td>
                                <span class="badge text-bg-success">Concluído</span>
                            </td>

                            <td>
                                <button class="btn btn-success btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalExcluir">
                                <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                        <td>3</td>
                            <td>Estoque Mensal</td>
                            <td>Estoque</td>
                            <td>10/06/2026</td>
                            <td>João Silva</td>
                            <td>
                                <span class="badge text-bg-success">Concluído</span>
                            </td>
                            
                            
                            <td>
                                <button class="btn btn-success btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalExcluir">
                                <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        

            </div>
            

        </div>
        
    </div>

    <div class="col-lg-4">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
            
            <h5 class="fw-bold mb-3 text-secondary" style="font-size: 1.1rem;">
                Exportações Rápidas
            </h5>

            <div class="card border-light shadow-sm mb-3">
                <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
                    <button class="btn btn-outline-danger d-flex align-items-center justify-content-center fw-bold" style="width: 85px; height: 38px;">
                        <i class="fa-solid fa-file-pdf me-2"></i> PDF
                    </button>
                    <span class="text-secondary" style="font-size: 0.9rem;">Exportar relatório em PDF</span>
                </div>
            </div>

            <div class="card border-light shadow-sm mb-3">
                <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
                    <button class="btn btn-outline-success d-flex align-items-center justify-content-center fw-bold" style="width: 85px; height: 38px;">
                        <i class="fa-solid fa-file-excel me-2"></i> Excel
                    </button>
                    <span class="text-secondary" style="font-size: 0.9rem;">Exportar relatório em Excel</span>
                </div>
            </div>

            <div class="card border-light shadow-sm mb-4">
                <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
                    <button class="btn btn-outline-primary d-flex align-items-center justify-content-center fw-bold" style="width: 85px; height: 38px;">
                        <i class="fa-solid fa-file-csv me-2"></i> CSV
                    </button>
                    <span class="text-secondary" style="font-size: 0.9rem;">Exportar relatório em CSV</span>
                </div>
            </div>

            <h5 class="fw-bold mb-3 text-secondary" style="font-size: 1.1rem;">
                Filtros Rápidos
            </h5>
            
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-outline-secondary btn-sm bg-white text-dark border-light shadow-sm px-3 py-2">
                    Hoje
                </button>
                <button class="btn btn-outline-secondary btn-sm bg-white text-dark border-light shadow-sm px-3 py-2">
                    Últimos 7 dias
                </button>
                <button class="btn btn-success btn-sm border-0 px-3 py-2 fw-semibold" style="background-color: #e2f6e9; color: #157347;">
                    Este mês
                </button>
                <button class="btn btn-outline-secondary btn-sm bg-white text-dark border-light shadow-sm px-3 py-2">
                    Último mês
                </button>
            </div>

        </div>
    </div>
</div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>





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

            <form method="POST" action="php/salvarRelatório.php?funcao=I" enctype="multipart/form-data">   

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
                            Relatório
                        </label>

                        <input type="text"
                            id="iRelatorio" name="nRelatorio"
                            class="form-control"
                            placeholder="Digite o nome do relatório">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Relatório
                        </label>

                        <input type="text"
                            id="iRelatorio" name="nRelatorio"
                            class="form-control"
                            placeholder="Digite o nome do relatório">
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
                Salvar Relatório
            </button>

        </div>

    </form>

    </div>

</div>

</div>


<script>

// Gráfico de Barras
new Chart(document.getElementById('graficoMes'), {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
            data: [12, 18, 25, 22, 28, 36],
            backgroundColor: '#198754',
            borderRadius: 8,
            maxBarThickness: 35
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#ececec'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Gráfico Donut
new Chart(document.getElementById('graficoTipo'), {
    type: 'doughnut',
    data: {
        labels: [
            'Estoque',
            'Serviços',
            'Financeiro',
            'Clientes',
            'Orçamentos'
        ],
        datasets: [{
            data: [35, 30, 20, 10, 5],
            backgroundColor: [
                '#198754',
                '#0d6efd',
                '#6f42c1',
                '#fd7e14',
                '#ffc107'
            ],
            borderWidth: 0
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: {
                position: 'right'
            }
        }
    }
});

</script>

</body>
</html>