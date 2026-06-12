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
    <link rel="stylesheet" href="css/styleestoque.css">
</head>

<body class="bg-custom">

    <div class="d-flex">

        <!-- Sidebar -->
        <nav id="sidebar" class="d-flex flex-column justify-content-between">

            <div class="p-3">

                <!-- Usuário -->
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

                <!-- Menu -->
                <ul class="nav flex-column gap-2">

                    <li class="nav-item side-item">
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

            <!-- Logout -->
            <div class="border-top p-3">
                <button id="logout_btn" class="w-100">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>

                    <span class="item-description">
                        Logout
                    </span>
                </button>
            </div>

        </nav>

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

                    <button class="btn btn-success px-4 py-2">
                        <i class="fa-solid fa-plus me-2"></i>
                        Novo Produto
                    </button>

                </div>

            </div>

        </div>
        <!-- Cards -->
        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Total de Produtos</h6>
                        <h2 class="fw-bold">128</h2>
                        <small>Produtos cadastrados</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Estoque Total</h6>
                        <h2 class="fw-bold">2.456</h2>
                        <small>Unidades em estoque</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Produtos Baixos</h6>
                        <h2 class="fw-bold text-warning">15</h2>
                        <small>Abaixo do mínimo</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-secondary">Valor Total</h6>
                        <h2 class="fw-bold text-success">
                            R$ 78.650,00
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

                    <!-- Buscar -->
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Buscar Produto
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Buscar produto..."
                            >
                        </div>
                    </div>

                    <!-- Categoria -->
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">
                            Categoria
                        </label>

                        <select class="form-select">
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

                        <select class="form-select">
                            <option selected>Todos</option>
                            <option>Em estoque</option>
                            <option>Estoque baixo</option>
                            <option>Esgotado</option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="col-lg-2">
                        <div class="d-flex gap-1">

                            <button class="btn btn-outline-success flex-fill">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Limpar
                            </button>

                            <button class="btn btn-success flex-fill">
                                <i class="fa-solid fa-filter me-1"></i>
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
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Estoque</th>
                            <th>Estoque Mínimo</th>
                            <th>Valor Unitário</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <td>1</td>
                            <td>Tela AMOLED 32p</td>
                            <td>Tela</td>
                            <td>45</td>
                            <td>10</td>
                            <td>R$ 550,00</td>
                            <td>R$ 24.750,00</td>
                            <td>
                                <span class="badge text-bg-success">Em estoque</span>
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
                            <td>Bateria 5000ma</td>
                            <td>Bateria</td>
                            <td>120</td>
                            <td>20</td>
                            <td>R$ 45,00</td>
                            <td>R$ 5.400,00</td>
                            <td>
                                <span class="badge text-bg-success">Em estoque</span>
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
                            <td>Botão lateral lig/des</td>
                            <td>Botões</td>
                            <td class="text-warning fw-bold">30</td>
                            <td>50</td>
                            <td>R$ 80,00</td>
                            <td>R$ 2.400,00</td>
                            <td>
                                <span class="badge text-bg-warning">Estoque baixo</span>
                                    
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>


    <div class="modal fade" id="modalExcluir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center p-4">

                <div class="mb-3">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width:80px;height:80px;">
                        <i class="bi bi-trash text-danger fs-1"></i>
                    </div>
                </div>

                <h3 class="fw-bold">Excluir Produto</h3>

                <p class="text-secondary">
                    Tem certeza que deseja excluir o produto
                    <strong style="color: red;">Tela AMOLED 32p</strong>?
                </p>

                <p class="text-muted">
                    Esta ação não poderá ser desfeita.
                </p>

                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button class="btn btn-outline-success px-4"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button class="btn btn-danger px-4">
                        Excluir
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>