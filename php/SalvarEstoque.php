<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';

    $peca         = $_POST["nPeca"] ?? "";
    $categoria    = $_POST["nCategoria"] ?? "";
    $quantidade   = $_POST["nQuantidade"] ?? 0;
    $valorPeca    = $_POST["nValor"] ?? 0;
    $estoqueMin   = $_POST["nEstoqueMin"] ?? 0;
    $funcao       = $_GET["funcao"] ?? "";
    $idPeca       = intval($_GET["codigo"] ?? 0);

    $sql = "";
    $mensagemNotificacao = "";
    $tipoNotificacao = "info";

    // Validar se é Inclusão, Alteração ou Exclusão
    if ($funcao == "I") {
        $idPeca = proxIdPeca();

        $sql = "INSERT INTO peca (idpeca, nome_peca, categoria, qtdade_peca, valor_unit, estoque_min) 
                VALUES (
                    ".$idPeca.",
                    '".mysqli_real_escape_string($conn, $peca)."',
                    '".mysqli_real_escape_string($conn, $categoria)."',
                    ".intval($quantidade).",
                    ".floatval($valorPeca).",
                    ".intval($estoqueMin)."
                );";

        $mensagemNotificacao = "Cadastrou a peça: " . $peca;
        $tipoNotificacao = "sucesso";

    } elseif ($funcao == "U") {
        $sql = "UPDATE peca SET 
                    nome_peca = '".mysqli_real_escape_string($conn, $peca)."', 
                    categoria = '".mysqli_real_escape_string($conn, $categoria)."', 
                    qtdade_peca = ".intval($quantidade).", 
                    valor_unit = ".floatval($valorPeca).", 
                    estoque_min = ".intval($estoqueMin)."
                WHERE idpeca = ".$idPeca.";";

        $mensagemNotificacao = "Alterou a peça: " . $peca . " (ID #" . $idPeca . ")";
        $tipoNotificacao = "alerta";

    } elseif ($funcao == "D") {
        // --- INÍCIO DA VALIDAÇÃO DE VÍNCULO COM OS / ORÇAMENTO ---

        // NOTA: Ajuste o nome da tabela e coluna conforme a estrutura do seu banco de dados.
        // Exemplo: 'ordemservico_has_peca', 'itens_os', 'orcamento_peca' etc.
        $queryVerificaOS = "SELECT COUNT(*) AS total FROM peca_orcamento WHERE peca_idpeca = {$idPeca}";
        $resVerifica = mysqli_query($conn, $queryVerificaOS);

        if ($resVerifica) {
            $totalUso = mysqli_fetch_assoc($resVerifica)['total'] ?? 0;

            if ($totalUso > 0) {
                // Fecha a conexão com o banco
                mysqli_close($conn);

                // Define mensagem de erro na sessão para exibição no estoque.php
                $_SESSION['mensagem_erro'] = "Ação bloqueada: A peça (ID #{$idPeca}) não pode ser excluída pois está vinculada a uma Ordem de Serviço ou Orçamento!";

                header("Location: ../estoque.php");
                exit();
            }
        }
        // --- FIM DA VALIDAÇÃO ---

        $sql = "DELETE FROM peca WHERE idpeca = ".$idPeca.";";

        $mensagemNotificacao = "Excluiu a peça ID #" . $idPeca;
        $tipoNotificacao = "perigo";
    }

    if (!empty($sql)) {
        $result = mysqli_query($conn, $sql);

        if ($result && !empty($mensagemNotificacao)) {
            registrarNotificacao($conn, $mensagemNotificacao, $usuarioLogado, $tipoNotificacao);
        }
    }

    mysqli_close($conn);

    header("Location: ../estoque.php");
    exit();
?>