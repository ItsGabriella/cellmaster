<?php
    // Define valores padrão para evitar erros caso a página não passe as variáveis
    $tituloPagina = isset($tituloPagina) ? $tituloPagina : 'Painel';
    $breadcrumb   = isset($breadcrumb)   ? $breadcrumb   : 'Home';

    include('conexaoBD.php')
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
            
            <?php
// Consulta notificações
                $queryNotif = "SELECT * FROM notificacoes ORDER BY data_criacao DESC LIMIT 5";
                $resNotif = mysqli_query($conn, $queryNotif);

                // Conta quantas notificações não foram lidas
                $queryNaoLidas = "SELECT COUNT(*) as total FROM notificacoes WHERE lida = 0";
                $resNaoLidas = mysqli_query($conn, $queryNaoLidas);
                $totalNaoLidas = mysqli_fetch_assoc($resNaoLidas)['total'];
                ?>

                <div class="dropdown">
                    <button class="btn btn-bell rounded-circle d-flex align-items-center justify-content-center p-0 position-relative" 
                            type="button" 
                            id="btnNotificacoes"
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <i class="fa-solid fa-bell text-success fs-5"></i>
                        
                        <?php if ($totalNaoLidas > 0): ?>
                            <span id="badgeNotificacao" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                        <?php endif; ?>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2 p-2" style="width: 400px;">
                        <li class="dropdown-header fw-bold text-dark border-bottom pb-2 mb-1">Notificações</li>
                        
                        <?php 
                        if (mysqli_num_rows($resNotif) > 0) {
                            while ($notif = mysqli_fetch_assoc($resNotif)) {
                                $tempo = date('d/m H:i', strtotime($notif['data_criacao']));
                                $usuario = htmlspecialchars($notif['usuario'] ?? 'Sistema');
                                $mensagem = htmlspecialchars($notif['mensagem']);

                                echo "
                                <li class='my-1'>
                                    <div class='dropdown-item rounded-3 p-2 small bg-white'>
                                        <div class='text-dark'>{$mensagem}</div>
                                        <div class='d-flex justify-content-between align-items-center mt-1' style='font-size: 0.75rem;'>
                                            <span class='badge bg-light text-secondary border'>por {$usuario}</span>
                                            <span class='text-muted'>{$tempo}</span>
                                        </div>
                                    </div>
                                </li>";
                            }
                        } else {
                            echo "<li><span class='dropdown-item text-muted small text-center py-3'>Nenhuma notificação</span></li>";
                        }
                        ?>
                    </ul>
                </div>

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

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const btnNotificacoes = document.getElementById('btnNotificacoes');
    const badgeNotificacao = document.getElementById('badgeNotificacao');

    if (btnNotificacoes) {
        // Escuta o evento de ABERTURA do dropdown do Bootstrap
        btnNotificacoes.addEventListener('show.bs.dropdown', function () {
            
            // 1. Esconde a bolinha vermelha imediatamente no front-end
            if (badgeNotificacao) {
                badgeNotificacao.style.display = 'none';
            }

            // 2. Envia a requisição em segundo plano para marcar no banco de dados como lidas
            fetch('php/marcarLidas.php')
                .then(response => response.json())
                .then(data => {
                    // Atualizado com sucesso no banco de dados!
                })
                .catch(error => console.error('Erro ao marcar notificações:', error));
        });
    }
});
</script>
</header>