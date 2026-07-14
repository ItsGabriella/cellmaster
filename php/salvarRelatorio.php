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

        //Busca o próximo ID na tabela
        $idPeca = proxIdRelatorio();

        //INSERT
        $sql = "INSERT INTO relatorio (idrelatorio, relatorio, tipo, data, responsavel, exportado, status) "
                ." VALUES (
                ".$idRelatorio.",
                '".$relatorio."',
                '".$tipo."',
                ".$data.",
                ".$responsavel.",


                
                ".$status.",
                ".$estoqueMin.");";

    }elseif($funcao == "U"){
        //UPDATE
        $sql = "UPDATE peca "
                    ." SET nome_peca = '".$peca."', "
                    ." categoria = '".$categoria."', "
                    ." qtdade_peca = ".$quantidade.", "
                    ." valor_unit = ".$valorPeca.", "
                    ." estoque_min = ".$estoqueMin

                ." WHERE idpeca = ".$idPeca.";";

    }elseif($funcao == "D"){
        //DELETE
        $sql = "DELETE FROM peca "
                ." WHERE idpeca = ".$idPeca.";";
    }

    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    header("location: ../estoque.php");

?>