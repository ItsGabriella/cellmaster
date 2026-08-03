<?php
include("conexaoBD.php");

$idorcamento = (int)($_GET['id'] ?? 0);

$erroEncontrado = false;
$tituloErro = "Ação Proibida!";
$mensagemErro = "";

if ($idorcamento > 0) {
    // 1. Verificar o status do orçamento
    $checkOrc = $conn->prepare("SELECT status FROM orcamento WHERE idorcamento = ?");
    $checkOrc->bind_param("i", $idorcamento);
    $checkOrc->execute();
    $resOrc = $checkOrc->get_result();

    if ($rowOrc = $resOrc->fetch_assoc()) {
        $statusOrcamento = mb_strtolower(trim($rowOrc['status'] ?? ''));

        if (in_array($statusOrcamento, ['em andamento', 'em_andamento'])) {
            $erroEncontrado = true;
            $mensagemErro = "Não é possível excluir o orçamento #{$idorcamento} pois ele está em andamento.";
        } elseif ($statusOrcamento === 'aprovado') {
            $erroEncontrado = true;
            $mensagemErro = "Não é possível excluir o orçamento #{$idorcamento} pois ele já está aprovado.";
        }
    } else {
        $erroEncontrado = true;
        $mensagemErro = "O orçamento solicitado não foi encontrado no sistema.";
    }

    // 2. Verificar se existe Ordem de Serviço (OS) atrelada
    if (!$erroEncontrado) {
        $checkOS = $conn->prepare("SELECT idos FROM ordem_servico WHERE orcamento_idorcamento = ?");
        $checkOS->bind_param("i", $idorcamento);
        $checkOS->execute();
        $resOS = $checkOS->get_result();

        if ($resOS->num_rows > 0) {
            $erroEncontrado = true;
            $mensagemErro = "Não é possível excluir o orçamento #{$idorcamento} pois ele possui uma Ordem de Serviço (OS) vinculada.";
        }
    }

    // 3. Se passou em todas as verificações, realiza a exclusão
    if (!$erroEncontrado) {
        $stmt = $conn->prepare("DELETE FROM orcamento WHERE idorcamento = ?");
        $stmt->bind_param("i", $idorcamento);

        if ($stmt->execute()) {
            header("Location: ../orcamento.php?sucesso=excluido");
            exit;
        } else {
            $erroEncontrado = true;
            $mensagemErro = "Ocorreu um erro no banco de dados ao tentar excluir o orçamento.";
        }
    }
} else {
    $erroEncontrado = true;
    $mensagemErro = "Identificador de orçamento inválido.";
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
                window.location.href = '../orcamento.php';
            }
        });
    });
</script>
<?php endif; ?>

</body>
</html>