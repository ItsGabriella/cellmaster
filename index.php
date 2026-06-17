
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/login.css">
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

                <div class="card border-0 shadow-lg login-card">

                    <div class="card-body p-5">
                        <div class="text-center mb-4">

                        <div class="logo-circle mx-auto mb-3">
                            <i class="bi bi-phone-fill"></i>
                        </div>

                        <h2 class="fw-bold">
                            Faça seu Login
                        </h2>

                        <div class="linha"></div>

                        </div>

                        <form action="php/validaAcesso.php" method="POST">

                            <div class="mb-4">

                                <label class="form-label">
                                    E-mail
                                </label>

                                <input
                                    type="email"
                                    name="nLogin"
                                    id="iLogin"
                                    class="form-control"
                                    placeholder="Digite seu e-mail">

                            </div>

                            <div class="mb-4">

                                <label class="form-label">
                                    Senha
                                </label>

                                <div class="input-group">

                                    <input
                                        type="password"
                                        name="nSenha"
                                        id= "iSenha"
                                        class="form-control"
                                        placeholder="Digite sua senha">

                                </div>

                            </div>

                            <div class="form-check mb-4">

                                <input
                                    class="form-check-input"
                                    type="checkbox">

                                <label class="form-check-label">
                                    Lembrar-me
                                </label>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-success w-100">

                                Entrar

                            </button>

                            <div class="text-center mt-4">

                                <a href="#" class="link-success text-decoration-none">
                                    Esqueceu sua senha?
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
  


</body>

</html>