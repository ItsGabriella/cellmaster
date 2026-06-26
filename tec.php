<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Assistência Técnica</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
body{
    background:#f1f5f9;
    font-family:Arial, Helvetica, sans-serif;
}

.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    background:#0f172a;
    color:white;
    padding:25px;
}

.sidebar h2{
    font-weight:bold;
    margin-bottom:35px;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
    color:#cbd5e1;
    padding:12px;
    border-radius:12px;
    margin-bottom:10px;
    transition:0.3s;
}

.sidebar a:hover{
    background:#1e293b;
    color:white;
}

.content{
    margin-left:280px;
    padding:30px;
}

.topbar{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    padding:30px;
    border-radius:25px;
    margin-bottom:30px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

.card-dashboard{
    border:none;
    border-radius:22px;
    padding:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card-dashboard:hover{
    transform:translateY(-5px);
}

.icon-card{
    width:65px;
    height:65px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    color:white;
}

.bg-blue{
    background:#2563eb;
}

.bg-green{
    background:#16a34a;
}

.bg-orange{
    background:#ea580c;
}

.bg-red{
    background:#dc2626;
}

.section-card{
    background:white;
    border-radius:25px;
    padding:25px;
    margin-top:25px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.table td,
.table th{
    vertical-align:middle;
}

.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.status-warning{
    background:#fef3c7;
    color:#b45309;
}

.status-success{
    background:#dcfce7;
    color:#15803d;
}

.status-danger{
    background:#fee2e2;
    color:#b91c1c;
}

.progress{
    height:12px;
    border-radius:20px;
}
</style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-mobile-screen-button"></i> Assistência</h2>

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
        <h1 class="fw-bold">Dashboard Loja de Conserto de Celulares</h1>
        <p class="mb-0 opacity-75">
            Controle completo da assistência técnica
        </p>
    </div>
  <!-- TECNICO -->
 <div id="tecnico" class="section-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Painel Técnico</h2>
                <p class="text-secondary">Controle dos aparelhos</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <h6 class="text-secondary">Consertos Hoje</h6>
                    <h2 class="fw-bold">14</h2>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <h6 class="text-secondary">Aguardando Peça</h6>
                    <h2 class="fw-bold">5</h2>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <h6 class="text-secondary">Finalizados</h6>
                    <h2 class="fw-bold">21</h2>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-dashboard">
                    <h6 class="text-secondary">Prioridade Alta</h6>
                    <h2 class="fw-bold text-danger">3</h2>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-lg-6">
                <div class="bg-light rounded-4 p-4 h-100">
                    <h4 class="fw-bold mb-4">Fila de Serviços</h4>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>iPhone 14 Pro</span>
                            <strong class="text-danger">Alta</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar bg-danger" style="width:85%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Samsung S23</span>
                            <strong class="text-warning">Média</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width:55%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Redmi Note 13</span>
                            <strong class="text-success">Baixa</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar bg-success" style="width:25%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-light rounded-4 p-4 h-100">
                    <h4 class="fw-bold mb-4">Checklist Técnico</h4>

                    <div class="form-check bg-white rounded-4 p-3 mb-3">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">
                            Teste de tela realizado
                        </label>
                    </div>

                    <div class="form-check bg-white rounded-4 p-3 mb-3">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">
                            Teste de bateria realizado
                        </label>
                    </div>

                    <div class="form-check bg-white rounded-4 p-3 mb-3">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">
                            Backup conferido
                        </label>
                    </div>

                    <div class="form-check bg-white rounded-4 p-3">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">
                            Aparelho higienizado
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>