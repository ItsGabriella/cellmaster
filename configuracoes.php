<?php
session_start();
$pagina = 'configuracoes';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-custom">

<div class="d-flex">
    <?php include 'php/sidebar.php'; ?>

    <main class="flex-grow-1 p-4 bg-light">
        <?php
        $tituloPagina = "Configurações";
        $breadcrumb   = "Configurações";
        include 'php/header.php';
        ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Configurações atualizadas com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="list-group shadow-sm rounded-3">
                    <a class="list-group-item list-group-item-action active p-3" data-bs-toggle="list" href="#conta">
                        <i class="fa-solid fa-user me-2"></i>Minha Conta
                    </a>
                    <a class="list-group-item list-group-item-action p-3" data-bs-toggle="list" href="#geral">
                        <i class="fa-solid fa-store me-2"></i>Dados da Empresa
                    </a>
                    <a class="list-group-item list-group-item-action p-3" data-bs-toggle="list" href="#sistema">
                        <i class="fa-solid fa-sliders me-2"></i>Preferências
                    </a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="tab-content">

                    <div class="tab-pane fade show active" id="conta">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="fw-bold text-success"><i class="fa-solid fa-user-gear me-2"></i>Minha Conta</h5>
                            </div>
                            <div class="card-body">
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

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nome</label>
                                            <input type="text" class="form-control" name="nNome" value="<?= htmlspecialchars($_SESSION['nome'] ?? '') ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">E-mail (Login)</label>
                                            <input type="email" class="form-control" name="nEmail" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nova Senha</label>
                                            <input type="password" class="form-control" id="iSenha" name="nSenha" placeholder="Deixe em branco para manter a atual">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Confirmar Nova Senha</label>
                                            <input type="password" class="form-control" id="iConfirmarSenha" name="nConfirmarSenha" placeholder="Repita a nova senha">
                                        </div>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button class="btn btn-success" type="submit">
                                            <i class="fa-solid fa-floppy-disk me-2"></i>Salvar Alterações
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="geral">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="fw-bold text-success"><i class="fa-solid fa-building me-2"></i>Dados da Empresa</h5>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nome Fantasia</label>
                                            <input value="Cellmaster Serviços" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">CNPJ</label>
                                            <input value="64.537.853/0001-44" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Telefone</label>
                                            <input value="(47) 98342-3942" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">E-mail de Contato</label>
                                            <input value="Cellmaster.Servicos@gmail.com" class="form-control" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Endereço</label>
                                            <input value="Rua XV de Novembro, 343" class="form-control" readonly>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="sistema">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="fw-bold text-success"><i class="fa-solid fa-sliders me-2"></i>Preferências</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" checked type="checkbox" id="notif">
                                    <label class="form-check-label" for="notif">Receber notificações</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" checked type="checkbox" id="est">
                                    <label class="form-check-label" for="est">Avisar estoque baixo</label>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-success">Salvar Preferências</button>
                                </div>
                            </div>
                        </div>
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
</body>
</html>