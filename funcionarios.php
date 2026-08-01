<?php
include("php/funcoes.php");

$busca = "";

if (isset($_GET["nBusca"])) {
    $busca = $_GET["nBusca"];
}

// Adapte/Crie estas funções no seu funcoes.php se desejar valores dinâmicos
$totalFuncionarios = function_exists('TotalFuncionarios') ? TotalFuncionarios() : 0;
$tecnicos          = function_exists('TotalTecnicos') ? TotalTecnicos() : 0;
$atendentes        = function_exists('TotalAtendentes') ? TotalAtendentes() : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
    $pagina = 'funcionarios';
    include 'php/sidebar.php'; 
    ?>

    <main class="flex-grow-1 p-4 bg-light">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">Funcionários</h3>
                        <nav style="--bs-breadcrumb-divider: '>';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="#" class="text-success text-decoration-none">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Funcionários</li>
                            </ol>
                        </nav>
                    </div>

                    <button class="btn btn-success px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalFuncionario">
                        <i class="fa-solid fa-plus me-2"></i>
                        Novo Funcionário
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Total de Funcionários</h6>
                        <h2 class="fw-bold"><?= $totalFuncionarios ?></h2>
                        <small>Equipe cadastrada</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Técnicos</h6>
                        <h2 class="fw-bold text-success"><?= $tecnicos ?></h2>
                        <small>Equipe técnica</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Atendentes / Outros</h6>
                        <h2 class="fw-bold text-primary"><?= $atendentes ?></h2>
                        <small>Atendimento e gestão</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <form method="GET" action="funcionarios.php">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Buscar Funcionário</label>
                            <div class="input-group shadow-sm">
                                <input type="text" class="form-control border-success" placeholder="Buscar por nome, cargo ou e-mail..." name="nBusca" value="<?= $busca ?>">
                                <button class="btn btn-success px-4" type="submit">
                                    <i class="fa-solid fa-magnifying-glass me-0"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Cargo</label>
                            <select class="form-select select-verde">
                                <option selected>Todos</option>
                                <option>Gerente</option>
                                <option>Técnico</option>
                                <option>Atendente</option>
                            </select>
                        </div>

                        <div class="col-lg-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <a href="funcionarios.php" class="btn btn-outline-success btn-filtrar flex-grow-1 text-center" title="Limpar">
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
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($busca == "") {
                            echo listaFuncionario();
                        } else {
                            echo BuscarFuncionario($busca);
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white py-3">
                <nav>
                    <ul class="pagination justify-content-end mb-0">
                        <li class="page-item disabled">
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

    <div class="modal fade" id="modalFuncionario" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-user-gear me-2"></i>
                        Novo Funcionário
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="php/salvarFuncionario.php?funcao=I" enctype="multipart/form-data">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nome do Funcionário</label>
                                <input type="text" class="form-control nome" id="iFuncionario" name="nFuncionario" placeholder="Digite o nome do funcionário" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cargo</label>
                                <select class="form-select cargo" id="icargo" name="ncargo" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">1 - Gerente</option>
                                    <option value="2">2 - Técnico</option>
                                    <option value="3">3 - Atendente</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Telefone</label>
                                <input type="text" class="form-control telefone" name="nTelefone" placeholder="(99) 99999-9999" maxlength="15" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">E-mail</label>
                                <input type="email" class="form-control" id="imail" name="nmail" placeholder="Digite o e-mail" required>
                            </div>
                        </div>

                        <div class="modal-footer mt-4 px-0 pb-0">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                Salvar Funcionário
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Máscara Telefone
            const telefones = document.querySelectorAll(".telefone");
            telefones.forEach(function(input) {
                input.addEventListener("input", function() {
                    let valor = this.value.replace(/\D/g, "");
                    valor = valor.substring(0, 11);

                    if (valor.length > 10) {
                        valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
                    } else {
                        valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4})$/, "($1) $2-$3");
                    }
                    this.value = valor;
                });
            });

            // Permite apenas letras e espaços no nome
            const nomes = document.querySelectorAll(".nome");
            nomes.forEach(function(input) {
                input.addEventListener("input", function() {
                    this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, "");
                });
            });
        });
    </script>

</body>

</html>