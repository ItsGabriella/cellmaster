<?php

include("php/funcoes.php");



    $busca = "";

    if(isset($_GET["nBusca"]))
    {
        $busca = $_GET["nBusca"];
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>clientes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-custom">

    <?php 
    $pagina = 'clientes';
    include 'php/sidebar.php'; ?>


    <main class="flex-grow-1 p-4 bg-light">

    <div class="card border-0 shadow-sm rounded-4 mb-4">

<div class="card-body p-4">

    <div class="d-flex justify-content-between align-items-center">

        <div>
            <h3 class="fw-bold mb-1">
                Clientes
            </h3>

            <nav style="--bs-breadcrumb-divider: '>';">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-success text-decoration-none">
                            Home
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Clientes
                    </li>
                </ol>
            </nav>
        </div>

        <button class="btn btn-success px-4 py-2"
        data-bs-toggle="modal"
        data-bs-target="#modalCliente">
            <i class="fa-solid fa-plus me-2"></i>
            Novo Cliente
        </button>

    </div>

</div>

</div>
        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white p-4">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Buscar Cliente
                        </label>

                    <form method="GET" action="clientes.php">

                        <div class="input-group">

                            <input
                                type="text"
                                class="form-control"
                                name="nBusca"
                                placeholder="Buscar Cliente..."
                                value="<?= $busca ?>">

                            <button class="btn btn-success" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>

                        </div>

                    </form>

                    </div>

                <div class="col-lg-auto d-flex align-items-end">

                    <div class="d-flex gap-2">

                        <a href="clientes.php"
                            class="btn btn-outline-success btn-filtro"
                            title="Limpar">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>

                    </div>

                    </div>

                </div>

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
<!-- idcliente	nome_clien	endereco_clien	cpf_clien	tel_clien	email_clien -->
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

    if($busca == "")
    {
        echo listaCliente();
    }
    else
    {
        echo BuscarCliente($busca);
    }

    ?>

    <!-- Telinha q aparece na frente -->
<div class="modal fade" id="modalCliente" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-success text-white">

                <h5 class="modal-title">
                    <i class="fa-solid fa-box-archive me-2"></i> 
                    Novo Cliente   <!-- Nome em cima-->
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

        <div class="modal-body">

    <form method="POST"
          action="php/salvarCliente.php?funcao=I"
          enctype="multipart/form-data">

        <div class="row g-3">
<!-- idcliente	nome_clien	endereco_clien	cpf_clien	tel_clien	email_clien -->
            <!-- Nome -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Nome do Cliente
                </label>

                <input type="text"
                       class="form-control nome"
                       id="iCliente"
                       name="nCliente"
                       placeholder="Digite o nome do cliente"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Endereço
                </label>

                <input type="text"
                       class="form-control"
                       id="iEndereco"
                       name="nEndereco"
                       placeholder="Endereço"
                       required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    CPF
                </label>

                <input type="text"
                class="form-control cpf"
                name="nCPF"
                placeholder="000.000.000-00"
                maxlength="14"
                required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Telefone
                </label>

                <input type="text"
                class="form-control telefone"
                name="nTelefone"
                placeholder="(99) 99999-9999"
                maxlength="15"
                required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    E-mail
                </label>

                <input type="email"
                       class="form-control"
                       id="imail"
                       name="nmail"
                       placeholder="Digite o e-mail"
                       required>
            </div>

        </div>

        <div class="modal-footer mt-4">

            <button type="button"
                    class="btn btn-outline-danger"
                    data-bs-dismiss="modal">
                Cancelar
            </button>

            <button type="submit"
                    class="btn btn-success">
                <i class="fa-solid fa-floppy-disk me-2"></i>
                Salvar Cliente
            </button>

        </div>

    </form>

</div>

</div>

</div>
</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>
    

    <script>
        // Máscara CPF
        const cpfs = document.querySelectorAll(".cpf");
        cpfs.forEach(function(input){
            input.addEventListener("input", function(){
                let valor = this.value.replace(/\D/g, "");
                valor = valor.substring(0,11);
                valor = valor.replace(/^(\d{3})(\d)/, "$1.$2");
                valor = valor.replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3");
                valor = valor.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, "$1.$2.$3-$4");
                this.value = valor;
            });
        });
    document.addEventListener("DOMContentLoaded", function () {

    const telefones = document.querySelectorAll(".telefone");

    telefones.forEach(function(input){

        input.addEventListener("input", function(){

            let valor = this.value.replace(/\D/g, "");

            valor = valor.substring(0,11);

            if(valor.length > 10){

                valor = valor.replace(
                    /^(\d{2})(\d{5})(\d{4})$/,
                    "($1) $2-$3"
                );

            }else{

                valor = valor.replace(
                    /^(\d{2})(\d{4})(\d{0,4})$/,
                    "($1) $2-$3"
                );

            }

            this.value = valor;

        });

    });
    const nomes = document.querySelectorAll(".nome");
    nomes.forEach(function(input){
        input.addEventListener("input", function(){
            this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, "");
        });

});

});
</script>


</body>
</html>