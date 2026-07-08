<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recuperar Senha</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../css/login.css">

    <style>

        body{
            background: linear-gradient(135deg,#1d4d1d,#2d7d32);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .card-recuperar{
            width:100%;
            max-width:480px;
            border:none;
            border-radius:20px;
            box-shadow:0 20px 45px rgba(0,0,0,.15);
        }

        .logo-circle{
            width:90px;
            height:90px;
            background:#198754;
            color:#fff;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:2.2rem;
        }

        .btn-success{
            border-radius:12px;
            height:50px;
            font-weight:600;
        }

        .form-control{
            height:50px;
            border-radius:12px;
        }

        .voltar{
            text-decoration:none;
            color:#198754;
            font-weight:600;
        }

        .voltar:hover{
            text-decoration:underline;
        }

    </style>

</head>

<body>

<div class="card card-recuperar">

    <div class="card-body p-5">

        <div class="text-center">

            <div class="logo-circle mx-auto mb-4">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <h2 class="fw-bold">
                Recuperar Senha
            </h2>

            <p class="text-muted mb-4">
                Informe seu e-mail cadastrado.
                Enviaremos um link para redefinir sua senha.
            </p>

        </div>

        <form action="enviarRecuperacao.php" method="POST">

            <div class="mb-4">

                <label class="form-label fw-semibold">
                    E-mail
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Digite seu e-mail"
                    required>

            </div>

            <button
                type="submit"
                class="btn btn-success w-100">

                <i class="bi bi-envelope-paper-fill me-2"></i>
                Enviar link de recuperação

            </button>

        </form>

        <div class="text-center mt-4">

            <a href="../index.php" class="voltar">

                <i class="bi bi-arrow-left"></i>
                Voltar para o login

            </a>

        </div>

    </div>

</div>

</body>

</html>