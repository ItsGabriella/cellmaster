<?php
session_start(); // <-- Sempre na primeira linha do arquivo
include("php/funcoes.php");

    $filtro_ativo  = isset($_GET['periodo']) ? $_GET['periodo'] : 'todos';
    $buscaR        = isset($_GET['nBuscaRelatorio']) ? $_GET['nBuscaRelatorio'] : '';
    
    $funcionarios  = ListarFuncionarios();
    $graficoMes    = graficoRelatoriosMes();
    $graficoTipo   = graficoRelatoriosTipo();
    
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
        $pagina = 'relatorio';
        include 'php/sidebar.php'; 
    ?>

    <main class="flex-grow-1 p-4 bg-light">

        <?php 
            $tituloPagina = "Relatório";
            $breadcrumb   = "Relatório";
            include 'php/header.php'; 
        ?>

        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-success px-4 py-2 rounded-3 shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalRelatorio">
                <i class="fa-solid fa-plus me-2"></i>
                Novo relatório
            </button>
        </div>

        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-secondary mb-2">Total de Relatórios</h6>
                                <h2 class="fw-bold mb-1"><?= $totalRelatorios ?></h2>
                                <small class="text-secondary">Cadastrados</small>
                            </div>
                            <div class="icon-circle">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-secondary mb-2">Este mês</h6>
                                <h2 class="fw-bold mb-1"><?= $relatoriosMes ?></h2>
                                <small class="text-secondary">Gerados</small>
                            </div>
                            <div class="icon-circle">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-secondary mb-2">Pendentes</h6>
                                <h2 class="fw-bold mb-1"><?= $pendentes ?></h2>
                                <small class="text-secondary">Aguardando</small>
                            </div>
                            <div class="icon-circle warning">
                                <i class="fa-solid fa-file-circle-exclamation"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-secondary mb-2">Exportados</h6>
                                <h2 class="fw-bold mb-1"><?= $exportados ?></h2>
                                <small class="text-secondary">Downloads</small>
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
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-60">
                    <div class="card-body">
                        <h6 class="fw-bold mb-4 text-center">Relatórios gerados por mês</h6>
                        <div class="chart-container">
                            <canvas id="graficoMes"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-60">
                    <div class="card-body">
                        <h6 class="fw-bold mb-4 text-center">Relatórios por tipo</h6>
                        <div class="chart-container">
                            <canvas id="graficoTipo"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" id="formExportar">
            <div class="row g-4 mb-4">
                
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white p-3">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h6 class="mb-0 fw-bold">Relatórios Recentes</h6>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <input
                                            type="text"
                                            class="form-control border-success"
                                            placeholder="Buscar relatório..."
                                            name="nBuscaRelatorio"
                                            value="<?= htmlspecialchars($buscaR) ?>">
                                        <button class="btn btn-success" type="submit" formaction="relatorio.php" formmethod="GET">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </div>

                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label text-nowrap" for="selectAll">
                                            Selecionar todos
                                        </label>
                                    </div>
                                </div>
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
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php 
                                            // Passa os filtros ativos para a função
                                            echo listaRelatorio($filtro_ativo, $buscaR); 
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
                                    <button 
                                            type="submit"
                                            onclick="exportarSeparados('php/exportarPdf.php')" 
                                            class="btn btn-outline-danger d-flex align-items-center justify-content-center fw-bold export-btn w-100" 
                                            style="height: 38px;">
                                        <i class="fa-solid fa-file-pdf me-2"></i> PDF
                                    </button>
                                    <span class="text-secondary" style="font-size: 0.85rem;">Exportar em PDF</span>
                                </div>
                            </div>

                            <div class="card border-light shadow-sm mb-2">
                                <div class="card-body exportacao-card py-2 px-3">
                                    <button type="submit" 
                                            onclick="exportarSeparados('php/exportarExcel.php')"
                                            class="btn btn-outline-success d-flex align-items-center justify-content-center fw-bold export-btn w-100" 
                                            style="height: 38px;">
                                        <i class="fa-solid fa-file-excel me-2"></i> Excel
                                    </button>
                                    <span class="text-secondary" style="font-size: 0.85rem;">Exportar em Excel</span>
                                </div>
                            </div>

                            <div class="card border-light shadow-sm mb-4">
                                <div class="card-body exportacao-card py-2 px-3">
                                    <button type="submit" 
                                            onclick="exportarSeparados('php/exportarCsv.php')"
                                            class="btn btn-outline-primary d-flex align-items-center justify-content-center fw-bold export-btn w-100" 
                                            style="height: 38px;">
                                        <i class="fa-solid fa-file-csv me-2"></i> CSV
                                    </button>
                                    <span class="text-secondary" style="font-size: 0.85rem;">Exportar em CSV</span>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-2 text-secondary" style="font-size: 1.1rem;">
                                Período do Relatório
                            </h5>
                            <div class="d-flex flex-wrap gap-2">

                                <a href="?periodo=hoje<?= !empty($buscaR) ? '&nBuscaRelatorio='.urlencode($buscaR) : '' ?>" 
                                class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == 'hoje' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                                style="<?= $filtro_ativo == 'hoje' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                                    Hoje
                                </a>

                                <a href="?periodo=7_dias<?= !empty($buscaR) ? '&nBuscaRelatorio='.urlencode($buscaR) : '' ?>" 
                                class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == '7_dias' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                                style="<?= $filtro_ativo == '7_dias' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                                    Últimos 7 dias
                                </a>

                                <a href="?periodo=este_mes<?= !empty($buscaR) ? '&nBuscaRelatorio='.urlencode($buscaR) : '' ?>" 
                                class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == 'este_mes' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                                style="<?= $filtro_ativo == 'este_mes' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                                    Este mês
                                </a>

                                <a href="?periodo=ultimo_mes<?= !empty($buscaR) ? '&nBuscaRelatorio='.urlencode($buscaR) : '' ?>" 
                                class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == 'ultimo_mes' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                                style="<?= $filtro_ativo == 'ultimo_mes' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                                    Último mês
                                </a>

                                <a href="?periodo=todos<?= !empty($buscaR) ? '&nBuscaRelatorio='.urlencode($buscaR) : '' ?>" 
                                class="btn btn-sm px-3 py-2 fw-semibold <?= $filtro_ativo == 'todos' ? 'btn-success border-0' : 'btn-outline-secondary bg-white text-dark border-light shadow-sm' ?>"
                                style="<?= $filtro_ativo == 'todos' ? 'background-color: #e2f6e9; color: #157347;' : '' ?>">
                                    Todos
                                </a>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal fade" id="modalRelatorio" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-box-archive me-2"></i>
                            Novo Relatório
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form method="POST" action="php/salvarRelatorio.php?funcao=I" enctype="multipart/form-data">   
                            <div class="row g-3">

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">ID</label>
                                    <input type="text" class="form-control" placeholder="0001" readonly>
                                </div>

                                <div class="col-md-9">
                                    <label class="form-label fw-semibold">Relatório</label>
                                    <input type="text" id="iRelatorio" name="nRelatorio" class="form-control" placeholder="Digite o nome do relatório" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tipo</label>
                                    <select id="iTipo" name="nTipo" class="form-select">
                                        <option>Clientes</option> 
                                        <option>Funcionários</option> 
                                        <option>Serviços</option> 
                                        <option>Estoque</option> 
                                        <option>Orçamento</option> 
                                        <option>Ordem de Serviço</option> 
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Data de Geração</label>
                                    <input id="iData" name="nData" type="date" class="form-control" value="<?= date('Y-m-d') ?>" required readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Período Início (De)</label>
                                    <input id="iDataInicio" name="nDataInicio" type="date" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Período Fim (Até)</label>
                                    <input id="iDataFim" name="nDataFim" type="date" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Responsável</label>
                                    <select name="nResponsavel" id="iResponsavel" class="form-select" required>
                                        <option value="">Selecione um funcionário</option>
                                        <?php while($funcionario = mysqli_fetch_assoc($funcionarios)){ ?>
                                            <option><?= $funcionario["nome_func"] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status</label>
                                    <input type="text" id="iStatus" name="nStatus" class="form-control" value="Pendente" readonly>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                Salvar Relatório
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/home.js"></script>

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
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#ececec' } },
                x: { grid: { display: false } }
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
                backgroundColor: ['#198754', '#0d6efd', '#6f42c1', '#fd7e14', '#ffc107'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '65%',
            plugins: { legend: { position: 'right' } }
        }
    });

    // Script Selecionar Todos
    document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAll');
    const reportCheckboxes = document.querySelectorAll('.checkbox-relatorio');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            reportCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        reportCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const allChecked = Array.from(reportCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });
    }
});

function exportarSeparados(urlDestino) {
    // Busca todas as checkboxes marcadas na tabela
    const marcados = document.querySelectorAll('.checkbox-relatorio:checked');

    if (marcados.length === 0) {
        alert('Por favor, selecione pelo menos um relatório para exportar.');
        return;
    }

    // Para cada item selecionado, cria e envia um formulário separado
    marcados.forEach(checkbox => {
        const idRelatorio = checkbox.value;

        // Cria o formulário temporário em memória
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = urlDestino;
        form.target = '_blank'; // Abre em nova aba / dispara o download individual

        // Cria o campo com o ID do relatório
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'relatorios[]';
        input.value = idRelatorio;

        form.appendChild(input);
        document.body.appendChild(form);

        // Submete o formulário e depois o remove do DOM
        form.submit();
        document.body.removeChild(form);
    });
}
    </script>
</body>
</html>