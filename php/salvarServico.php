<?php

    include('funcaoServico.php');

    $servico = $_POST["nServico"];
    $descricao = $_POST["nDescricao"];
    $valorServico  = $_POST["nValor"];
    $tempo  = $_POST["nTempo"];
    $status  = $_POST["nStatus"];
    $funcao   = $_GET["funcao"];
    $idServico   = $_GET["codigo"];
    

    include("conexaoBD.php");

    //Validar se é Inclusão ou Alteração
    if($funcao == "I"){

        //Busca o próximo ID na tabela
        $idServico = proxIdServico();

        //INSERT
        $sql = "INSERT INTO servico (idservico, nome_servico, descricao_servico, valor, tempo, status) "
                ." VALUES (
                ".$idServico.",
                '".$servico."',
                '".$descricao."',
                '".$valorServico."',
                '".$tempo."',
                '".$status."');";

    }elseif($funcao == "U"){
        //UPDATE
        $sql = "UPDATE servico "
                    ." SET nome_servico = '".$servico."', "
                    ." descricao_servico = '".$descricao."', "
                    ." valor = '".$valorServico."', "
                    ." tempo = '".$tempo."', "
                    ." status = '".$status."'"

                ." WHERE idservico = ".$idServico.";";

    }elseif($funcao == "D"){
        //DELETE
        $sql = "DELETE FROM servico "
                ." WHERE idservico = ".$idServico.";";
    }

    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    header("location: ../servicos.php");

?>