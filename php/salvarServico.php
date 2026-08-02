<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';
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

        $mensagemNotificacao = "Cadastrou o serviço: " . $servico;
        $tipoNotificacao = "sucesso";

    }elseif($funcao == "U"){
        //UPDATE
        $sql = "UPDATE servico "
                    ." SET nome_servico = '".$servico."', "
                    ." descricao_servico = '".$descricao."', "
                    ." valor = '".$valorServico."', "
                    ." tempo = '".$tempo."', "
                    ." status = '".$status."'"

                ." WHERE idservico = ".$idServico.";";

                $mensagemNotificacao = "Alterou o serviço: " . $servico . " (ID #" . $idServico . ")";
                $tipoNotificacao = "alerta";

    }elseif($funcao == "D"){
        //DELETE
        $sql = "DELETE FROM servico "
                ." WHERE idservico = ".$idServico.";";

                $mensagemNotificacao = "Excluiu o serviço ID #" . $idServico;
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
        header("location: ../servicos.php");
        exit();

?>