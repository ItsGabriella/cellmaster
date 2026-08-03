<?php
// Obtém o cargo do usuário logado da sessão (aceita ID numérico 1, 2, 3, 4 ou nome do cargo/tipo)
$cargoUsuario = $_SESSION['cargos_idcargos'] ?? $_SESSION['usuario_cargo'] ?? $_SESSION['cargo_id'] ?? $_SESSION['cargo'] ?? $_SESSION['tipo_usuario'] ?? 1;

// Define as páginas que CADA CARGO PODE CLICAR
// 1 = Gerente, 2 = Técnico, 3 = Atendente, 4 = Cliente
if ($cargoUsuario == 1 || strtolower($cargoUsuario) === 'gerente') {
    // Gerente clica em tudo
    $menusPermitidos = ['dashboard', 'funcionarios', 'clientes', 'orcamento', 'os', 'estoque', 'servicos', 'relatorio', 'configuracoes'];
} elseif ($cargoUsuario == 2 || strtolower($cargoUsuario) === 'técnico' || strtolower($cargoUsuario) === 'tecnico') {
    // Técnico clica apenas em Dashboard, OS, Estoque, Serviços e Configurações
    $menusPermitidos = ['dashboard', 'os', 'estoque', 'servicos', 'configuracoes'];
} elseif ($cargoUsuario == 3 || strtolower($cargoUsuario) === 'atendente') {
    // Atendente clica em Dashboard, Clientes, Orçamento, OS, Estoque, Serviços e Configurações
    $menusPermitidos = ['dashboard', 'clientes', 'orcamento', 'os', 'estoque', 'servicos', 'configuracoes'];
} elseif ($cargoUsuario == 4 || strtolower($cargoUsuario) === 'cliente') {
    // Cliente clica apenas no Dashboard e Configurações
    $menusPermitidos = ['dashboard', 'configuracoes'];
} else {
    // Padrão de segurança para clientes ou perfis não reconhecidos
    $menusPermitidos = ['dashboard', 'configuracoes'];
}

// Função auxiliar para renderizar cada item do menu
function renderMenuItem($chaveMenu, $paginaAtual, $link, $iconeClass, $titulo, $menusPermitidos) {
    $podeAcessar = in_array($chaveMenu, $menusPermitidos);
    $activeClass = ($paginaAtual == $chaveMenu) ? 'active' : '';
    
    // Se não puder acessar, adiciona estilo visual de desabilitado e bloqueia clique
    $disabledClass = !$podeAcessar ? 'opacity-50 pe-none' : '';
    $href = $podeAcessar ? $link : '#';
    $titleAttr = !$podeAcessar ? 'title="Acesso não permitido para o seu perfil"' : '';

    echo '
    <li class="nav-item side-item ' . $activeClass . ' ' . $disabledClass . '" ' . $titleAttr . '>
        <a href="' . $href . '" class="nav-link text-white ' . (!$podeAcessar ? 'disabled' : '') . '" ' . (!$podeAcessar ? 'tabindex="-1" aria-disabled="true"' : '') . '>
            <i class="' . $iconeClass . '"></i>
            <span class="item-description ms-2">' . $titulo . '</span>
        </a>
    </li>';
}
?>

<nav id="sidebar" class="d-flex flex-column justify-content-between">

    <div class="p-3">

        <div class="logo d-flex align-items-center gap-2 mb-4 text-white">
            <div class="bg-success p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fa-solid fa-mobile-screen-button fs-4"></i>
            </div>
            <h4 class="fw-bold mb-0 item-description">CELLMASTER</h4>
        </div>

        <ul class="nav flex-column gap-2">
            <?php 
                renderMenuItem('dashboard', $pagina, 'dashboard.php', 'fa-solid fa-chart-line', 'Dashboard', $menusPermitidos);
                renderMenuItem('funcionarios', $pagina, 'funcionarios.php', 'fa-solid fa-clipboard-user', 'Funcionários', $menusPermitidos);
                renderMenuItem('clientes', $pagina, 'clientes.php', 'fa-solid fa-users', 'Clientes', $menusPermitidos);
                renderMenuItem('orcamento', $pagina, 'orcamento.php', 'fa-solid fa-calculator', 'Orçamento', $menusPermitidos);
                renderMenuItem('os', $pagina, 'ordens_servico.php', 'fa-solid fa-file-contract', 'Ordem de Serviço', $menusPermitidos);
                renderMenuItem('estoque', $pagina, 'estoque.php', 'fa-solid fa-boxes-stacked', 'Estoque', $menusPermitidos);
                renderMenuItem('servicos', $pagina, 'servicos.php', 'fa-solid fa-wrench', 'Serviços', $menusPermitidos);
                renderMenuItem('relatorio', $pagina, 'relatorio.php', 'fa-solid fa-file', 'Relatório', $menusPermitidos);
                renderMenuItem('configuracoes', $pagina, 'configuracoes.php', 'fa-solid fa-gear', 'Configurações', $menusPermitidos);
            ?>
        </ul>

        <button id="open_btn">
            <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
        </button>

    </div>

    <div class="border-top p-3">
        <button id="logout_btn" class="w-100" onclick="window.location.href='php/logout.php';">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="item-description ms-2">Sair</span>
        </button>
    </div>

</nav>