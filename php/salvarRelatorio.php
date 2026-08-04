<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';
    $relatorio     = $_POST["nRelatorio"] ?? '';
    $tipo          = $_POST["nTipo"] ?? '';
    $data          = $_POST["nData"] ?? '';         // Data de Geração/Criação
    $data_inicio   = $_POST["nDataInicio"] ?? '';   // Período De
    $data_fim      = $_POST["nDataFim"] ?? '';      // Período Até
    $responsavel   = $_POST["nResponsavel"] ?? '';
    $status        = $_POST["nStatus"] ?? '';
    $funcao        = $_GET["funcao"] ?? '';
    $idRelatorio   = $_GET["codigo"] ?? 0;

    $sql = "";
    $mensagemNotificacao = "";
    $tipoNotificacao = "";

    // Inclusão (Novo Relatório)
    if($funcao == "I"){


        $idRelatorio = proxIdRelatorio();
        $exportado_inicial = "Não"; // Todo relatório novo começa como Não exportado

        $sql = "INSERT INTO relatorio (idrelatorio, nome_relatorio, tipo, geracao_data, data_inicio, data_fim, responsavel, exportado, status) 
                VALUES (
                    $idRelatorio,
                    '$relatorio',
                    '$tipo',
                    " . (!empty($data) ? "'$data'" : "NULL") . ",
                    " . (!empty($data_inicio) ? "'$data_inicio'" : "NULL") . ",
                    " . (!empty($data_fim) ? "'$data_fim'" : "NULL") . ",
                    '$responsavel',
                    '$exportado_inicial',
                    '$status'
                );";

        $mensagemNotificacao = "Cadastrou o relatório: " . $relatorio;
        $tipoNotificacao = "sucesso";

    // Alteração / Atualização (O campo 'exportado' NÃO é alterado aqui)
    }elseif($funcao == "U"){

        // Trata os campos de data para aceitarem string de data ou enviar NULL se estiverem vazios
        $sqlData        = !empty($data) ? "geracao_data = '$data'," : "geracao_data = NULL,";
        $sqlDataInicio  = !empty($data_inicio) ? "data_inicio = '$data_inicio'," : "data_inicio = NULL,";
        $sqlDataFim     = !empty($data_fim) ? "data_fim = '$data_fim'," : "data_fim = NULL,";

        $sql = "UPDATE relatorio SET 
                    nome_relatorio = '$relatorio',
                    tipo = '$tipo',
                    $sqlData
                    $sqlDataInicio
                    $sqlDataFim
                    responsavel = '$responsavel',
                    status = '$status',
                    data_alteracao = NOW()
                WHERE idrelatorio = $idRelatorio;";

        $mensagemNotificacao = "Alterou o relatório: " . $relatorio . " (ID #" . $idRelatorio . ")";
        $tipoNotificacao = "alerta";

    // Exclusão
    }elseif($funcao == "D"){

        $sql = "DELETE FROM relatorio WHERE idrelatorio = ".$idRelatorio.";";
        $mensagemNotificacao = "Excluiu o relatório ID #" . $idRelatorio;
        $tipoNotificacao = "perigo";

    }

    if (!empty($sql)) {
        $result = mysqli_query($conn, $sql);

        // Se a query deu certo, registra a notificação
        if ($result && !empty($mensagemNotificacao)) {
            registrarNotificacao($conn, $mensagemNotificacao, $usuarioLogado, $tipoNotificacao);
        }
    }

    mysqli_close($conn);

    // Redireciona de volta para a tela de relatórios
    header("location: ../relatorio.php");
    exit();
?>