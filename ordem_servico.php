<?php

// =====================================================
// CELLMASTER - ORDEM DE SERVIÇO
// =====================================================

$arquivoOS = "os.json";

// Cria arquivo se não existir
if (!file_exists($arquivoOS)) {

    file_put_contents(
        $arquivoOS,
        json_encode([])
    );
}

// Carrega OS
$ordens = json_decode(
    file_get_contents($arquivoOS),
    true
);

if (!$ordens) {
    $ordens = [];
}

// =====================================================
// GERAR NÚMERO DA OS
// =====================================================

function gerarOS($ordens)
{
    if (count($ordens) == 0) {
        return 5001;
    }

    $ids = array_column(
        $ordens,
        'os'
    );

    return max($ids) + 1;
}

// =====================================================
// ALTERAR STATUS
// =====================================================

if (isset($_GET['status'])) {

    $os = intval($_GET['os']);
    $status = $_GET['status'];

    foreach ($ordens as &$ordem) {

        if ($ordem['os'] == $os) {

            $ordem['status'] = $status;
        }
    }

    file_put_contents(
        $arquivoOS,
        json_encode(
            $ordens,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE
        )
    );

    header("Location: ordem_servico.php");
    exit;
}

// =====================================================
// EXCLUIR OS
// =====================================================

if (isset($_GET['excluir'])) {

    $os = intval($_GET['excluir']);

    $ordens = array_filter(
        $ordens,
        function ($ordem) use ($os) {

            return $ordem['os'] != $os;
        }
    );

    file_put_contents(
        $arquivoOS,
        json_encode(
            array_values($ordens),
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE
        )
    );

    header("Location: ordem_servico.php");
    exit;
}

// =====================================================
// DASHBOARD
// =====================================================

$totalOS = count($ordens);

$aguardandoPeca = count(
    array_filter(
        $ordens,
        fn($o) =>
        $o['status']
        == 'Aguardando Peça'
    )
);

$emManutencao = count(
    array_filter(
        $ordens,
        fn($o) =>
        $o['status']
        == 'Em Manutenção'
    )
);

$concluidas = count(
    array_filter(
        $ordens,
        fn($o) =>
        $o['status']
        == 'Concluído'
    )
);

$valorTotal = array_sum(
    array_column(
        $ordens,
        'total'
    )
);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
CELLMASTER - Ordem de Serviço
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">
<style>

:root{
    --primary:#0d6efd;
    --success:#198754;
    --danger:#dc3545;
    --warning:#ffc107;
    --dark:#111827;
    --light:#f5f7fb;
}

body{
    background:var(--light);
    font-family:'Segoe UI',sans-serif;
    overflow-x:hidden;
}

/* =====================================
SIDEBAR
===================================== */

.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    background:var(--dark);
    transition:.3s;
    z-index:999;
}

.sidebar.close{
    width:80px;
}

.logo{
    height:70px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:24px;
    font-weight:bold;
    border-bottom:1px solid #374151;
}

.sidebar ul{
    list-style:none;
    padding:0;
    margin:0;
}

.sidebar ul li{
    padding:15px 20px;
    transition:.3s;
}

.sidebar ul li:hover{
    background:#1f2937;
}

.sidebar ul li a{
    color:white;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:12px;
}

.sidebar.close .menu-text{
    display:none;
}

/* =====================================
CONTENT
===================================== */

.content{
    margin-left:260px;
    padding:20px;
    transition:.3s;
}

.content.expand{
    margin-left:80px;
}

/* =====================================
TOPBAR
===================================== */

.topbar{
    background:white;
    border-radius:15px;
    padding:15px 20px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
    margin-bottom:25px;
}

/* =====================================
CARDS
===================================== */

.card-dashboard{
    border:none;
    border-radius:15px;
    overflow:hidden;
    color:white;
}

.card-dashboard .card-body{
    padding:25px;
}

.card-dashboard h2{
    font-weight:bold;
}

.bg-os{
    background:linear-gradient(
        135deg,
        #0d6efd,
        #4dabf7
    );
}

.bg-peca{
    background:linear-gradient(
        135deg,
        #ffc107,
        #ffd43b
    );
    color:#212529;
}

.bg-manutencao{
    background:linear-gradient(
        135deg,
        #fd7e14,
        #ff922b
    );
}

.bg-concluido{
    background:linear-gradient(
        135deg,
        #198754,
        #20c997
    );
}

/* =====================================
CARD TABELA
===================================== */

.card-table{
    border:none;
    border-radius:15px;
    box-shadow:0 2px 15px rgba(0,0,0,.08);
}

.table{
    vertical-align:middle;
}

.table thead{
    background:#111827;
    color:white;
}

/* =====================================
RESPONSIVO
===================================== */

@media(max-width:768px){

    .sidebar{
        width:80px;
    }

    .content{
        margin-left:80px;
    }

    .menu-text{
        display:none;
    }

}

</style>

</head>

<body>

<!-- ============================
SIDEBAR
============================ -->

<div class="sidebar" id="sidebar">

    <div class="logo">
        CELLMASTER
    </div>

    <ul>

        <li>
            <a href="#">
                <i class="bi bi-speedometer2"></i>
                <span class="menu-text">
                    Dashboard
                </span>
            </a>
        </li>

        <li>
            <a href="orcamentos.php">
                <i class="bi bi-file-earmark-text"></i>
                <span class="menu-text">
                    Orçamentos
                </span>
            </a>
        </li>

        <li>
            <a href="ordem_servico.php">
                <i class="bi bi-tools"></i>
                <span class="menu-text">
                    Ordem de Serviço
                </span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-people"></i>
                <span class="menu-text">
                    Clientes
                </span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-gear"></i>
                <span class="menu-text">
                    Configurações
                </span>
            </a>
        </li>

    </ul>

</div>

<!-- ============================
CONTENT
============================ -->

<div class="content" id="content">

    <!-- TOPO -->

    <div class="topbar d-flex justify-content-between align-items-center">

        <button
            class="btn btn-dark"
            id="btnMenu">

            <i class="bi bi-list"></i>

        </button>

        <h4 class="m-0">
            Gestão de Ordens de Serviço
        </h4>

    </div>

    <!-- DASHBOARD -->

    <div class="row mb-4">

        <div class="col-md-3 mb-3">

            <div class="card card-dashboard bg-os">

                <div class="card-body">

                    <h6>Total de OS</h6>

                    <h2>
                        <?= $totalOS ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card card-dashboard bg-peca">

                <div class="card-body">

                    <h6>Aguardando Peça</h6>

                    <h2>
                        <?= $aguardandoPeca ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card card-dashboard bg-manutencao">

                <div class="card-body">

                    <h6>Em Manutenção</h6>

                    <h2>
                        <?= $emManutencao ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card card-dashboard bg-concluido">

                <div class="card-body">

                    <h6>Concluídas</h6>

                    <h2>
                        <?= $concluidas ?>
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <!-- VALOR TOTAL -->

    <div class="alert alert-success mb-4">

        <strong>
            Valor Total das OS:
        </strong>

        R$
        <?= number_format(
            $valorTotal,
            2,
            ',',
            '.'
        ) ?>

    </div>
        <!-- =====================================
    TABELA DE ORDENS DE SERVIÇO
    ===================================== -->

    <div class="card card-table">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <span>
                Ordens de Serviço
            </span>

            <input
                type="text"
                id="pesquisa"
                class="form-control w-25"
                placeholder="Pesquisar...">

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover"
                    id="tabelaOS">

                    <thead>

                        <tr>
                            <th>OS</th>
                            <th>Cliente</th>
                            <th>Aparelho</th>
                            <th>Técnico</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="420">Ações</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach($ordens as $ordem): ?>

                        <tr>

                            <td>
                                <?= $ordem['os'] ?>
                            </td>

                            <td>
                                <?= $ordem['cliente'] ?? '' ?>
                            </td>

                            <td>
                                <?= ($ordem['marca'] ?? '') . ' ' . ($ordem['modelo'] ?? '') ?>
                            </td>

                            <td>
                                <?= $ordem['tecnico'] ?? 'Não informado' ?>
                            </td>

                            <td>
                                R$
                                <?= number_format(
                                    $ordem['total'] ?? 0,
                                    2,
                                    ',',
                                    '.'
                                ) ?>
                            </td>

                            <td>

                                <?php

                                $status = $ordem['status'] ?? '';

                                switch($status){

                                    case 'Aguardando Peça':
                                        echo '<span class="badge bg-warning text-dark">Aguardando Peça</span>';
                                        break;

                                    case 'Em Manutenção':
                                        echo '<span class="badge bg-primary">Em Manutenção</span>';
                                        break;

                                    case 'Em Testes':
                                        echo '<span class="badge bg-info">Em Testes</span>';
                                        break;

                                    case 'Concluído':
                                        echo '<span class="badge bg-success">Concluído</span>';
                                        break;

                                    case 'Entregue':
                                        echo '<span class="badge bg-dark">Entregue</span>';
                                        break;

                                    default:
                                        echo '<span class="badge bg-secondary">Sem Status</span>';
                                }

                                ?>

                            </td>

                            <td>

                                <a
                                    href="?os=<?= $ordem['os'] ?>&status=Aguardando%20Peça"
                                    class="btn btn-warning btn-sm">

                                    Peça

                                </a>

                                <a
                                    href="?os=<?= $ordem['os'] ?>&status=Em%20Manutenção"
                                    class="btn btn-primary btn-sm">

                                    Manutenção

                                </a>

                                <a
                                    href="?os=<?= $ordem['os'] ?>&status=Em%20Testes"
                                    class="btn btn-info btn-sm">

                                    Testes

                                </a>

                                <a
                                    href="?os=<?= $ordem['os'] ?>&status=Concluído"
                                    class="btn btn-success btn-sm">

                                    Concluir

                                </a>

                                <a
                                    href="?os=<?= $ordem['os'] ?>&status=Entregue"
                                    class="btn btn-dark btn-sm">

                                    Entregar

                                </a>

                                <a
                                    href="?excluir=<?= $ordem['os'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Deseja excluir esta OS?')">

                                    Excluir

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- =====================================
JAVASCRIPT
===================================== -->

<script>

// ===============================
// MENU RETRÁTIL
// ===============================

const btnMenu =
document.getElementById("btnMenu");

const sidebar =
document.getElementById("sidebar");

const content =
document.getElementById("content");

btnMenu.addEventListener(
'click',
function(){

    sidebar.classList.toggle('close');
    content.classList.toggle('expand');

}
);

// ===============================
// PESQUISA
// ===============================

document
.getElementById("pesquisa")
.addEventListener(
"keyup",
function(){

    let filtro =
    this.value.toLowerCase();

    let linhas =
    document.querySelectorAll(
        "#tabelaOS tbody tr"
    );

    linhas.forEach(function(linha){

        let texto =
        linha.innerText.toLowerCase();

        linha.style.display =
        texto.includes(filtro)
        ?
        ""
        :
        "none";

    });

}
);

</script>

</body>
</html>