<?php

$email = "";

if(isset($_COOKIE["lembrar_email"])){
    $email = $_COOKIE["lembrar_email"];
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/login.css">

    <script src="js/login2.js"></script>
</head>

<body>

    <div class="container-fluid vh-100">

        <div class="row h-100">

            <!-- Lado esquerdo -->
            <div class="col-lg-6 d-none d-lg-block p-0">

                <div class="imagem-login">

                    <div class="overlay">

                    </div>

                </div>

            </div>

            <!-- Lado direito -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center">

<div class="card login-card border-0">

    <div class="card-body p-5">

        <div class="text-center mb-5">

            <div class="logo-circle mx-auto mb-3">

                <i class="bi bi-phone-fill"></i>

            </div>

            <h2 class="fw-bold">

                Bem-vindo!

            </h2>

            <p class="text-muted">

                Faça login para acessar o sistema.

            </p>

            <div class="linha"></div>

        </div>

        <form action="php/validaAcesso.php" method="POST">

            <!-- EMAIL -->

            <div class="mb-4">

                <label class="form-label">

                    E-mail

                </label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-envelope-fill"></i>

                    </span>

                    <input
                        type="email"
                        class="form-control"
                        name="nLogin"
                        id="iLogin"
                        placeholder="Digite seu e-mail"
                        value="<?= $email ?>"
                        required>

                </div>

            </div>

            <!-- SENHA -->

            <div class="mb-4">

                <label class="form-label">

                    Senha

                </label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-lock-fill"></i>

                    </span>

                    <input
                        type="password"
                        class="form-control"
                        name="nSenha"
                        id="iSenha"
                        placeholder="Digite sua senha"
                        required>

                    <button
                        type="button"
                        class="btn btn-olho"
                        onclick="toggleSenha()">

                        <i id="iconeSenha"
                            class="bi bi-eye-fill"></i>

                    </button>

                </div>

            </div>

            <!-- OPÇÕES -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="lembrar"
                        name="lembrar"
                        value="1"
                        <?= isset($_COOKIE["lembrar_email"]) ? "checked" : "" ?>>

                    <label
                        class="form-check-label"
                        for="lembrar">

                        Lembrar-me

                    </label>

                </div>

                <a
                    href="php/solicitarSenha.php"
                    class="link-success text-decoration-none">

                    Esqueceu sua senha?

                </a>

            </div>

            <!-- BOTÃO -->

            <button
                type="submit"
                class="btn btn-success w-100">

                <i class="bi bi-box-arrow-in-right"></i>

                Entrar

            </button>

        </form>



    </div>

</div>

</div>

</div>

</div>
  


</body>

</html>