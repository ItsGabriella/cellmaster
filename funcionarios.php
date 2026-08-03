<?php
session_start();
include("php/funcoes.php"); // Ou funcaoFuncionario.php

$busca = isset($_GET["nBusca"]) ? trim($_GET["nBusca"]) : "";
$cargo = isset($_GET["nCargo"]) ? $_GET["nCargo"] : "Todos";

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
        include ('php/sidebar.php');
    ?>

    <main class="flex-grow-1 p-4 bg-light">

        <?php 
            $tituloPagina = "Funcionários";
            $breadcrumb = "Funcionários";
            include 'php/header.php'; 
        ?>

        <?php if (isset($_SESSION['mensagem_erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <?= $_SESSION['mensagem_erro']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensagem_erro']); ?>
        <?php endif; ?>

        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-success px-4 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFuncionario">
                <i class="fa-solid fa-user-plus me-2"></i>
                Novo Funcionário
            </button>
        </div>

        

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Total de Funcionários</h6>
                        <h2 class="fw-bold"><?= $totalFuncionarios ?></h2>
                        <small>Ativos na empresa</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Técnicos</h6>
                        <h2 class="fw-bold text-success"><?= $tecnicos ?></h2>
                        <small>Manutenção</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Atendentes</h6>
                        <h2 class="fw-bold text-primary"><?= $atendentes ?></h2>
                        <small>Recepção</small>
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
                                <input type="text" class="form-control border-success" placeholder="Buscar por nome ou e-mail..." name="nBusca" value="<?= htmlspecialchars($busca) ?>">
                                <button class="btn btn-success px-4" type="submit">
                                    <i class="fa-solid fa-magnifying-glass me-0"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Cargo</label>
                            <select name="nCargo" class="form-select select-verde">
                                <option value="Todos" <?= ($cargo == 'Todos') ? 'selected' : '' ?>>Todos</option>
                                <option value="1" <?= ($cargo == '1') ? 'selected' : '' ?>>Gerente</option>
                                <option value="2" <?= ($cargo == '2') ? 'selected' : '' ?>>Técnico</option>
                                <option value="3" <?= ($cargo == '3') ? 'selected' : '' ?>>Atendente</option>
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
                            <th>Data de Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            echo filtrarFuncionarios($busca, $cargo);
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
                        <i class="fa-solid fa-user-plus me-2"></i>
                        Novo Funcionário
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="php/salvarFuncionario.php?funcao=I">

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">ID</label>
                                <input type="text" class="form-control" placeholder="0001" readonly>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Nome Completo</label>
                                <input type="text" id="iFuncionario" name="nFuncionario" class="form-control nome" placeholder="Digite o nome" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Cargo</label>
                                <select id="iCargo" name="nCargo" class="form-select" required>
                                    <option value="1">1 - Gerente</option>
                                    <option value="2">2 - Técnico</option>
                                    <option value="3">3 - Atendente</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Telefone</label>
                                <input type="text" id="iTelefone" name="nTelefone" class="form-control telefone" placeholder="(00) 00000-0000" maxlength="15" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">E-mail</label>
                                <input type="email" id="iEmail" name="nmail" class="form-control" placeholder="email@exemplo.com" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Senha Padrão</label>
                                <input type="text" id="iSenha" name="nSenha" class="form-control" value="Cellmaster123" required>
                                <small class="text-muted">Senha temporária sugerida</small>
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