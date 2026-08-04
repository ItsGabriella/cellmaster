<?php
// Ativar exibição de erros para debug caso algo falhe
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexaoBD.php");

$token = $_GET['token'] ?? '';
$erro = "";
$sucesso = false;

if (empty($token)) {
    die("Token de recuperação inválido ou não fornecido.");
}

// 1. Validar se o token existe e não expirou
$sql = "SELECT idcliente FROM cliente WHERE token_recuperacao = ? AND token_expiracao > NOW()";
$stmt = $conn->prepare($sql);

// Se houver erro na query (ex: coluna que não existe), o script avisa exatamente o motivo
if (!$stmt) {
    die("Erro no SQL (Verifique se as colunas de token existem na tabela cliente): " . $conn->error);
}

$stmt->bind_param("s", $token);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("<div style='text-align:center; margin-top:50px; font-family:Arial;'><h3>Link inválido ou expirado.</h3><p>Solicite uma nova recuperação de senha.</p><a href='solicitarSenha.php'>Voltar</a></div>");
}

$usuario = $resultado->fetch_assoc();
$idcliente = $usuario['idcliente'];

// 2. Processar a alteração da senha
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novaSenha = $_POST['nova_senha'];
    $confirmaSenha = $_POST['confirma_senha'];

    if (empty($novaSenha) || strlen($novaSenha) < 6) {
        $erro = "A nova senha deve ter pelo menos 6 caracteres.";
    } elseif ($novaSenha !== $confirmaSenha) {
        $erro = "As senhas não coincidem.";
    } else {
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        // Atualizar a senha e limpar o token
        $updateSql = "UPDATE cliente SET senha = ?, token_recuperacao = NULL, token_expiracao = NULL WHERE idcliente = ?";
        $updateStmt = $conn->prepare($updateSql);

        if (!$updateStmt) {
            die("Erro no SQL de Update: " . $conn->error);
        }

        $updateStmt->bind_param("si", $senhaHash, $idcliente);

        if ($updateStmt->execute()) {
            $sucesso = true;
        } else {
            $erro = "Erro ao atualizar a senha no banco de dados.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - CellMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg,#1d4d1d,#2d7d32);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-custom {
            width: 100%;
            max-width: 480px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(0,0,0,.15);
        }
    </style>
</head>
<body>

<div class="card card-custom p-5 bg-white">
    <h3 class="fw-bold text-center mb-4" style="color: #1b4332;">Nova Senha</h3>

    <?php if ($sucesso): ?>
        <div class="alert alert-success text-center">
            Senha alterada com sucesso! <br><br>
            <a href="../index.php" class="btn btn-success w-100">Ir para o Login</a>
        </div>
    <?php else: ?>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= $erro; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Nova Senha</label>
                <input type="password" name="nova_senha" class="form-control" placeholder="Mínimo de 6 caracteres" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Confirmar Nova Senha</label>
                <input type="password" name="confirma_senha" class="form-control" placeholder="Repita a nova senha" required>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-bold" style="background-color: #198754;">Salvar Nova Senha</button>
        </form>

    <?php endif; ?>
</div>

</body>
</html>