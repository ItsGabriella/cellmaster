<?php

$arquivo = "orcamentos.json";

// Cria o arquivo caso não exista
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([]));
}

// Carrega dados
$orcamentos = json_decode(file_get_contents($arquivo), true);

function gerarID($orcamentos)
{
    if (count($orcamentos) == 0) {
        return 1001;
    }

    $ids = array_column($orcamentos, 'id');

    return max($ids) + 1;
}


if (isset($_POST['salvar'])) {

    $novo = [

        'id' => gerarID($orcamentos),

        'cliente' => $_POST['cliente'] ?? '',

        'telefone' => $_POST['telefone'] ?? '',

        'marca' => $_POST['marca'] ?? '',

        'modelo' => $_POST['modelo'] ?? '',

        'imei' => $_POST['imei'] ?? '',

        'defeito' => $_POST['defeito'] ?? '',

        'observacao' => $_POST['observacao'] ?? '',

        'peca' => floatval($_POST['peca']),

        'mao_obra' => floatval($_POST['mao_obra']),

        'desconto' => floatval($_POST['desconto']),

        'total' => floatval($_POST['total']),

        'status' => 'Pendente',

        'data' => date('d/m/Y H:i')
    ];

    $orcamentos[] = $novo;

    file_put_contents(
        $arquivo,
        json_encode(
            $orcamentos,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );

    header("Location: orcamentos.php");
    exit;
}

if (isset($_GET['aprovar'])) {

    $id = intval($_GET['aprovar']);

    foreach ($orcamentos as &$orcamento) {

        if ($orcamento['id'] == $id) {

            $orcamento['status'] = 'Aprovado';
        }
    }

    file_put_contents(
        $arquivo,
        json_encode(
            $orcamentos,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );

    header("Location: orcamentos.php");
    exit;
}

if (isset($_GET['reprovar'])) {

    $id = intval($_GET['reprovar']);

    foreach ($orcamentos as &$orcamento) {

        if ($orcamento['id'] == $id) {

            $orcamento['status'] = 'Reprovado';
        }
    }

    file_put_contents(
        $arquivo,
        json_encode(
            $orcamentos,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );

    header("Location: orcamentos.php");
    exit;
}


if (isset($_GET['excluir'])) {

    $id = intval($_GET['excluir']);

    $orcamentos = array_filter(
        $orcamentos,
        function ($orcamento) use ($id) {

            return $orcamento['id'] != $id;
        }
    );

    file_put_contents(
        $arquivo,
        json_encode(
            array_values($orcamentos),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );

    header("Location: orcamentos.php");
    exit;
}


$totalOrcamentos = count($orcamentos);

$aprovados = count(
    array_filter(
        $orcamentos,
        fn($o) => $o['status'] == 'Aprovado'
    )
);

$reprovados = count(
    array_filter(
        $orcamentos,
        fn($o) => $o['status'] == 'Reprovado'
    )
);

$pendentes = count(
    array_filter(
        $orcamentos,
        fn($o) => $o['status'] == 'Pendente'
    )
);

$valorTotal = array_sum(
    array_column(
        $orcamentos,
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
CELLMASTER - Orçamentos
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">
<style>

:root{
     --primary:#2e7d32;
    --dark:#1b4332;
    --success:#40916c;
    --danger:#dc3545;
    --warning:#fbc02d;
    --light:#f1f8f4;
}

body{
    background:var(--light);
    overflow-x:hidden;
    font-family:'Segoe UI', sans-serif;
}


.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:260px;
    height:100vh;
    background:#1b4332;
    transition:.3s;
    z-index:1000;
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
    border-bottom:1px solid #2d6a4f;;
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
    background:#2d6a4f;
}

.sidebar ul li a{
    color:white;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:12px;
}

.sidebar.close .text-menu{
    display:none;
}


.content{
    margin-left:260px;
    transition:.3s;
    padding:20px;
}

.content.expand{
    margin-left:80px;
}


.topbar{
    background:white;
    border-radius:15px;
    padding:15px 20px;
    box-shadow:0 2px 10px rgba(51, 180, 37, 0.08);
    margin-bottom:20px;
}


.card-info{
    border:none;
    border-radius:15px;
    color:white;
    overflow:hidden;
}

.card-info .card-body{
    padding:25px;
}

.card-info h2{
    font-weight:bold;
}

.bg-aprovado{
    background:linear-gradient(
    135deg,
    #198754,
    #20c997
    );
}

.bg-reprovado{
    background:linear-gradient(
    135deg,
    #dc3545,
    #ff6b6b
    );
}

.bg-pendente{
    background:linear-gradient(
    135deg,
    #ffc107,
    #ffda6a
    );
    color:#212529;
}

.bg-total{
     background:linear-gradient(
    135deg,
    #1b4332,
    #40916c
    );
}


.card-form{
    border:none;
    border-radius:15px;
    box-shadow:0 2px 15px rgba(40, 156, 73, 0.08);
}

.card-header{
    border-radius:15px 15px 0 0 !important;
}

.form-label{
    font-weight:600;
}


.total-box{
    background:#40916c;
    border-radius:12px;
    padding:15px;
    text-align:center;
}

.total-box h2{
    margin:0;
    font-weight:bold;
}


.btn{
    border-radius:10px;
}

.btn-action{
    min-width:100px;
}

.table{
    vertical-align:middle;
}

.table thead{
    background:#1b4332;
    color:white;
}

.card-table{
    border:none;
    border-radius:15px;
    box-shadow:0 2px 15px rgba(38, 142, 57, 0.08);
}

@media(max-width:768px){

    .sidebar{
        width:80px;
    }

    .content{
        margin-left:80px;
    }

    .text-menu{
        display:none;
    }
}

</style>

</head>

<body>


<div class="sidebar" id="sidebar">

    <div class="logo">
        CELLMASTER
    </div>

    <ul>

        <li>
            <a href="#">
                <i class="bi bi-speedometer2"></i>
                <span class="text-menu">Dashboard</span>
            </a>
        </li>

         <li>
            <a href="#">
                <i class="bi bi-people"></i>
                <span class="text-menu">Clientes</span>
            </a>
        </li>

         <li>
            <a href="#">
                <i class="bi bi-people"></i>
                <span class="text-menu">Funcionarios</span>
            </a>
        </li>


        <li>
            <a href="#">
                <i class="bi bi-file-earmark-text"></i>
                <span class="text-menu">Orçamentos</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-tools"></i>
                <span class="text-menu">Ordens de Serviço</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-tools"></i>
                <span class="text-menu">Serviços</span>
            </a>
        </li>

         <li>
            <a href="#">
                <i class="bi bi-file-earmark-text"></i>
                <span class="text-menu">Estoque</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class="bi bi-gear"></i>
                <span class="text-menu">Configurações</span>
            </a>
        </li>

    </ul>

</div>


<div class="content" id="content">

    <div class="topbar d-flex justify-content-between align-items-center">

        <button
            class="btn text-white"
                style="background:#2e7d32;"
                id="btnMenu">

            <i class="bi bi-list"></i>

        </button>

        <h4 class="m-0">
            Gestão de Orçamentos
        </h4>

    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">

            <div class="card card-info bg-total">

                <div class="card-body">

                    <h6>Total Orçamentos</h6>

                    <h2>
                        <?= $totalOrcamentos ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card card-info bg-aprovado">

                <div class="card-body">

                    <h6>Aprovados</h6>

                    <h2>
                        <?= $aprovados ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card card-info bg-reprovado">

                <div class="card-body">

                    <h6>Reprovados</h6>

                    <h2>
                        <?= $reprovados ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card card-info bg-pendente">

                <div class="card-body">

                    <h6>Pendentes</h6>

                    <h2>
                        <?= $pendentes ?>
                    </h2>

                </div>

            </div>

        </div>

    </div>


    <div class="alert mb-4"
            style="background:#d8f3dc;
            border:1px solid #74c69d;
            color:#1b4332;">

        <strong>
            Valor Total Orçado:
        </strong>

        R$
        <?= number_format($valorTotal,2,",",".") ?>

    </div>

    <div class="card card-form">

        <div class="card-header text-white"
                style="background:#2e7d32;">

            Novo Orçamento

        </div>

        <div class="card-body">

            <form method="POST">

                <div class="row">

                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            ID
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= gerarID($orcamentos); ?>"
                            readonly>

                    </div>

                    <div class="col-md-5 mb-3">

                        <label class="form-label">
                            Cliente
                        </label>

                        <input
                            type="text"
                            name="cliente"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-5 mb-3">

                        <label class="form-label">
                            Telefone
                        </label>

                        <input
                            type="text"
                            name="telefone"
                            class="form-control">

                    </div>
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Marca
                        </label>

                        <input
                            type="text"
                            name="marca"
                            class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Modelo
                        </label>

                        <input
                            type="text"
                            name="modelo"
                            class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            IMEI
                        </label>

                        <input
                            type="text"
                            name="imei"
                            class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Defeito Relatado
                        </label>

                        <textarea
                            name="defeito"
                            rows="3"
                            class="form-control"></textarea>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Observações
                        </label>

                        <textarea
                            name="observacao"
                            rows="3"
                            class="form-control"></textarea>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Valor da Peça
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="peca"
                            id="peca"
                            value="0"
                            class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Mão de Obra
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="mao_obra"
                            id="mao_obra"
                            value="0"
                            class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Desconto
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="desconto"
                            id="desconto"
                            value="0"
                            class="form-control">

                    </div>

                    <div class="col-md-12 mb-4">

                        <div class="total-box">

                            <h6>Total do Orçamento</h6>

                            <h2 id="totalTela">
                                R$ 0,00
                            </h2>

                        </div>

                        <input
                            type="hidden"
                            name="total"
                            id="total">

                    </div>

                    <div class="col-md-12">

                        <button
                            type="submit"
                            name="salvar"
                            class="btn btn-success">

                            <i class="bi bi-check-circle"></i>
                            Salvar Orçamento

                        </button>

                        <button
                            type="reset"
                            class="btn btn-secondary">

                            Limpar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <div class="card card-table mt-4">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <span>
                Orçamentos Cadastrados
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
                    class="table table-hover table-bordered"
                    id="tabela">

                    <thead>

                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Aparelho</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th width="280">
                            Ações
                        </th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php foreach($orcamentos as $orcamento): ?>

                    <tr>

                        <td>
                            <?= $orcamento['id'] ?>
                        </td>

                        <td>
                            <?= $orcamento['cliente'] ?>
                        </td>

                        <td>
                            <?= $orcamento['marca'] ?>
                            <?= $orcamento['modelo'] ?>
                        </td>

                        <td>
                            R$
                            <?= number_format(
                                $orcamento['total'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </td>

                        <td>

                            <?php if($orcamento['status']=="Aprovado"): ?>

                                <span class="badge bg-success">
                                    Aprovado
                                </span>

                            <?php elseif($orcamento['status']=="Reprovado"): ?>

                                <span class="badge bg-danger">
                                    Reprovado
                                </span>

                            <?php else: ?>

                                <span class="badge bg-warning text-dark">
                                    Pendente
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= $orcamento['data'] ?>
                        </td>

                        <td>

                            <a
                                href="?aprovar=<?= $orcamento['id'] ?>"
                                class="btn btn-success btn-sm">

                                Aprovar

                            </a>

                            <a
                                href="?reprovar=<?= $orcamento['id'] ?>"
                                class="btn btn-warning btn-sm">

                                Reprovar

                            </a>

                            <a
                                href="?excluir=<?= $orcamento['id'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Deseja excluir?')">

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

<script>


const btnMenu =
document.getElementById("btnMenu");

const sidebar =
document.getElementById("sidebar");

const content =
document.getElementById("content");

btnMenu.addEventListener("click",()=>{

    sidebar.classList.toggle("close");
    content.classList.toggle("expand");

});


const peca =
document.getElementById("peca");

const maoObra =
document.getElementById("mao_obra");

const desconto =
document.getElementById("desconto");

function calcularTotal(){

    let valorPeca =
    parseFloat(peca.value) || 0;

    let valorMao =
    parseFloat(maoObra.value) || 0;

    let valorDesc =
    parseFloat(desconto.value) || 0;

    let total =
    (valorPeca + valorMao) - valorDesc;

    document
    .getElementById("total")
    .value = total;

    document
    .getElementById("totalTela")
    .innerHTML =
    "R$ " +
    total.toLocaleString(
        'pt-BR',
        {
            minimumFractionDigits:2
        }
    );
}

peca.addEventListener(
'input',
calcularTotal
);

maoObra.addEventListener(
'input',
calcularTotal
);

desconto.addEventListener(
'input',
calcularTotal
);

calcularTotal();


document
.getElementById("pesquisa")
.addEventListener(
"keyup",
function(){

let texto =
this.value.toLowerCase();

let linhas =
document.querySelectorAll(
"#tabela tbody tr"
);

linhas.forEach(function(linha){

let conteudo =
linha.innerText.toLowerCase();

linha.style.display =
conteudo.includes(texto)
?
""
:
"none";

});

});

</script>

</body>
</html>