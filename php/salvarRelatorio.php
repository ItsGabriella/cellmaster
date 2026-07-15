<?php



    $relatorio = $_POST["nRelatorio"];
    $tipo = $_POST["nTipo"];
    $data  = $_POST["nData"];
    $responsavel = $_POST["nResponsavel"];
    $status = $_POST["nStatus"];
    $funcao   = $_GET["funcao"];
    $idRelatorio   = $_GET["codigo"];
    

    include("conexaoBD.php");

    //Validar se é Inclusão ou Alteração
    if($funcao == "I"){

        include('funcaoRelatorio.php');

        //Busca o próximo ID na tabela
        $idRelatorio = proxIdRelatorio();
        $exportado = "N";

        //INSERT
        $sql = "INSERT INTO relatorio (idrelatorio, nome_relatorio, tipo, data, responsavel, exportado, status) "
                ." VALUES (
                ".$idRelatorio.",
                '".$relatorio."',
                '".$tipo."',
                '".$data."',
                '".$responsavel."',
                '".$exportado."',
                '".$status."');";

    }elseif($funcao == "U"){
        //UPDATE
        $sql = "UPDATE relatorio "
                    ." SET nome_relatorio = '".$relatorio."', "
                    ." tipo = '".$tipo."', "
                    ." data = '".$data."', "
                    ." responsavel = '".$responsavel."', "
                    ." status = '".$status."'"

                ." WHERE idrelatorio = ".$idRelatorio.";";

    }elseif($funcao == "D"){
        //DELETE
        $sql = "DELETE FROM relatorio "
                ." WHERE idrelatorio = ".$idRelatorio.";";
    }

    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    header("location: ../relatorio.php");

?>