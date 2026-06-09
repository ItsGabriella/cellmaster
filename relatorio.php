<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/styleestoque.css">
</head>

<body class="bg-custom">

    <div class="d-flex">

        <nav id="sidebar" class="d-flex flex-column justify-content-between">

            <div class="p-3">


                <div class="d-flex align-items-center gap-2 mb-4">
                    <img src="img/perfil.png" id="user_avatar" alt="Avatar">

                    <div class="user_infos">
                        <span class="item-description d-block">
                            Fulano de tal
                        </span>
                        <span class="item-description d-block text-secondary">
                            Gerente
                        </span>
                    </div>
                </div>

                <ul class="nav flex-column gap-2">

                    <li class="nav-item side-item active">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-chart-line"></i>
                            <span class="item-description ms-2">
                                Dashboard
                            </span>
                        </a>
                    </li>

                    <li class="nav-item side-item">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-clipboard-user"></i>
                            <span class="item-description ms-2">
                                Funcionários
                            </span>
                        </a>
                    </li>

                    <li class="nav-item side-item">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-users"></i>
                            <span class="item-description ms-2">
                                Clientes
                            </span>
                        </a>
                    </li>

                    <li class="nav-item side-item active">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-box-archive"></i>
                            <span class="item-description ms-2">
                                Estoque
                            </span>
                        </a>
                    </li>


                    <li class="nav-item side-item">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span class="item-description ms-2">
                                Serviços
                            </span>
                        </a>
                    </li>


                    <li class="nav-item side-item">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span class="item-description ms-2">
                                Orçamento
                            </span>
                        </a>
                    </li>

                    <li class="nav-item side-item">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-file-contract"></i>
                            <span class="item-description ms-2">
                                Ordem de Serviço
                            </span>
                        </a>
                    </li>

                    <li class="nav-item side-item">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-file"></i>
                            <span class="item-description ms-2">
                                Relatório
                            </span>
                        </a>
                    </li>

                </ul>

                <button id="open_btn">
                    <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
                </button>

            </div>


            <div class="border-top p-3">
                <button id="logout_btn" class="w-100">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>

                    <span class="item-description">
                        Logout
                    </span>
                </button>
            </div>

        </nav>


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

<!-- Total de Serviços -->
        <div class="col-md-3">
            <div class="card dashboard-card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-secondary mb-2">
                                Total de Relatórios
                            </h6>

                            <h2 class="fw-bold mb-1">
                                65
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
                                48
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
                                17
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
                            <h6 class="text-secondary mb-2">
                                Exportados
                            </h6>

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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>

</body>
</html>