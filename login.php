<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"> <!-- puxa a imagem de visualizar e esconder do site bootstrap-icons -->
        <link rel="stylesheet" href="css/stylelogin.css">
    </head>

    <body>

        <script src="js/login2.js"></script>

        <form action="php/validaAcesso.php" method="POST">
            <div class="titulo">
            <h1> Faça seu Login </h1>
            <div class="barra-horizontal"></div>
            </div>
            <br>
            <div class="campo-input">
                <label for="email">E-mail*</label><br>
                <input type="email" id="iEmail" placeholder="Digite seu e-mail" maxlength="50" required/>
            </div>
            <div class="senha">
                <label for="password">Senha*</label><br>
                <div class="input-senha">
                    <input type="password" id="iSenha" placeholder="Digite sua senha" maxlength="8" required/>
                    <i class="bi bi-eye" id="iIcone1" onclick="toggleSenha()"></i> <!-- botao de visualizar e esconder -->
                </div>
            </div>
            <div class="lembrar-me">
            <input type="checkbox" id="cLembrar">
            <p>Lembrar-me</p>
            </div>
            <input type="submit" value="ENTRAR">
            <p> Esqueceu sua senha? </p>

            </div>
        </form>


    </body>

</html>