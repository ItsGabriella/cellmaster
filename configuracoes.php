<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include ('php/funcoes.php');
$pagina = 'configuracoes';

// Identifica se o usuário logado é Cliente (Cargo 4)
$idCargo = $_SESSION['cargos_idcargos'] ?? $_SESSION['usuario_cargo'] ?? 0;
$tipoUsuario = strtolower($_SESSION['tipo_usuario'] ?? '');

$isCliente = ($idCargo == 4 || $tipoUsuario === 'cliente');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - CELLMASTER</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Cor de fundo e borda do item ativo na lista */
        .list-group-item.active {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        /* Cor do interruptor (Switch) quando ativado */
        .form-check-input:checked {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        /* Foco verde para os campos */
        .form-check-input:focus,
        .list-group-item-action:focus {
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }
    </style>
</head>
<body>

<div class="wrapper">
    <?php include("php/sidebar.php"); ?>

    <main class="main-content">
        <?php include("php/header.php"); ?>

        <div class="container-fluid p-4">
            
            <h4 class="fw-bold text-dark mb-4">
                <i class="fa-solid fa-gear me-2 text-success"></i>Configurações do Sistema
            </h4>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="list-group shadow-sm border rounded-3" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active p-3" id="list-perfil-list" data-bs-toggle="list" href="#list-perfil" role="tab">
                            <i class="fa-solid fa-user me-2"></i> Meu Perfil
                        </a>

                        <a class="list-group-item list-group-item-action p-3" id="list-seguranca-list" data-bs-toggle="list" href="#list-seguranca" role="tab">
                            <i class="fa-solid fa-lock me-2"></i> Segurança
                        </a>

                        <?php if (!$isCliente): ?>
                            <a class="list-group-item list-group-item-action p-3" id="list-preferencias-list" data-bs-toggle="list" href="#list-preferencias" role="tab">
                                <i class="fa-solid fa-sliders me-2"></i> Preferências
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="tab-content" id="nav-tabContent">
                        
                        <div class="tab-pane fade show active" id="list-perfil" role="tabpanel">
                            <div class="card bg-white shadow-sm border p-4 rounded-3">
                                <h5 class="fw-bold mb-3 text-dark">Informações do Perfil</h5>
                                 <form method="POST" action="php/salvarConta.php" enctype="multipart/form-data" onsubmit="return validarSenhas()">

                                    <input type="hidden" name="nIdFuncionario" value="<?= $_SESSION['id'] ?? '' ?>">



                                    <div class="text-center mb-4">

                                        <img src="img/perfil/<?= $_SESSION['foto'] ?? '../user.png' ?>"

                                             class="rounded-circle border border-3 border-success"

                                             width="130" height="130" style="object-fit:cover;"

                                             onerror="this.src='img/user.png'">

                                        <div class="mt-3 col-md-6 mx-auto">

                                            <label class="form-label fw-semibold">Foto de Perfil</label>

                                            <input type="file" class="form-control" name="fotoPerfil" accept="image/*">

                                        </div>

                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nome Completo</label>
                                            <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($_SESSION['nome'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">E-mail</label>
                                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success fw-semibold">Salvar Alterações</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="list-seguranca" role="tabpanel">
                            <div class="card bg-white shadow-sm border p-4 rounded-3">
                                <h5 class="fw-bold mb-3 text-dark">Alterar Senha</h5>
                                <form action="php/salvarConta.php" method="POST" onsubmit="return validarSenhas()">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Senha Atual</label>
                                        <input type="password" class="form-control" name="senha_atual" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nova Senha</label>
                                        <input type="password" class="form-control" id="iSenha" name="nova_senha" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Confirmar Nova Senha</label>
                                        <input type="password" class="form-control" id="iConfirmarSenha" name="confirmar_senha" required>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success fw-semibold">Atualizar Senha</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php if (!$isCliente): ?>
                            <div class="tab-pane fade" id="list-preferencias" role="tabpanel">
                                <div class="card bg-white shadow-sm border p-4 rounded-3">
                                    <h5 class="fw-bold mb-3 text-dark">Preferências do Sistema</h5>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" checked type="checkbox" id="notif">
                                        <label class="form-check-label" for="notif">Receber notificações por e-mail</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" checked type="checkbox" id="est">
                                        <label class="form-check-label" for="est">Avisar estoque baixo</label>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-success fw-semibold">Salvar Preferências</button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function validarSenhas() {
        const senha = document.getElementById('iSenha').value;
        const confirmar = document.getElementById('iConfirmarSenha').value;

        if (senha !== "" && senha !== confirmar) {
            alert("A 'Nova Senha' e a 'Confirmação' não coincidem!");
            return false;
        }
        return true;
    }
</script>
<script src="js/home.js"></script>
</body>
</html>