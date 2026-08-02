<?php
session_start();

include("conexaoBD.php"); // Certifique-se de que a variável de conexão é $conn ou $conexao (ajustado abaixo)

// Suporte para ambas as variáveis de conexão
$conexao = $conn ?? $conexao;

$id = $_POST["nIdFuncionario"] ?? $_SESSION["id"] ?? null;
$nome = trim($_POST["nNome"] ?? '');
$email = trim($_POST["nEmail"] ?? '');
$senha = $_POST["nSenha"] ?? '';
$confirmar = $_POST["nConfirmarSenha"] ?? '';

if (!$id) {
    die("Sessão expirada ou utilizador inválido.");
}

/* ==========================
   1. ATUALIZAR FOTO
   ========================== */
if (isset($_FILES["fotoPerfil"]) && $_FILES["fotoPerfil"]["name"] != "") {

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

        $_SESSION["foto"] = $nomeFoto;
    }
}

/* ==========================
   2. ATUALIZAR NOME E E-MAIL
   ========================== */

   
if (!empty($nome) && !empty($email)) {
    // Atualiza nome e email no banco
    $stmtUser = $conexao->prepare("UPDATE funcionario SET nome_func=?, email_func=? WHERE idfuncionario=?");
    $stmtUser->bind_param("ssi", $nome, $email, $id);
    $stmtUser->execute();

    // Atualiza as variáveis da sessão
    $_SESSION["nome"] = $nome;
    $_SESSION["email"] = $email;
}

/* ==========================
   3. ALTERAR SENHA
   ========================== */
if (!empty($senha)) {

    if ($senha !== $confirmar) {
        die("<script>alert('As senhas não coincidem!'); window.history.back();</script>");
    }

    // Se o seu login usa md5 (conforme o validaAcesso.php):
    $senhaHash = md5($senha);

    // Nota: Se optar por password_hash no futuro, utilize:
    // $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmtSenha = $conexao->prepare("UPDATE funcionario SET senha_func=? WHERE idfuncionario=?");
    $stmtSenha->bind_param("si", $senhaHash, $id);
    $stmtSenha->execute();
}

header("Location: ../configuracoes.php?status=sucesso");
exit;
?>