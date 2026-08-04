<?php
session_start(); // <-- Sempre na primeira linha do arquivo
include("php/funcoes.php");

$busca = "";

if (isset($_GET["nBusca"])) {
    $busca = $_GET["nBusca"];
}

// Adapte/Crie estas funções no seu funcoes.php se quiser valores dinâmicos
$totalClientes  = function_exists('TotalClientes') ? TotalClientes() : 0;
$novosClientes  = function_exists('NovosClientesMês') ? NovosClientesMês() : 0;
$clientesAtivos = function_exists('ClientesAtivos') ? ClientesAtivos() : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
    $pagina = 'clientes';
    include 'php/sidebar.php'; 
    ?>

<main class="flex-grow-1 p-4 bg-light">

        <?php if (isset($_SESSION['mensagem_erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <?= $_SESSION['mensagem_erro']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensagem_erro']); ?>
        <?php endif; ?>

        <?php 
        // Configura as informações desta página
        $tituloPagina = "Clientes";
        $breadcrumb   = "Clientes";

        // Inclui o arquivo de cabeçalho
        include 'php/header.php'; 
        ?>

    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-success px-4 py-2 rounded-3 shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalCliente">
            <i class="fa-solid fa-plus me-2"></i>
            Novo Cliente
        </button>
    </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Total de Clientes</h6>
                        <h2 class="fw-bold"><?= $totalClientes ?></h2>
                        <small>Clientes cadastrados</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Novos este Mês</h6>
                        <h2 class="fw-bold text-success"><?= $novosClientes ?></h2>
                        <small>Cadastros recentes</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Clientes Ativos</h6>
                        <h2 class="fw-bold text-primary"><?= $clientesAtivos ?></h2>
                        <small>Com ordens recentes</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <form method="GET" action="clientes.php">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Buscar Cliente</label>
                            <div class="input-group shadow-sm">
                                <input type="text" class="form-control border-success" placeholder="Buscar por nome, CPF ou e-mail..." name="nBusca" value="<?= $busca ?>">
                                <button class="btn btn-success px-4" type="submit">
                                    <i class="fa-solid fa-magnifying-glass me-0"></i>
                                </button>
                            </div>
                        </div>


                        <div class="col-lg-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-20">
                                <a href="clientes.php" class="btn btn-outline-success btn-filtrar flex-grow-1 text-center" title="Limpar">
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
                            <th>Nome</th>
                            <th>Endereço</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($busca == "") {
                            echo listaCliente();
                        } else {
                            echo BuscarCliente($busca);
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

    <div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fa-solid fa-user-plus me-2"></i>Novo Cliente</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="php/salvarCliente.php?funcao=I" method="POST">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nome Completo</label>
                    <input type="text" class="form-control" name="nCliente" placeholder="Digite o nome do cliente" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">E-mail</label>
                    <input type="email" class="form-control" name="nmail" placeholder="email@exemplo.com" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">CPF</label>
                    <input type="text" class="form-control cpf" name="nCPF" placeholder="000.000.000-00" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Telefone</label>
                    <input type="text" class="form-control telefone" name="nTelefone" placeholder="(00) 00000-0000" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Endereço</label>
                    <input type="text" class="form-control" name="nEndereco" placeholder="Ex: Rua xxxxx, n°000 - Bairro" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Senha de Acesso</label>
                    <input type="text" class="form-control" name="nSenha" value="Cellmaster123" required>
                    <small class="text-muted">Senha temporária sugerida</small>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Cadastrar Cliente</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>

    <script>
        // Máscara CPF
        const cpfs = document.querySelectorAll(".cpf");
        cpfs.forEach(function(input) {
            input.addEventListener("input", function() {
                let valor = this.value.replace(/\D/g, "");
                valor = valor.substring(0, 11);
                valor = valor.replace(/^(\d{3})(\d)/, "$1.$2");
                valor = valor.replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3");
                valor = valor.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, "$1.$2.$3-$4");
                this.value = valor;
            });
        });

        // Máscara Telefone e Restrição de Nome
        document.addEventListener("DOMContentLoaded", function() {
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