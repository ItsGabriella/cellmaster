<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Cadastrar Funcionário</title>

<link rel="stylesheet" href="css/stylefuncionario.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    font-family: 'Poppins', sans-serif;
    background: #337830;
    min-height: 100vh;
}

/* SIDEBAR */
#sidebar{
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    background-color:#245a22;
    height:100vh;
    border-radius:0 18px 18px 0;
    position:fixed;
    left:0;
    top:0;
    transition:.5s;
    min-width:82px;
    z-index:1000;
}

.sidebar_content{
    padding:12px;
}

#user{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:24px;
}

#user_avatar{
    width:50px;
    height:50px;
    border-radius:50%;
}

#user_infos{
    display:flex;
    flex-direction:column;
    color:#fff;
}

#side_items{
    display:flex;
    flex-direction:column;
    gap:8px;
    list-style:none;
    padding-left:0;
}

.side-item a{
    display:flex;
    align-items:center;
    gap:14px;
    text-decoration:none;
    color:#fff;
    padding:14px;
    border-radius:8px;
    transition:.3s;
}

.side-item a:hover,
.side-item.active a{
    background:#8a9e8a;
}

#open_btn{
    border:none;
    background:#337830;
    color:white;
    width:100%;
    padding:10px;
    border-radius:8px;
    cursor:pointer;
    margin-top:15px;
}

#logout{
    padding:12px;
}

#logout_btn{
    width:100%;
    border:none;
    background:#b02a2a;
    color:white;
    padding:12px;
    border-radius:8px;
    cursor:pointer;
}

.item-description{
    white-space:nowrap;
}

.content{
    margin-left:280px;
    padding:30px;
}
.sidebar{
    width: 82px;
    min-height: 100vh;
    background-color: #245a22;
    position: fixed;
    left: 0;
    top: 0;
    padding: 12px;
    border-radius: 0 18px 18px 0;
    transition: all .5s;
    overflow: hidden;
}

.sidebar:hover{
    width: 250px;
}

.sidebar h2{
    color: #fff;
    text-align: center;
    font-weight: 600;
    margin-bottom: 30px;
    white-space: nowrap;
}

.sidebar a{
    display: flex;
    align-items: center;
    gap: 14px;
    color: #fff;
    text-decoration: none;
    padding: 14px;
    border-radius: 8px;
    transition: .3s;
    margin-bottom: 8px;
    white-space: nowrap;
}

.sidebar a:hover{
    background-color: #8a9e8a;
    color: #fff;
}

.sidebar a.active{
    background-color: #8a9e8a;
}


/* CONTENT */
.content{
    margin-left: 260px;
    padding: 30px;
}

/* CARD */
.card-form{
    background: #ffffff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.titulo{
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #1b3d2a;
}

/* INPUTS */
.form-control{
    height: 45px;
    border-radius: 10px;
}

/* BUTTONS */
.btn-salvar{
    background: #214d36;
    color: white;
}

.btn-alterar{
    background: #c8a400;
    color: white;
}

.btn-excluir{
    background: #b02a2a;
    color: white;
}

.btn-limpar{
    background: #6c757d;
    color: white;
}

.btn:hover{
    filter: brightness(0.9);
}

/* TABLE */
table{
    margin-top: 25px;
    border-radius: 10px;
    overflow: hidden;
}

thead{
    background: #0f472b;
    color: white;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<nav id="sidebar">
    <div class="sidebar_content">

        <div id="user">
            <img src="img/perfil.png" id="user_avatar" alt="Avatar">

            <p id="user_infos">
                <span class="item-description">
                    Maria de Lurdes
                </span>
                <span class="item-description">
                    Gerente
                </span>
            </p>
        </div>

        <ul id="side_items">

            <li class="side-item">
                <a href="home.php">
                    <i class="fa-solid fa-chart-line"></i>
                    <span class="item-description">
                        Dashboard
                    </span>
                </a>
            </li>

            <li class="side-item active">
                <a href="cadastrar-funcionario.php">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <span class="item-description">
                        Funcionários
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="#">
                    <i class="fa-solid fa-users"></i>
                    <span class="item-description">
                        Clientes
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="#">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span class="item-description">
                        Orçamento
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="#">
                    <i class="fa-solid fa-file-contract"></i>
                    <span class="item-description">
                        Ordem de Serviço
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="#">
                    <i class="fa-solid fa-file"></i>
                    <span class="item-description">
                        Relatório
                    </span>
                </a>
            </li>

        </ul>

        <button id="open_btn">
            <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
        </button>

    </div>

    <div id="logout">
        <button id="logout_btn" onclick="window.location.href='index.php';">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span class="item-description">
                Logout
            </span>
        </button>
    </div>
</nav>
<!--<div class="sidebar">
    <h2>CELLMASTER</h2>
    
    <a href="#"><i class="bi bi-person"></i> Cliente</a>
    <a href="#"><i class="bi bi-people"></i> Funcionário</a>
    <a href="#"><i class="bi bi-box"></i> Estoque</a>
    <a href="#"><i class="bi bi-tools"></i> Serviços</a>
    <a href="#"><i class="bi bi-file-earmark-text"></i> OS</a>
    <a href="#"><i class="bi bi-bar-chart"></i> Relatórios</a>
</div> -->

<!-- CONTENT -->
<div class="content">

    <div class="card card-form">

        <h1 class="titulo">
            <i class="bi bi-person-plus"></i> Cadastrar Funcionário
        </h1>

        <form method="POST" action="php/salvaFuncionario.php?opcao=I">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cargo</label>
                    <input type="text" name="cargo" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Endereço</label>
                    <input type="text" name="endereco" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>

            </div>

            <!-- BUTTONS -->
            <div class="d-flex justify-content-end gap-2 mt-4">

                <button type="reset" class="btn btn-limpar px-4">
                    <i class="bi bi-eraser"></i> Limpar
                </button>

                <button type="submit" class="btn btn-salvar px-4">
                    <i class="bi bi-check-circle"></i> Salvar
                </button>

                <button type="submit" class="btn btn-alterar px-4">
                    <i class="bi bi-pencil"></i> Alterar
                </button>

                <button type="submit" class="btn btn-excluir px-4">
                    <i class="bi bi-trash"></i> Excluir
                </button>

            </div>

        </form>

        <!-- TABLE -->
        <table class="table table-striped table-hover table-bordered mt-4">

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