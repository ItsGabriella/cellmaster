<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';

    $funcionario = $_POST["nFuncionario"];
    $cargo  = $_POST["ncargo"];
    $tel = $_POST["nTelefone"];
    $mail  = $_POST["nmail"];
    $idFuncionario = isset($_GET["IDFunc"]) ? $_GET["IDFunc"] : 0;
    $funcao = isset($_GET["funcao"]) ? $_GET["funcao"] : "";

    include("conexaoBD.php");

    if($funcao == "I"){
        $idFuncionario = proxIdFuncionario();
        $sql = "INSERT INTO funcionario (idfuncionario, nome_func, cargos_idcargos, tel_func, email_func)
        VALUES(".$idFuncionario.",
        '".$funcionario."',
        '".$cargo."',
        '".$tel."',
        '".$mail."'
        );";


        $mensagemNotificacao = "Cadastrou o funcionário: " . $funcionario;
        $tipoNotificacao = "sucesso";

    }elseif($funcao == "U"){
        $sql = "UPDATE funcionario  SET
        nome_func = '".$funcionario."',
        cargos_idcargos = '".$cargo."',
        tel_func = '".$tel."',
        email_func = '".$mail."'
        WHERE idfuncionario = ".$idFuncionario.";";

        $mensagemNotificacao = "Alterou o funcionário: " . $funcionario . " (ID #" . $idFuncionario . ")";
        $tipoNotificacao = "alerta";

    }elseif($funcao == "D"){
        $sql = "DELETE FROM funcionario "
                ." WHERE idfuncionario = ".$idFuncionario.";";

                $mensagemNotificacao = "Excluiu o funcionário ID #" . $idFuncionario;
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
        header("location: ../funcionarios.php");
        exit();
    

?>