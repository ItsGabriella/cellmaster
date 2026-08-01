<?php

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

    // Exclusão
    }elseif($funcao == "D"){

        $sql = "DELETE FROM relatorio WHERE idrelatorio = ".$idRelatorio.";";

    }

    mysqli_query($conn, $sql);
    mysqli_close($conn);

    header("Location: ../relatorio.php");
    exit;
?>