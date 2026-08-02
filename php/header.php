<?php
    // Define valores padrão para evitar erros caso a página não passe as variáveis
    $tituloPagina = isset($tituloPagina) ? $tituloPagina : 'Painel';
    $breadcrumb   = isset($breadcrumb)   ? $breadcrumb   : 'Home';
?>

<header class="navbar navbar-expand bg-white border-0 shadow-sm rounded-4 mb-4 px-4 py-3">
    <div class="container-fluid d-flex justify-content-between align-items-center p-0">
        
        <div>
            <h3 class="fw-bold mb-1 text-dark"><?= $tituloPagina ?></h3>
            <nav style="--bs-breadcrumb-divider: '>';" class="small">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="home.php" class="text-success text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $breadcrumb ?></li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-3">
            
            <button class="btn btn-bell rounded-circle d-flex align-items-center justify-content-center p-0" type="button" aria-label="Notificações">
                <i class="fa-solid fa-bell text-success fs-5"></i>
            </button>

            <div class="d-flex align-items-center gap-2 ps-2">
                <?php 
                    $fotoBD = $_SESSION["foto"] ?? '';
                    
                    // Verifica se a foto existe na pasta 'img/perfil/'
                    if (!empty($fotoBD) && file_exists("img/perfil/" . $fotoBD)) {
                        $caminhoFoto = "img/perfil/" . $fotoBD;
                    } else {
                        // Foto padrão localizada na pasta img/
                        $caminhoFoto = "img/user.jpeg"; 
                    }
                ?>
                <img
                    src="<?= $caminhoFoto; ?>?v=<?= time(); ?>"
                    alt="Foto de Perfil" 
                    class="rounded-circle object-fit-cover border" 
                    width="42" 
                    height="42"
                    onerror="this.onerror=null; this.src='img/user.png';">
                
                <div class="d-flex flex-column justify-content-center lh-1">
                    <span class="fw-bold text-dark fs-6 mb-1">
                        <?= htmlspecialchars($_SESSION['nome'] ?? 'Utilizador'); ?>
                    </span>
                    <span class="text-secondary small fw-normal">
                        <?= htmlspecialchars($_SESSION['cargo'] ?? 'Atendente'); ?>
                    </span>
                </div>
            </div>

        </div>

    </div>
</header>