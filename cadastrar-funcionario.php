<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Funcionário</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        *{
            font-family: 'Poppins', sans-serif;
        }

        body{
            background: linear-gradient(90deg,#244d38,#4f6f62);
            min-height: 100vh;
        }

        /* A BARRA DO LADO */

        .sidebar{
            width: 220px;
            min-height: 100vh;
            background-color: #183f2d;
            position: fixed;
            padding-top: 30px;
        }

        .sidebar h2{
            color: white;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar a{
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px 25px;
            font-size: 18px;
            transition: 0.3s;
        }

        .sidebar a:hover{
            background-color: #245a3d;
        }

        /* CORZINHAS */

        .content{
            margin-left: 240px;
            padding: 30px;
        }

        .card-form{
            background-color: #b7dfc5;
            border-radius: 15px;
            padding: 30px;
        }

        .titulo{
            font-size: 45px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-control{
            height: 50px;
            border-radius: 8px;
        }

        .btn-salvar{
            background-color: #214d36;
            color: white;
            border: none;
        }
        .btn-alterar{
            background-color:rgb(204, 178, 64);
            color: white;
            border: none;
        }
        .btn-excluir{
            background-color:rgb(168, 36, 36);
            color: white;
            border: none;
        }

        .btn-salvar:hover{
            background-color: #163726;
            color: white;
        }

        .btn-limpar{
            background-color: #8b6d68;
            color: white;
            border: none;
        }

        .btn-limpar:hover{
            background-color: #705653;
            color: white;
        }

        table{
            margin-top: 30px;
        }

        thead{
            background-color: #0f472b;
            color: white;
        }

    </style>

</head>

<body>

    <!-- MENU LATERAL -->

    <div class="sidebar">

        <h2>CELLMASTER</h2>
        <!-- AINDA N TEM ABA DAS TELAS (POR ENQUANTO)   -->
        <a href="#">Cliente</a>
        <a href="#">Funcionário</a>
        <a href="#">Estoque</a>
        <a href="#">Serviços</a>
        <a href="#">Ordem de Serviço</a>
        <a href="#">Relatórios</a>

    </div>

    <!-- INFORMACOES -->

    <div class="content">

        <div class="card-form shadow">

            <h1 class="titulo">Cadastrar Funcionário</h1>

            <form method="POST" action="php/salvaFuncionario.php?opcao=I">

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label">Nome</label>

                        <input type="text" 
                               name="nome" 
                               class="form-control"
                               placeholder="Nome"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cargo</label>

                        <input type="text" 
                               name="cargo" 
                               class="form-control"
                               placeholder="Cargo"
                               required>
                    </div>

                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label">Endereço</label>

                        <input type="text" 
                               name="endereco" 
                               class="form-control"
                               placeholder="Endereço"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Telefone</label>

                        <input type="text" 
                               name="telefone" 
                               class="form-control"
                               placeholder="Telefone"
                               required>
                    </div>

                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label">CPF</label>

                        <input type="text" 
                               name="cpf" 
                               class="form-control"
                               placeholder="CPF"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">E-mail</label>

                        <input type="email" 
                               name="email" 
                               class="form-control"
                               placeholder="E-mail"
                               required>
                    </div>

                </div>

                <div class="row mb-4">

                    <div class="col-md-12">
                        <label class="form-label">Senha</label>

                        <input type="password" 
                               name="senha" 
                               class="form-control"
                               placeholder="Senha"
                               required>
                    </div>

                </div>

                <!-- BOTOES -->

                <div class="d-flex gap-2 justify-content-end">

                    <button type="reset" class="btn btn-limpar px-4">
                        Limpar
                    </button>

                    <button type="submit" class="btn btn-salvar px-4">
                        Salvar
                    </button>
                    <button type="submit" class="btn btn-alterar px-4">
                        Alterar
                    </button>
                    <button type="submit" class="btn btn-excluir px-4">
                        Excluir
                    </button>

                </div>

            </form>

            <!-- TABELA QUE N FUNCIONA (AINDA)-->

            <table class="table table-bordered table-hover mt-5">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cargo</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>1</td>
                        <td>Carlos</td>
                        <td>Técnico</td>
                        <td>(92) 99999-9999</td>
                        <td>carlos@gmail.com</td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Ana</td>
                        <td>Atendente</td>
                        <td>(92) 98888-8888</td>
                        <td>ana@gmail.com</td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</body>
</html>