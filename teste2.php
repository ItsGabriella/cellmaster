<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Atendente</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,Helvetica,sans-serif;
}

body{
background:#eef6f1;
overflow-x:hidden;
}

/* SIDEBAR */

.sidebar{

position:fixed;
top:0;
left:0;

width:270px;
height:100vh;

background:linear-gradient(180deg,#14532d,#166534,#15803d);

transition:.4s;

padding:25px;

z-index:999;

box-shadow:8px 0 20px rgba(0,0,0,.15);

}

.sidebar.close{

width:90px;

}

.logo{

display:flex;
align-items:center;
gap:15px;

margin-bottom:40px;

color:#fff;

}

.logo i{

font-size:32px;

background:#22c55e;

width:55px;
height:55px;

border-radius:15px;

display:flex;
justify-content:center;
align-items:center;

}

.logo h3{

font-weight:bold;

white-space:nowrap;

transition:.3s;

}

.sidebar.close .logo h3{

display:none;

}

.menu{

margin-top:20px;

}

.menu a{

display:flex;

align-items:center;

gap:15px;

padding:15px;

margin-bottom:12px;

border-radius:15px;

text-decoration:none;

color:#d1fae5;

transition:.3s;

font-size:16px;

}

.menu a:hover{

background:#22c55e;

color:white;

transform:translateX(5px);

}

.menu a i{

font-size:20px;

min-width:25px;

text-align:center;

}

.sidebar.close span{

display:none;

}

/* CONTEÚDO */

.main{

margin-left:270px;

transition:.4s;

padding:30px;

}

.main.expand{

margin-left:90px;

}

/* TOPBAR */

.topbar{

background:white;

padding:20px 30px;

border-radius:20px;

display:flex;

justify-content:space-between;

align-items:center;

box-shadow:0 8px 25px rgba(0,0,0,.08);

margin-bottom:30px;

}

.left{

display:flex;

align-items:center;

gap:20px;

}

.toggle{

width:50px;

height:50px;

border:none;

border-radius:15px;

background:#16a34a;

color:white;

font-size:20px;

transition:.3s;

}

.toggle:hover{

background:#15803d;

}

.search{

position:relative;

}

.search input{

width:320px;

padding:12px 20px 12px 45px;

border:none;

background:#f3f4f6;

border-radius:30px;

outline:none;

}

.search i{

position:absolute;

left:18px;

top:14px;

color:#16a34a;

}

.right{

display:flex;

align-items:center;

gap:20px;

}

.notification{

width:45px;

height:45px;

border-radius:50%;

background:#dcfce7;

display:flex;

justify-content:center;

align-items:center;

color:#166534;

font-size:18px;

cursor:pointer;

transition:.3s;

}

.notification:hover{

background:#22c55e;

color:white;

}

.profile{

display:flex;

align-items:center;

gap:12px;

}

.profile img{

width:50px;

height:50px;

border-radius:50%;

object-fit:cover;

}

.profile h6{

margin:0;

font-weight:bold;

}

.profile p{

margin:0;

font-size:13px;

color:gray;

}

/* TÍTULO */

.title{

margin-bottom:30px;

}

.title h2{

font-weight:bold;

color:#14532d;

}

.title p{

color:#666;

}

/* CARDS */

.card-dashboard{

background:white;

border:none;

border-radius:20px;

padding:25px;

box-shadow:0 10px 20px rgba(0,0,0,.08);

transition:.3s;

height:100%;

}

.card-dashboard:hover{

transform:translateY(-8px);

}

.icon{

width:70px;

height:70px;

border-radius:18px;

display:flex;

align-items:center;

justify-content:center;

font-size:28px;

color:white;

margin-bottom:15px;

}

.green1{background:#15803d;}
.green2{background:#22c55e;}
.green3{background:#16a34a;}
.green4{background:#166534;}

.number{

font-size:35px;

font-weight:bold;

color:#14532d;

}

.subtitle{

color:#777;

font-size:15px;

}

.section{

margin-top:35px;

background:white;

border-radius:20px;

padding:25px;

box-shadow:0 8px 20px rgba(0,0,0,.08);

}

.table thead{

background:#166534;

color:white;

}

.badge-status{

padding:8px 15px;

border-radius:30px;

font-size:13px;

font-weight:bold;

}

.status1{

background:#dcfce7;
color:#166534;

}

.status2{

background:#fef3c7;
color:#92400e;

}

.status3{

background:#fee2e2;
color:#991b1b;

}

</style>

</head>

<body>

<div class="sidebar" id="sidebar">

<div class="logo">

<i class="fa-solid fa-mobile-screen-button"></i>

<h3>Assistência</h3>

</div>

<div class="menu">

<a href="#">
<i class="fa-solid fa-chart-line"></i>
<span>Dashboard</span>
</a>

<a href="#">
<i class="fa-solid fa-user-plus"></i>
<span>Novo Atendimento</span>
</a>

<a href="#">
<i class="fa-solid fa-users"></i>
<span>Clientes</span>
</a>

<a href="#">
<i class="fa-solid fa-file-invoice-dollar"></i>
<span>Orçamentos</span>
</a>

<a href="#">
<i class="fa-solid fa-screwdriver-wrench"></i>
<span>Ordens de Serviço</span>
</a>

<a href="#">
<i class="fa-solid fa-calendar-days"></i>
<span>Agenda</span>
</a>

<a href="#">
<i class="fa-solid fa-boxes-stacked"></i>
<span>Estoque</span>
</a>

<a href="#">
<i class="fa-solid fa-chart-column"></i>
<span>Relatórios</span>
</a>

<a href="#">
<i class="fa-solid fa-gear"></i>
<span>Configurações</span>
</a>

<a href="#">
<i class="fa-solid fa-right-from-bracket"></i>
<span>Sair</span>
</a>

</div>

</div>

<div class="main" id="main">

<div class="topbar">

<div class="left">

<button class="toggle" onclick="toggleMenu()">
<i class="fa-solid fa-bars"></i>
</button>

<div class="search">

<i class="fa-solid fa-search"></i>

<input
type="text"
placeholder="Pesquisar cliente, OS ou orçamento">

</div>

</div>

<div class="right">

<div class="notification">
<i class="fa-solid fa-bell"></i>
</div>

<div class="profile">

<img src="https://i.pravatar.cc/100" alt="">

<div>

<h6>Atendente</h6>

<p>Online</p>

</div>

</div>

</div>

</div>

<div class="title">

<h2>Dashboard do Atendente</h2>

<p>Controle de atendimentos, orçamentos e ordens de serviço.</p>

</div>
<!-- CARDS -->

<div class="row g-4">

    <div class="col-lg-3 col-md-6">

        <div class="card-dashboard">

            <div class="icon green1">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>

            <div class="number">18</div>

            <div class="subtitle">
                Orçamentos do Dia
            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-dashboard">

            <div class="icon green2">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>

            <div class="number">32</div>

            <div class="subtitle">
                OS em Andamento
            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-dashboard">

            <div class="icon green3">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="number">47</div>

            <div class="subtitle">
                Clientes Atendidos
            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-dashboard">

            <div class="icon green4">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>

            <div class="number">
                R$ 8.560
            </div>

            <div class="subtitle">
                Faturamento do Dia
            </div>

        </div>

    </div>

</div>

<!-- TABELA DE ORDENS -->

<div class="section mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="fw-bold">
            Ordens de Serviço Recentes
        </h4>

        <button class="btn btn-success">
            Nova OS
        </button>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>

                <tr>

                    <th>OS</th>

                    <th>Cliente</th>

                    <th>Aparelho</th>

                    <th>Problema</th>

                    <th>Status</th>

                    <th>Valor</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>#1001</td>

                    <td>João Silva</td>

                    <td>iPhone 13</td>

                    <td>Troca de Tela</td>

                    <td>

                        <span class="badge-status status2">
                            Aguardando Aprovação
                        </span>

                    </td>

                    <td>R$ 950</td>

                </tr>

                <tr>

                    <td>#1002</td>

                    <td>Maria Souza</td>

                    <td>Samsung A54</td>

                    <td>Bateria</td>

                    <td>

                        <span class="badge-status status1">
                            Em Andamento
                        </span>

                    </td>

                    <td>R$ 260</td>

                </tr>

                <tr>

                    <td>#1003</td>

                    <td>Carlos Lima</td>

                    <td>Redmi Note 13</td>

                    <td>Conector</td>

                    <td>

                        <span class="badge-status status3">
                            Cancelado
                        </span>

                    </td>

                    <td>R$ 180</td>

                </tr>

                <tr>

                    <td>#1004</td>

                    <td>Ana Costa</td>

                    <td>Moto G84</td>

                    <td>Câmera</td>

                    <td>

                        <span class="badge-status status1">
                            Concluído
                        </span>

                    </td>

                    <td>R$ 420</td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<div class="row mt-4">

    <div class="col-lg-6">

        <div class="section">

            <h4 class="fw-bold mb-4">

                Estoque Crítico

            </h4>

            <table class="table">

                <thead>

                    <tr>

                        <th>Peça</th>

                        <th>Quantidade</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>Tela iPhone 11</td>

                        <td class="text-danger fw-bold">2</td>

                    </tr>

                    <tr>

                        <td>Bateria A54</td>

                        <td class="text-warning fw-bold">4</td>

                    </tr>

                    <tr>

                        <td>Conector USB-C</td>

                        <td class="text-danger fw-bold">1</td>

                    </tr>

                    <tr>

                        <td>Câmera iPhone XR</td>

                        <td class="text-success fw-bold">12</td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="section">

            <h4 class="fw-bold mb-4">

                Agenda de Entregas

            </h4>

            <table class="table">

                <thead>

                    <tr>

                        <th>Horário</th>

                        <th>Cliente</th>

                        <th>Serviço</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>09:00</td>

                        <td>João Silva</td>

                        <td>Troca de Tela</td>

                    </tr>

                    <tr>

                        <td>11:30</td>

                        <td>Marcos Lima</td>

                        <td>Bateria</td>

                    </tr>

                    <tr>

                        <td>15:00</td>

                        <td>Fernanda Alves</td>

                        <td>Conector</td>

                    </tr>

                    <tr>

                        <td>17:00</td>

                        <td>Juliana Rocha</td>

                        <td>Câmera</td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>
<!-- LINHA INFERIOR -->

<div class="row mt-4">

    <!-- Últimos Orçamentos -->
    <div class="col-lg-8">

        <div class="section">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h4 class="fw-bold">
                    Últimos Orçamentos
                </h4>

                <button class="btn btn-success">
                    Novo Orçamento
                </button>

            </div>

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Cliente</th>
                        <th>Aparelho</th>
                        <th>Serviço</th>
                        <th>Valor</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>Pedro Henrique</td>

                        <td>iPhone 12</td>

                        <td>Troca de Tela</td>

                        <td class="fw-bold text-success">
                            R$ 890
                        </td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                Aguardando
                            </span>
                        </td>

                    </tr>

                    <tr>

                        <td>Larissa Alves</td>

                        <td>Galaxy S23</td>

                        <td>Bateria</td>

                        <td class="fw-bold text-success">
                            R$ 320
                        </td>

                        <td>
                            <span class="badge bg-success">
                                Aprovado
                            </span>
                        </td>

                    </tr>

                    <tr>

                        <td>José Carlos</td>

                        <td>Moto G84</td>

                        <td>Conector</td>

                        <td class="fw-bold text-success">
                            R$ 180
                        </td>

                        <td>
                            <span class="badge bg-danger">
                                Reprovado
                            </span>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    <!-- Clientes Recentes -->

    <div class="col-lg-4">

        <div class="section">

            <h4 class="fw-bold mb-4">

                Clientes Recentes

            </h4>

            <div class="list-group list-group-flush">

                <div class="list-group-item">

                    <strong>Maria Souza</strong>

                    <br>

                    <small class="text-muted">
                        iPhone 13
                    </small>

                </div>

                <div class="list-group-item">

                    <strong>João Pedro</strong>

                    <br>

                    <small class="text-muted">
                        Redmi Note 13
                    </small>

                </div>

                <div class="list-group-item">

                    <strong>Fernanda Lima</strong>

                    <br>

                    <small class="text-muted">
                        Galaxy A54
                    </small>

                </div>

                <div class="list-group-item">

                    <strong>Carlos Henrique</strong>

                    <br>

                    <small class="text-muted">
                        Moto Edge 50
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- INDICADORES -->

<div class="row mt-4">

    <div class="col-lg-4">

        <div class="section">

            <h5 class="fw-bold mb-4">

                Serviços Mais Realizados

            </h5>

            <div class="mb-3">

                <div class="d-flex justify-content-between">

                    <span>Troca de Tela</span>

                    <strong>80%</strong>

                </div>

                <div class="progress">

                    <div class="progress-bar bg-success"
                    style="width:80%"></div>

                </div>

            </div>

            <div class="mb-3">

                <div class="d-flex justify-content-between">

                    <span>Bateria</span>

                    <strong>60%</strong>

                </div>

                <div class="progress">

                    <div class="progress-bar bg-success"
                    style="width:60%"></div>

                </div>

            </div>

            <div>

                <div class="d-flex justify-content-between">

                    <span>Conector</span>

                    <strong>40%</strong>

                </div>

                <div class="progress">

                    <div class="progress-bar bg-success"
                    style="width:40%"></div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="section">

            <h5 class="fw-bold mb-4">

                Resumo do Dia

            </h5>

            <div class="row text-center">

                <div class="col">

                    <h2 class="text-success">18</h2>

                    <p>Orçamentos</p>

                </div>

                <div class="col">

                    <h2 class="text-success">9</h2>

                    <p>Aprovados</p>

                </div>

                <div class="col">

                    <h2 class="text-success">6</h2>

                    <p>Entregues</p>

                </div>

                <div class="col">

                    <h2 class="text-success">R$ 8.560</h2>

                    <p>Receita</p>

                </div>

            </div>

        </div>

    </div>

</div>

</div>

<script>

function toggleMenu(){

const sidebar=document.getElementById("sidebar");

const main=document.getElementById("main");

sidebar.classList.toggle("close");

main.classList.toggle("expand");

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

