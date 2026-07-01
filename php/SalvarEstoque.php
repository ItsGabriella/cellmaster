<?php

    include('funcoes.php');

    $idPeca   = $_GET["id"];
    $peca = $_POST["nPeca"];
    $categoria = $_POST["nCategoria"];
    $quantidade  = $_POST["nQuantidade"];
    $valorPeca   = $_POST["nValor"];
    $estoqueMin = $_POST["nEstoqueMin"];
    $funcao   = $_GET["funcao"];
    

    include("conexaoBD.php");

    //Validar se é Inclusão ou Alteração
    if($funcao == "I"){

        //Busca o próximo ID na tabela
        $idPeca = proxIdPeca();

        //INSERT
        $sql = "INSERT INTO peca (idpeca,nome_peca, categoria, qtdade_peca, valor_unit, status) "
                ." VALUES (".$idPeca.","
                .$peca.","
                .$idCategoria.","
                .$quantidade.","
                .$valorPeca.","
                .$status.");";

    }elseif($funcao == "U"){
        //UPDATE
        $sql = "UPDATE peca "
                    ." SET nome_peca = ".$peca.", "
                    ." categoria = ".$categoria.", "
                    ." qtdade_peca = ".$quantidade."', "
                    ." valor_unit = ".$valorPeca."', "
                    ." estoque_min = ".$status.");";

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