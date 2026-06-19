<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Assistência Técnica</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
body{
    background:#f4f8f5;
    font-family:'Segoe UI',sans-serif;
}

/* SIDEBAR */
.sidebar{
    width:280px;
    min-height:100vh;
    background:linear-gradient(180deg,#0f5132,#198754,#20c997);
    position:fixed;
    left:0;
    top:0;
    padding:25px;
    box-shadow:5px 0 20px rgba(0,0,0,.1);
}

.sidebar h2{
    color:#fff;
    font-weight:700;
    margin-bottom:35px;
    text-align:center;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none;
    color:#fff;
    padding:14px 18px;
    border-radius:12px;
    margin-bottom:10px;
    transition:.3s;
    font-weight:500;
}

.sidebar a:hover{
    background:rgba(255,255,255,.15);
    transform:translateX(5px);
    color:#fff;
}

/* CONTEÚDO */
.content{
    margin-left:300px;
    padding:30px;
}

/* TOPO */
.topbar{
    background:linear-gradient(135deg,#198754,#20c997);
    color:white;
    padding:30px;
    border-radius:20px;
    margin-bottom:30px;
    box-shadow:0 10px 25px rgba(25,135,84,.25);
}

.topbar h1{
    margin-bottom:5px;
}

/* ÁREA PRINCIPAL */
.section-card{
    background:#fff;
    padding:30px;
    border-radius:25px;
    box-shadow:0 5px 25px rgba(0,0,0,.06);
}

/* CARDS */
.card-dashboard{
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
    border-left:6px solid #198754;
    transition:.3s;
}

.card-dashboard:hover{
    transform:translateY(-6px);
}

.icon-card{
    width:60px;
    height:60px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:22px;
}

.bg-green{
    background:linear-gradient(135deg,#198754,#20c997);
}

.bg-blue{
    background:linear-gradient(135deg,#157347,#198754);
}

.bg-orange{
    background:linear-gradient(135deg,#20c997,#198754);
}

.bg-red{
    background:linear-gradient(135deg,#0f5132,#146c43);
}

/* BOTÕES */
.btn-primary{
    background:linear-gradient(135deg,#198754,#20c997);
    border:none;
    font-weight:600;
    box-shadow:0 5px 15px rgba(25,135,84,.3);
}

.btn-primary:hover{
    background:linear-gradient(135deg,#157347,#198754);
}

/* CAIXAS */
.bg-light{
    background:#f8fbf9 !important;
    border-radius:20px !important;
}

/* TABELA */
.table thead{
    background:#e7f7ef;
}

.table th{
    color:#146c43;
    border:none;
}

.table td{
    vertical-align:middle;
}

/* STATUS */
.status{
    display:inline-block;
    padding:6px 14px;
    border-radius:50px;
    font-size:13px;
    font-weight:600;
}

.status-success{
    background:#d1e7dd;
    color:#0f5132;
}

.status-warning{
    background:#fff3cd;
    color:#856404;
}

.status-danger{
    background:#f8d7da;
    color:#842029;
}

/* PROGRESS */
.progress{
    height:12px;
    border-radius:20px;
    overflow:hidden;
}

.progress-bar{
    background:linear-gradient(90deg,#198754,#20c997);
}

h2,h3,h4{
    color:#146c43;
}

@media(max-width:991px){
    .sidebar{
        width:100%;
        min-height:auto;
        position:relative;
    }

    .content{
        margin-left:0;
    }
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>
        <i class="fa-solid fa-mobile-screen-button"></i>
            CELLMASTER
    </h2>

    <a href="#admin">
        <i class="fa-solid fa-chart-line"></i>
        Dashboard Admin
    </a>

    <a href="#atendente">
        <i class="fa-solid fa-headset"></i>
        Atendente
    </a>

    <a href="#tecnico">
        <i class="fa-solid fa-screwdriver-wrench"></i>
        Técnico
    </a>

    <a href="#estoque">
        <i class="fa-solid fa-box"></i>
        Estoque
    </a>

    <a href="#os">
        <i class="fa-solid fa-file-lines"></i>
        Ordens de Serviço
    </a>
</div>

<div class="content">

    <div class="topbar">
        <h1 class="fw-bold">
            Dashboard Loja de Conserto de Celulares
        </h1>
        <p class="mb-0 opacity-75">
            Controle completo da assistência técnica
        </p>
    </div>

    <div id="admin" class="section-card">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Painel Administrativo</h2>
                <p class="text-secondary">Visão geral da empresa</p>

        <div class="row g-4">

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">Faturamento</small>
                            <h3 class="fw-bold mt-2">R$ 48.900</h3>
                        </div>
                        <div class="icon-card bg-green">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">Funcionarios Hoje</small>
                            <h3 class="fw-bold mt-2">11</h3>
                        </div>
                        <div class="icon-card bg-orange">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">Clientes Hoje</small>
                            <h3 class="fw-bold mt-2">37</h3>
                        </div>
                        <div class="icon-card bg-orange">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">Ordens Ativas</small>
                            <h3 class="fw-bold mt-2">190</h3>
                        </div>
                        <div class="icon-card bg-blue">
                            <i class="fa-solid fa-file-circle-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">Serviços Ativos</small>
                            <h3 class="fw-bold mt-2">90</h3>
                        </div>
                        <div class="icon-card bg-blue">
                            <i class="fa-solid fa-file-circle-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">Peça com baixo estoque</small>
                            <h3 class="fw-bold mt-2">Tela - 2</h3>
                        </div>
                        <div class="icon-card bg-red">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">Aguardando Peça</small>
                            <h3 class="fw-bold mt-2">14</h3>
                        </div>
                        <div class="icon-card bg-red">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4 g-4">

            <div class="col-lg-8">
                <div class="bg-light p-4 h-100">
                    <h4 class="fw-bold mb-4">Últimas Ordens de Serviço</h4>

                    <div class="table-responsive">
                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Aparelho</th>
                                    <th>Serviço</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>Carlos Henrique</td>
                                    <td>iPhone 13</td>
                                    <td>Troca de Tela</td>
                                    <td>
                                        <span class="status status-warning">
                                            Em andamento
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Mariana Silva</td>
                                    <td>Samsung S22</td>
                                    <td>Troca de Bateria</td>
                                    <td>
                                        <span class="status status-success">
                                            Finalizado
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Lucas Rocha</td>
                                    <td>Redmi Note 13</td>
                                    <td>Troca de Conector</td>
                                    <td>
                                        <span class="status status-danger">
                                            Aguardando Peça
                                        </span>
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">

                <div class="bg-light p-4 h-100">

                    <h4 class="fw-bold mb-4">Meta Mensal</h4>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Meta Financeira</span>
                            <strong>78%</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar" style="width:78%"></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-4 p-4 mt-4 shadow-sm">

                        <div class="d-flex justify-content-between mb-3">
                            <span>Lucro Hoje</span>
                            <strong class="text-success">
                                +R$ 2.430
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Despesas</span>
                            <strong class="text-danger">
                                -R$ 580
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>