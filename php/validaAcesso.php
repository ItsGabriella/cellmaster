<?php
session_start();

// Garante o caminho correto do conexaoBD.php
if (file_exists("conexaoBD.php")) {
    include("conexaoBD.php");
} elseif (file_exists("../conexaoBD.php")) {
    include("../conexaoBD.php");
}

// Suporte à variável de conexão ($conn ou $conexao)
$conexao = $conn ?? $conexao ?? null;

if (!$conexao) {
    die("Erro de Conexão: Não foi possível conectar ao banco de dados.");
}

$login = $_POST["nLogin"] ?? '';
$senha = $_POST["nSenha"] ?? '';

if (empty($login) || empty($senha)) {
    header("Location: ../index.php");
    exit;
}

// 1. Tenta autenticar na tabela de FUNCIONÁRIOS
$sqlFunc = "SELECT f.*, c.nome_cargos 
            FROM funcionario f
            LEFT JOIN cargos c ON f.cargos_idcargos = c.idcargos
            WHERE f.email_func = ? AND f.senha_func = MD5(?)";

$stmt = $conexao->prepare($sqlFunc);
$stmt->bind_param("ss", $login, $senha);
$stmt->execute();
$resultFunc = $stmt->get_result();

if ($resultFunc && $resultFunc->num_rows > 0) {
    $usuario = $resultFunc->fetch_assoc();

    // Dados na Sessão para Funcionario
    $_SESSION["idfuncionario"]   = $usuario["idfuncionario"];
    $_SESSION["usuario_id"]      = $usuario["idfuncionario"];
    $_SESSION["id"]              = $usuario["idfuncionario"];

    $_SESSION["nome_func"]       = $usuario["nome_func"];
    $_SESSION["nome"]            = $usuario["nome_func"];
    $_SESSION["usuario_nome"]    = $usuario["nome_func"];

    $_SESSION["email"]           = $usuario["email_func"];
    $_SESSION["foto"]            = !empty($usuario["foto"]) ? $usuario["foto"] : 'user.png';

    $_SESSION["cargos_idcargos"] = $usuario["cargos_idcargos"];
    $_SESSION["usuario_cargo"]   = $usuario["cargos_idcargos"];
    $_SESSION["tipo_usuario"]    = $usuario["nome_cargos"] ?? 'Atendente';

    // Cookie "Lembrar-me"
    if (isset($_POST["lembrar"])) {
        setcookie("lembrar_email", $usuario["email_func"], time() + (60 * 60 * 24 * 30), "/");
    } else {
        setcookie("lembrar_email", "", time() - 3600, "/");
    }

    $stmt->close();
    $conexao->close();

    header("Location: ../dashboard.php");
    exit;
}
$stmt->close();

// 2. Se não encontrou no funcionário, tenta autenticar na tabela de CLIENTES
$sqlClien = "SELECT cl.*, c.nome_cargos 
             FROM cliente cl
             LEFT JOIN cargos c ON cl.cargos_idcargos = c.idcargos
             WHERE cl.email_clien = ? AND cl.senha = MD5(?)";

$stmtClien = $conexao->prepare($sqlClien);
$stmtClien->bind_param("ss", $login, $senha);
$stmtClien->execute();
$resultClien = $stmtClien->get_result();

if ($resultClien && $resultClien->num_rows > 0) {
    $cliente = $resultClien->fetch_assoc();

    // Dados na Sessão para Cliente
    $_SESSION["idcliente"]       = $cliente["idcliente"];
    $_SESSION["usuario_id"]      = $cliente["idcliente"];
    $_SESSION["id"]              = $cliente["idcliente"];

    $_SESSION["nome"]            = $cliente["nome_clien"];
    $_SESSION["usuario_nome"]    = $cliente["nome_clien"];

    $_SESSION["email"]           = $cliente["email_clien"];
    $_SESSION["foto"]            = 'user.png';

    $_SESSION["cargos_idcargos"] = $cliente["cargos_idcargos"] ?? 4;
    $_SESSION["usuario_cargo"]   = $cliente["cargos_idcargos"] ?? 4;
    $_SESSION["tipo_usuario"]    = $cliente["nome_cargos"] ?? 'Cliente';

    // Cookie "Lembrar-me"
    if (isset($_POST["lembrar"])) {
        setcookie("lembrar_email", $cliente["email_clien"], time() + (60 * 60 * 24 * 30), "/");
    } else {
        setcookie("lembrar_email", "", time() - 3600, "/");
    }

    $stmtClien->close();
    $conexao->close();

    header("Location: ../dashboard.php");
    exit;
}

// 3. Se não encontrou em nenhuma tabela
if (isset($stmtClien)) { 
    $stmtClien->close(); 
}
$conexao->close();

header("Location: ../index.php?erro=1");
exit;
?>