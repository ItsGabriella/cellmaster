<?php
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';

    $cliente = $_POST["nCliente"];
    $endereco  = $_POST["nEndereco"];
    $CPF  = $_POST["nCPF"];
    $tel = $_POST["nTelefone"];
    $mail  = $_POST["nmail"];
    $idCliente = isset($_GET["IDClien"]) ? $_GET["IDClien"] : 0;
    $funcao = isset($_GET["funcao"]) ? $_GET["funcao"] : "";

    include("conexaoBD.php");

    if($funcao == "I"){
        $idCliente = proxIdCliente();
        $sql = "INSERT INTO cliente (idcliente, nome_clien, endereco_clien, cpf_clien, tel_clien, email_clien)
        VALUES(".$idCliente.",
        '".$cliente."',
        '".$endereco."',
        '".$CPF."',
        '".$tel."',
        '".$mail."'
        );";

        $mensagemNotificacao = "Cadastrou o cliente: " . $cliente;
        $tipoNotificacao = "sucesso";

    }elseif($funcao == "U"){
        $sql = "UPDATE cliente  SET
        nome_clien = '".$cliente."',
        endereco_clien = '".$endereco."',
        cpf_clien = '".$CPF."',
        tel_clien = '".$tel."',
        email_clien = '".$mail."'
        WHERE idcliente = ".$idCliente.";";

        $mensagemNotificacao = "Alterou o cliente: " . $cliente. " (ID #" . $idCliente . ")";
        $tipoNotificacao = "alerta";

    }elseif($funcao == "D"){
        $sql = "DELETE FROM cliente "
                ." WHERE idcliente = ".$idCliente.";";
                $mensagemNotificacao = "Excluiu o cliente ID #" . $idCliente;
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
        header("location: ../clientes.php");
        exit();
    

?>