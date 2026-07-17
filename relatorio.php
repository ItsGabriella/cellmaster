<?php 

    include("php/funcaoRelatorio.php");
    $funcionarios = ListarFuncionarios();
    $graficoMes = graficoRelatoriosMes();
    $graficoTipo = graficoRelatoriosTipo();
    

    $totalRelatorios = TotalRelatorios();
    $relatoriosMes   = RelatoriosMes();
    $pendentes       = RelatoriosPendentes();
    $exportados      = RelatoriosExportados();



?>

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

        <button class="btn btn-success px-4 py-2"
        data-bs-toggle="modal"
        data-bs-target="#modalRelatorio">
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
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    Relatórios Recentes
                </h6>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label" for="selectAll">
                        Selecionar todos
                    </label>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th></th>
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
                            <?php echo listaRelatorio(); ?>
                        </tbody>

                        <tbody>
                            <?php 
                                // Passa o filtro ativo para a função
                                echo listaRelatorio($filtro_ativo); 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                
                <h5 class="fw-bold mb-3 text-secondary" style="font-size: 1.1rem;">
                    Exportações Rápidas
                </h5>

                <div class="card border-light shadow-sm mb-2">
                    <div class="card-body exportacao-card py-2 px-3">
                        <a href="php/exportarPdf.php" target="_blank" class="btn btn-outline-danger d-flex align-items-center justify-content-center fw-bold export-btn" style="height: 38px; text-decoration: none;">
                            <i class="fa-solid fa-file-pdf me-2"></i> PDF
                        </a>
                        <span class="text-secondary" style="font-size: 0.85rem;">Exportar em PDF</span>
                    </div>
                </div>

                <div class="card border-light shadow-sm mb-2">
                    <div class="card-body exportacao-card py-2 px-3">
                        <button type="submit" class="btn btn-outline-success d-flex align-items-center justify-content-center fw-bold export-btn" style="height: 38px;">
                            <i class="fa-solid fa-file-excel me-2"></i> Excel
                        </button>
                        <span class="text-secondary" style="font-size: 0.85rem;">Exportar em Excel</span>
                    </div>
                </div>

                <div class="card border-light shadow-sm mb-4">
                    <div class="card-body exportacao-card py-2 px-3">
                        <button type="submit" class="btn btn-outline-primary d-flex align-items-center justify-content-center fw-bold export-btn" style="height: 38px;">
                            <i class="fa-solid fa-file-csv me-2"></i> CSV
                        </button>
                        <span class="text-secondary" style="font-size: 0.85rem;">Exportar em CSV</span>
                    </div>
                </div>

                <h5 class="fw-bold mb-2 text-secondary" style="font-size: 1.1rem;">
                    Período do Relatório
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php 
                    // Identifica qual filtro está ativo na URL (se não houver nenhum, o padrão será 'este_mes')
                    $filtro_ativo = isset($_GET['periodo']) ? $_GET['periodo'] : 'este_mes'; 
                    ?>

                    <a href="?periodo=hoje" 
                    class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == 'hoje' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                    style="<?= $filtro_ativo == 'hoje' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                        Hoje
                    </a>

                    <a href="?periodo=7_dias" 
                    class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == '7_dias' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                    style="<?= $filtro_ativo == '7_dias' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                        Últimos 7 dias
                    </a>

                    <a href="?periodo=este_mes" 
                    class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == 'este_mes' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                    style="<?= $filtro_ativo == 'este_mes' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                        Este mês
                    </a>

                    <a href="?periodo=ultimo_mes" 
                    class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == 'ultimo_mes' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                    style="<?= $filtro_ativo == 'ultimo_mes' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                        Último mês
                    </a>
                </div>

            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>





    <div class="modal fade" id="modalRelatorio" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <!-- Cabeçalho -->
            <div class="modal-header bg-success text-white">

                <h5 class="modal-title">
                    <i class="fa-solid fa-box-archive me-2"></i>
                    Novo Relatório
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- Corpo -->
            <div class="modal-body">

                <form method="POST" action="php/salvarRelatorio.php?funcao=I" enctype="multipart/form-data">   

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
                                    Tipo
                                </label>

                                <select id="iTipo" name="nTipo" class="form-select">
                                    <option>Clientes</option> 
                                    <option>Funcionários</option> 
                                    <option>Serviços</option> 
                                    <option>Estoque</option> 
                                    <option>Orçamento</option> 
                                    <option>Ordem de Serviço</option> 
                                </select>
                            </div>


                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Data
                            </label>

                            <input id="iData" name="nData" type="date"
                                class="form-control">
                        </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Responsável</label>

                                <select name="nResponsavel" id="iResponsavel" class="form-select w-200" required >

                                    <option value="">Selecione um funcionário</option>

                                    <?php while($funcionario = mysqli_fetch_assoc($funcionarios)){ ?>

                                        <option>
                                            <?= $funcionario["nome_func"] ?>
                                        </option>

                                    <?php } ?>

                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Status
                                </label>

                                <select id="iStatus" name="nStatus" class="form-select">
                                    <option>Pendente</option> <!--Relatório ainda não foi iniciado. -->
                                    <option>Em andamento</option> <!--Está sendo elaborado. -->
                                    <option>Concluído</option> <!--Foi finalizado. -->
                                    <option>Enviado</option> <!--Foi enviado ao cliente ou gerente. -->
                                    <option>Arquivado</option> <!--Não será mais alterado. -->
                                    <option>Cancelado</option> <!--Relatório foi cancelado. -->
                                </select>
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
        labels: <?= json_encode($graficoMes["meses"]) ?>,

        datasets: [{
            data: <?= json_encode($graficoMes["totais"]) ?>,
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
        labels: <?= json_encode($graficoTipo["tipos"]) ?>,

        datasets: [{
            data: <?= json_encode($graficoTipo["quantidades"]) ?>,
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

// Aguarda o carregamento do DOM
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAll');
    
    // Seleciona todos os checkboxes que têm a classe 'checkbox-relatorio'
    const reportCheckboxes = document.querySelectorAll('.checkbox-relatorio');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            // Define o estado de todos os checkboxes individuais igual ao do "Selecionar todos"
            reportCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        // Opcional: Se o usuário desmarcar um item manualmente, desmarca o "Selecionar todos"
        reportCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const allChecked = Array.from(reportCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });
    }
});



</script>

</body>
</html>