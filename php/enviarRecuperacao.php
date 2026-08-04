<?php
// ATIVAR EXIBIÇÃO DE ERROS TEMPORARIAMENTE PARA DESCOBRIR A CAUSA DA TELA BRANCA
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("conexaoBD.php");

// Importar as classes do PHPMailer (ajuste para '../phpmailer/' pois estamos na pasta 'php/')
require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Por favor, informe um e-mail válido.'); window.history.back();</script>";
        exit;
    }

    // Verificar se o e-mail existe na base de dados
    $sql = "SELECT idcliente AS id, nome_clien AS nome, email_clien AS email FROM cliente WHERE email_clien = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $mensagemSucesso = "Se o e-mail estiver cadastrado, as instruções de recuperação foram enviadas.";

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        $token = bin2hex(random_bytes(32));
        $expiracao = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $updateSql = "UPDATE cliente SET token_recuperacao = ?, token_expiracao = ? WHERE email_clien = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sss", $token, $expiracao, $email);
        
        if ($updateStmt->execute()) {
            $linkRedefinicao = "http://localhost/cellmaster/php/redefinirSenha.php?token=" . $token;

            $mail = new PHPMailer(true);

            try {
                // Configurações do Servidor SMTP (Exemplo com Gmail)
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                // ATENÇÃO: Substitua abaixo pelo seu e-mail real e pela sua Senha de Aplicativo do Google
                $mail->Username   = 'gabriella.galdino1808@gmail.com'; 
                $mail->Password   = 'igid eqdw myst levh'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                // Remetente e Destinatário
                $mail->setFrom('gabriella.galdino1808@gmail.com', 'CellMaster Suporte');
                $mail->addAddress($email, $usuario['nome']);

                // Conteúdo do E-mail
                $mail->isHTML(false);
                $mail->Subject = 'Redefinição de Senha - CellMaster';
                $mail->Body    = "Olá, " . $usuario['nome'] . ".\n\n" .
                                 "Recebemos uma solicitação para redefinir a senha da sua conta.\n" .
                                 "Aceda ao link abaixo para criar uma nova senha (válido por 1 hora):\n\n" .
                                 $linkRedefinicao . "\n\n" .
                                 "Se não solicitou esta alteração, ignore este e-mail.";

                $mail->send();
                echo "<script>alert('$mensagemSucesso'); window.location='../index.php';</script>";
                exit;
            } catch (Exception $e) {
                // Se der erro no PHPMailer, ele vai aparecer na tela em vez de ficar branco
                echo "<div style='font-family: Arial; padding: 20px; color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb;'>";
                echo "<h3>Erro ao enviar o e-mail via SMTP:</h3>";
                echo "<p>{$mail->ErrorInfo}</p>";
                echo "<br><a href='javascript:history.back()'>Voltar</a>";
                echo "</div>";
                exit;
            }
        } else {
            echo "<script>alert('Erro interno ao atualizar o token.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('$mensagemSucesso'); window.location='../index.php';</script>";
        exit;
    }
}
?>