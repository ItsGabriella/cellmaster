<!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Login</title>

        <link rel="stylesheet" href="css/index.css">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Fonte -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    </head>

    <body>

        <div class="login-card">

            <h1 class="title">Faça seu Login</h1>
            <form action="php/validaAcesso.php" method="POST">

                <div class="mb-3">
                    <label class="form-label">E-mail*</label>
                    <input 
                    type="email"
                    class="form-control"
                    placeholder="Digite seu e-mail" required>
                </div>

                <div class="mb-3 password-box">
                    <label class="form-label">Senha*</label>
                    <input 
                    type="password"
                    class="form-control"
                    placeholder="Digite sua senha" required>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="lembrar">

                    <label class="form-check-label" for="lembrar">
                    Lembrar-me
                    </label>
                </div>

                <input type="submit" value="ENTRAR" class="btn btn-login">
                
                <a href="#" class="forgot">
                    Esqueceu sua senha?
                </a>

            </form>

        </div>

    </body>
</html>