<?php
include("conexaoBD.php");

$idos = (int)($_GET['id'] ?? 0);

$erroEncontrado = false;
$tituloErro = "Ação Proibida!";
$mensagemErro = "";

if ($idos > 0) {
    // 1. Verificar o status atual da Ordem de Serviço no banco de dados
    $checkOS = $conn->prepare("SELECT status_os FROM ordem_servico WHERE idos = ?");
    $checkOS->bind_param("i", $idos);
    $checkOS->execute();
    $resOS = $checkOS->get_result();

    if ($rowOS = $resOS->fetch_assoc()) {
        $statusAtual = mb_strtolower(trim($rowOS['status_os'] ?? ''));

        // Trava de segurança: impede a exclusão se a OS estiver Aberta ou Em Andamento
        if (in_array($statusAtual, ['aberta', 'em andamento', 'em_andamento'])) {
            $erroEncontrado = true;
            $mensagemErro = "Não é possível excluir a Ordem de Serviço #{$idos} pois ela está em andamento ou aberta! Altere o status para Concluído ou Entregue primeiro.";
        }
    } else {
        $erroEncontrado = true;
        $mensagemErro = "A Ordem de Serviço solicitada não foi encontrada no sistema.";
    }

    // 2. Se passou na verificação, realiza a exclusão segura
    if (!$erroEncontrado) {
        $stmt = $conn->prepare("DELETE FROM ordem_servico WHERE idos = ?");
        $stmt->bind_param("i", $idos);

        if ($stmt->execute()) {
            header("Location: ordem_servico.php?sucesso=excluido");
            exit;
        } else {
            $erroEncontrado = true;
            $mensagemErro = "Ocorreu um erro no banco de dados ao tentar excluir a Ordem de Serviço.";
        }
    }
} else {
    $erroEncontrado = true;
    $mensagemErro = "Identificador de Ordem de Serviço inválido.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ação Proibida - CellMaster</title>
    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body>

<?php if ($erroEncontrado): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: '<?php echo addslashes($tituloErro); ?>',
            text: '<?php echo addslashes($mensagemErro); ?>',
            confirmButtonColor: '#1b4d22',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../ordens_servico.php';
            }
        });
    });
</script>
<?php endif; ?>

</body>
</html>