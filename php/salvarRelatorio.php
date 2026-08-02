<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';
    $relatorio   = $_POST["nRelatorio"];
    $tipo        = $_POST["nTipo"];
    $data        = $_POST["nData"];        // Data de Geração/Criação
    $data_inicio = $_POST["nDataInicio"] ; // Período De
    $data_fim    = $_POST["nDataFim"] ;    // Período Até
    $responsavel = $_POST["nResponsavel"];
    $status      = $_POST["nStatus"];
    $funcao      = $_GET["funcao"];
    $idRelatorio = $_GET["codigo"];

    include("conexaoBD.php");

    // Inclusão (Novo Relatório)
    if($funcao == "I"){

        include('funcaoRelatorio.php');

        $idRelatorio = proxIdRelatorio();
        $exportado = "N";

        $sql = "INSERT INTO relatorio (idrelatorio, nome_relatorio, tipo, geracao_data, data_inicio, data_fim, responsavel, exportado, status) 
                        VALUES (
                            $idRelatorio,
                            '$relatorio',
                            '$tipo',
                            '$data',
                            '$data_inicio',
                            '$data_fim',
                            '$responsavel',
                            '$exportado',
                            '$status'
                        );";

        $mensagemNotificacao = "Cadastrou o relatório: " . $relatorio;
        $tipoNotificacao = "sucesso";

    // Alteração / Atualização
    }elseif($funcao == "U"){

        $updateData = ($data !== "NULL") ? "geracao_data = $data," : "";

        $sql = "UPDATE relatorio SET 
                    nome_relatorio = '$relatorio',
                    tipo = '$tipo',
                    $updateData
                    data_inicio = $data_inicio,
                    data_fim = $data_fim,
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
                // Chama a função passando a conexão, a mensagem, o usuário e o tipo
                registrarNotificacao($conn, $mensagemNotificacao, $usuarioLogado, $tipoNotificacao);
            }
        }

        mysqli_close($conn);

        // Redireciona de volta para a tela de estoque
        header("location: ../relatorio.php");
        exit();
    
?>