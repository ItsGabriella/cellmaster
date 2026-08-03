<?php
session_start();

include('funcoes.php');
if (file_exists("conexaoBD.php")) {
    include("conexaoBD.php");
} elseif (file_exists("php/conexaoBD.php")) {
    include("php/conexaoBD.php");
}

$conn = $conn ?? $conexao ?? null;

$usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';

$cliente   = $_POST["nCliente"] ?? '';
$endereco  = $_POST["nEndereco"] ?? '';
$CPF       = $_POST["nCPF"] ?? '';
$tel       = $_POST["nTelefone"] ?? '';
$mail      = $_POST["nmail"] ?? '';
$senha     = $_POST["nSenha"] ?? '';

$idCliente = isset($_GET["IDClien"]) ? intval($_GET["IDClien"]) : 0;
$funcao    = isset($_GET["funcao"]) ? $_GET["funcao"] : "";

if ($funcao == "I") {
    $idCliente = proxIdCliente();
    
    // Se a senha estiver vazia no cadastro, define uma padrão (ex: '123456')
    $senhaFinal = !empty($senha) ? md5($senha) : md5('123456');

    $sql = "INSERT INTO cliente (idcliente, nome_clien, endereco_clien, cpf_clien, tel_clien, email_clien, cargos_idcargos, senha)
            VALUES (
                {$idCliente},
                '".mysqli_real_escape_string($conn, $cliente)."',
                '".mysqli_real_escape_string($conn, $endereco)."',
                '".mysqli_real_escape_string($conn, $CPF)."',
                '".mysqli_real_escape_string($conn, $tel)."',
                '".mysqli_real_escape_string($conn, $mail)."',
                4,
                '{$senhaFinal}'
            )";

    $mensagemNotificacao = "Cadastrou o cliente: " . $cliente;
    $tipoNotificacao = "sucesso";

} elseif ($funcao == "U") {
    
    // Se digitou uma nova senha, atualiza o campo de senha
    if (!empty($senha)) {
        $senhaHash = md5($senha);
        $sql = "UPDATE cliente SET
                nome_clien = '".mysqli_real_escape_string($conn, $cliente)."',
                endereco_clien = '".mysqli_real_escape_string($conn, $endereco)."',
                cpf_clien = '".mysqli_real_escape_string($conn, $CPF)."',
                tel_clien = '".mysqli_real_escape_string($conn, $tel)."',
                email_clien = '".mysqli_real_escape_string($conn, $mail)."',
                senha = '{$senhaHash}'
                WHERE idcliente = {$idCliente}";
    } else {
        // Se não informou nova senha, mantém a atual
        $sql = "UPDATE cliente SET
                nome_clien = '".mysqli_real_escape_string($conn, $cliente)."',
                endereco_clien = '".mysqli_real_escape_string($conn, $endereco)."',
                cpf_clien = '".mysqli_real_escape_string($conn, $CPF)."',
                tel_clien = '".mysqli_real_escape_string($conn, $tel)."',
                email_clien = '".mysqli_real_escape_string($conn, $mail)."'
                WHERE idcliente = {$idCliente}";
    }

    $mensagemNotificacao = "Alterou o cliente: " . $cliente . " (ID #" . $idCliente . ")";
    $tipoNotificacao = "alerta";

} elseif ($funcao == "D") {
    $sql = "DELETE FROM cliente WHERE idcliente = {$idCliente}";
    $mensagemNotificacao = "Excluiu o cliente ID #" . $idCliente;
    $tipoNotificacao = "perigo";
}

if (!empty($sql)) {
    $result = mysqli_query($conn, $sql);

    if ($result && !empty($mensagemNotificacao) && function_exists('registrarNotificacao')) {
        registrarNotificacao($conn, $mensagemNotificacao, $usuarioLogado, $tipoNotificacao);
    }
}

header("Location: ../clientes.php");
exit;
?>