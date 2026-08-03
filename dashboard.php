<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Inclui a conexão com o BD
if (file_exists("php/conexaoBD.php")) {
    include("php/conexaoBD.php");
} elseif (file_exists("conexaoBD.php")) {
    include("conexaoBD.php");
}

$conn = $conn ?? $conexao ?? null;

// 2. Verifica se o utilizador está logado
$idUsuario = $_SESSION['usuario_id'] ?? $_SESSION['idfuncionario'] ?? $_SESSION['idcliente'] ?? $_SESSION['id'] ?? null;

if (!$idUsuario) {
    header("Location: index.php");
    exit;
}

// 3. Identifica o cargo/perfil para carregar o sub-dashboard
$idCargo = $_SESSION['usuario_cargo'] ?? $_SESSION['cargos_idcargos'] ?? 0;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? $_SESSION['cargo'] ?? '';

if ($idCargo == 1 || strtolower($tipoUsuario) == 'gerente') {
    $perfil = 'gerente';
} elseif ($idCargo == 2 || strtolower($tipoUsuario) == 'técnico' || strtolower($tipoUsuario) == 'tecnico') {
    $perfil = 'tecnico';
} elseif ($idCargo == 3 || strtolower($tipoUsuario) == 'atendente') {
    $perfil = 'atendente';
} else {
    // Qualquer usuário com idCargo = 4 ou tipo_usuario = 'cliente' cairá aqui
    $perfil = 'cliente';
}

// 4. Define variáveis para o Header (com o cargo no título)
$pagina = 'dashboard';
$tituloPagina = 'Dashboard ' . ucfirst($perfil);
$breadcrumb = 'Painel Geral';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloPagina ?> - CELLMASTER</title>
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="wrapper">
    <?php include("php/sidebar.php"); ?>

    <main class="main-content">
        <?php include("php/header.php"); ?>

        <?php
        switch ($perfil) {
            case 'gerente':
                include("php/dash_gerente.php");
                break;
            case 'tecnico':
                include("php/dash_tecnico.php");
                break;
            case 'atendente':
                include("php/dash_atendente.php");
                break;
            case 'cliente':
            default:
                include("php/dash_cliente.php");
                break;
        }
        ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/home.js"></script>
</body>
</html>