<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';


    $peca = $_POST["nPeca"];
    $categoria = $_POST["nCategoria"];
    $quantidade  = $_POST["nQuantidade"];
    $valorPeca   = $_POST["nValor"];
    $estoqueMin = $_POST["nEstoqueMin"];
    $funcao   = $_GET["funcao"];
    $idPeca   = $_GET["codigo"];

    $sql = "";
    $mensagemNotificacao = "";
    $tipoNotificacao = "info";
    


    //Validar se é Inclusão ou Alteração
    if($funcao == "I"){

        //Busca o próximo ID na tabela
        $idPeca = proxIdPeca();

        //INSERT
        $sql = "INSERT INTO peca (idpeca, nome_peca, categoria, qtdade_peca, valor_unit, estoque_min) "
                ." VALUES (
                ".$idPeca.",
                '".$peca."',
                '".$categoria."',
                ".$quantidade.",
                ".$valorPeca.",
                ".$estoqueMin.");";

        $mensagemNotificacao = "Cadastrou a peça: " . $peca;
        $tipoNotificacao = "sucesso";

    }elseif($funcao == "U"){
        //UPDATE
        $sql = "UPDATE peca "
                    ." SET nome_peca = '".$peca."', "
                    ." categoria = '".$categoria."', "
                    ." qtdade_peca = ".$quantidade.", "
                    ." valor_unit = ".$valorPeca.", "
                    ." estoque_min = ".$estoqueMin

                    ." WHERE idpeca = ".$idPeca.";";
                $mensagemNotificacao = "Alterou a peça: " . $peca . " (ID #" . $idPeca . ")";
                $tipoNotificacao = "alerta";

    }elseif($funcao == "D"){
        //DELETE
        $sql = "DELETE FROM peca "
                ." WHERE idpeca = ".$idPeca.";";
                $mensagemNotificacao = "Excluiu a peça ID #" . $idPeca;
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
        header("location: ../estoque.php");
        exit();

?>