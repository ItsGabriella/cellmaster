<?php
session_start();

include("conexaoBD.php");

// Garante o suporte para a variável de conexão
$conexao = $conn ?? $conexao;

$login = $_POST["nLogin"] ?? '';
$senha = $_POST["nSenha"] ?? '';

if (empty($login) || empty($senha)) {
    header("Location: ../index.php");
    exit;
}

// 1. Utiliza Prepared Statements contra SQL Injection
$sql = "SELECT f.*, c.nome_cargos 
        FROM funcionario f
        LEFT JOIN cargos c ON f.cargos_idcargos = c.idcargos
        WHERE f.email_func = ? AND f.senha_func = MD5(?)";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ss", $login, $senha);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // 2. Salva os dados na Sessão de forma segura
    $_SESSION["id"]    = $usuario["idfuncionario"];
    $_SESSION["nome"]  = $usuario["nome_func"];
    $_SESSION["email"] = $usuario["email_func"];
    // Define foto padrão caso o campo no BD esteja vazio
    $_SESSION["foto"]  = !empty($usuario["foto"]) ? $usuario["foto"] : 'user.png';
    $_SESSION["cargo"] = $usuario["nome_cargos"] ?? 'Sem Cargo';

    // 3. Cookie "Lembrar-me"
    if (isset($_POST["lembrar"])) {
        setcookie("lembrar_email", $usuario["email_func"], time() + (60 * 60 * 24 * 30), "/");
    } else {
        setcookie("lembrar_email", "", time() - 3600, "/");
    }

    $stmt->close();
    $conexao->close();

    // Redireciona para a home/painel principal
    header("Location: ../estoque.php");
    exit;

} else {
    if (isset($stmt)) { $stmt->close(); }
    $conexao->close();
    
    header("Location: ../index.php?erro=1");
    exit;
}
?>