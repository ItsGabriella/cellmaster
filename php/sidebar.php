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

            <li class="nav-item side-item <?= ($pagina == 'dashboard') ? 'active' : '' ?>">
                <a href="dashboard.php" class="nav-link text-white">
                    <i class="fa-solid fa-chart-line"></i>
                    <span class="item-description ms-2">Dashboard</span>
                </a>
            </li>

            <li class="nav-item side-item <?= ($pagina == 'funcionarios') ? 'active' : '' ?>">
                <a href="funcionarios.php" class="nav-link text-white">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <span class="item-description ms-2">Funcionários</span>
                </a>
            </li>

            <li class="nav-item side-item <?= ($pagina == 'clientes') ? 'active' : '' ?>">
                <a href="clientes.php" class="nav-link text-white">
                    <i class="fa-solid fa-users"></i>
                    <span class="item-description ms-2">Clientes</span>
                </a>
            </li>

            <li class="nav-item side-item <?= ($pagina == 'estoque') ? 'active' : '' ?>">
                <a href="estoque.php" class="nav-link text-white">
                    <i class="fa-solid fa-box-archive"></i>
                    <span class="item-description ms-2">Estoque</span>
                </a>
            </li>

            <li class="nav-item side-item <?= ($pagina == 'servicos') ? 'active' : '' ?>">
                <a href="servicos.php" class="nav-link text-white">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <span class="item-description ms-2">Serviços</span>
                </a>
            </li>

            <li class="nav-item side-item <?= ($pagina == 'orcamentos') ? 'active' : '' ?>">
                <a href="orcamentos.php" class="nav-link text-white">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span class="item-description ms-2">Orçamento</span>
                </a>
            </li>

            <li class="nav-item side-item <?= ($pagina == 'os') ? 'active' : '' ?>">
                <a href="ordem_servico.php" class="nav-link text-white">
                    <i class="fa-solid fa-file-contract"></i>
                    <span class="item-description ms-2">Ordem de Serviço</span>
                </a>
            </li>

            <li class="nav-item side-item <?= ($pagina == 'relatorio') ? 'active' : '' ?>">
                <a href="relatorio.php" class="nav-link text-white">
                    <i class="fa-solid fa-file"></i>
                    <span class="item-description ms-2">Relatório</span>
                </a>
            </li>

        </ul>

        <button id="open_btn">
            <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
        </button>

    </div>

    <div class="border-top p-3">
        <button id="logout_btn" class="w-100" onclick="window.location.href='index.php';">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>

            <span class="item-description">
                Logout
            </span>
        </button>
    </div>

</nav>