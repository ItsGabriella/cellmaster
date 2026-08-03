<?php
session_start();

// Garante o caminho correto para o arquivo de conexão
if (file_exists("conexaoBD.php")) {
    include("conexaoBD.php");
} elseif (file_exists("php/conexaoBD.php")) {
    include("php/conexaoBD.php");
} elseif (file_exists("../conexaoBD.php")) {
    include("../conexaoBD.php");
} elseif (file_exists("../php/conexaoBD.php")) {
    include("../php/conexaoBD.php");
}

// Suporte para ambas as variáveis de conexão ($conexao ou $conn)
$conexao = $conn ?? $conexao ?? null;

if (!$conexao) {
    die("Erro de Conexão: Não foi possível conectar ao banco de dados.");
}

// Identifica o utilizador e o tipo
$id = $_POST["nIdFuncionario"] ?? $_SESSION["idcliente"] ?? $_SESSION["idfuncionario"] ?? $_SESSION["id"] ?? null;
$idCargo = $_SESSION['cargos_idcargos'] ?? $_SESSION['usuario_cargo'] ?? 0;
$tipoUsuario = strtolower($_SESSION['tipo_usuario'] ?? '');

$isCliente = ($idCargo == 4 || $tipoUsuario === 'cliente' || isset($_SESSION["idcliente"]));

$nome = trim($_POST["nNome"] ?? $_POST["nome"] ?? '');
$email = trim($_POST["nEmail"] ?? $_POST["email"] ?? '');
$senha = $_POST["nSenha"] ?? $_POST["nova_senha"] ?? '';
$confirmar = $_POST["nConfirmarSenha"] ?? $_POST["confirmar_senha"] ?? '';

if (!$id) {
    die("<script>alert('Sessão expirada ou utilizador inválido.'); window.location.href='../index.php';</script>");
}

/* =========================================
   1. ATUALIZAR FOTO (Apenas Funcionário)
   ========================================= */
if (!$isCliente && isset($_FILES["fotoPerfil"]) && $_FILES["fotoPerfil"]["name"] != "") {

    $ext = pathinfo($_FILES["fotoPerfil"]["name"], PATHINFO_EXTENSION);
    $nomeFoto = uniqid() . "." . strtolower($ext);
    $destino = "../img/perfil/" . $nomeFoto;

    if (!is_dir("../img/perfil")) {
        mkdir("../img/perfil", 0777, true);
    }

    if (move_uploaded_file($_FILES["fotoPerfil"]["tmp_name"], $destino)) {
        $stmtFoto = $conexao->prepare("UPDATE funcionario SET foto=? WHERE idfuncionario=?");
        $stmtFoto->bind_param("si", $nomeFoto, $id);
        $stmtFoto->execute();
        $stmtFoto->close();

        $_SESSION["foto"] = $nomeFoto;
    }
}

/* =========================================
   2. ATUALIZAR NOME E E-MAIL
   ========================================= */
if (!empty($nome) && !empty($email)) {
    if ($isCliente) {
        // Atualiza na tabela CLIENTE
        $stmtUser = $conexao->prepare("UPDATE cliente SET nome_clien=?, email_clien=? WHERE idcliente=?");
    } else {
        // Atualiza na tabela FUNCIONARIO
        $stmtUser = $conexao->prepare("UPDATE funcionario SET nome_func=?, email_func=? WHERE idfuncionario=?");
    }

    $stmtUser->bind_param("ssi", $nome, $email, $id);
    $stmtUser->execute();
    $stmtUser->close();

    // Atualiza as variáveis na Sessão
    $_SESSION["nome"] = $nome;
    $_SESSION["usuario_nome"] = $nome;
    $_SESSION["email"] = $email;
    if (!$isCliente) {
        $_SESSION["nome_func"] = $nome;
    }
}

/* =========================================
   3. ALTERAR SENHA
   ========================================= */
if (!empty($senha)) {

    if ($senha !== $confirmar) {
        die("<script>alert('As senhas não coincidem!'); window.history.back();</script>");
    }

    // Criptografa a senha com MD5
    $senhaHash = md5($senha);

    if ($isCliente) {
        // Atualiza a senha do CLIENTE
        $stmtSenha = $conexao->prepare("UPDATE cliente SET senha=? WHERE idcliente=?");
    } else {
        // Atualiza a senha do FUNCIONÁRIO
        $stmtSenha = $conexao->prepare("UPDATE funcionario SET senha_func=? WHERE idfuncionario=?");
    }

    $stmtSenha->bind_param("si", $senhaHash, $id);
    $stmtSenha->execute();
    $stmtSenha->close();
}

$conexao->close();

echo "<script>alert('Dados atualizados com sucesso!'); window.location.href='../configuracoes.php';</script>";
exit;
?>