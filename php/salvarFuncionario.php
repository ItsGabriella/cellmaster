<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';

    $funcionario   = $_POST["nFuncionario"] ?? "";
    $cargo         = $_POST["nCargo"] ?? $_POST["ncargo"] ?? "";
    $tel           = $_POST["nTelefone"] ?? "";
    $mail          = $_POST["nmail"] ?? "";
    $senha         = MD5("Cellmaster123"); // Recebe a senha ou assume a padrão
    $idFuncionario = isset($_GET["IDFunc"]) ? $_GET["IDFunc"] : 0;
    $funcao        = isset($_GET["funcao"]) ? $_GET["funcao"] : "";

    $sql = "";
    $mensagemNotificacao = "";
    $tipoNotificacao = "";

    if($funcao == "I"){
        $idFuncionario = proxIdFuncionario();
        $dtCadastro = date('Y-m-d H:i:s'); // Data e hora atual do cadastro

        // INSERT incluindo as colunas `senha_func` e `dt_cadastro`
        $sql = "INSERT INTO funcionario (idfuncionario, nome_func, cargos_idcargos, tel_func, email_func, senha_func, data_cadastro)
        VALUES (
            '".$idFuncionario."',
            '".mysqli_real_escape_string($conn, $funcionario)."',
            '".mysqli_real_escape_string($conn, $cargo)."',
            '".mysqli_real_escape_string($conn, $tel)."',
            '".mysqli_real_escape_string($conn, $mail)."',
            '".mysqli_real_escape_string($conn, $senha)."',
            '".$dtCadastro."'
        );";

        $mensagemNotificacao = "Cadastrou o funcionário: " . $funcionario;
        $tipoNotificacao = "sucesso";

    }elseif($funcao == "U"){
        $sql = "UPDATE funcionario SET
            nome_func = '".mysqli_real_escape_string($conn, $funcionario)."',
            cargos_idcargos = '".mysqli_real_escape_string($conn, $cargo)."',
            tel_func = '".mysqli_real_escape_string($conn, $tel)."',
            email_func = '".mysqli_real_escape_string($conn, $mail)."'
        WHERE idfuncionario = ".intval($idFuncionario).";";

        $mensagemNotificacao = "Alterou o funcionário: " . $funcionario . " (ID #" . $idFuncionario . ")";
        $tipoNotificacao = "alerta";

    }elseif($funcao == "D"){
        $sql = "DELETE FROM funcionario WHERE idfuncionario = ".intval($idFuncionario).";";

        $mensagemNotificacao = "Excluiu o funcionário ID #" . $idFuncionario;
        $tipoNotificacao = "perigo";
    }

    if (!empty($sql)) {
        $result = mysqli_query($conn, $sql);

        // Se a query executou com sucesso, regista a notificação
        if ($result && !empty($mensagemNotificacao)) {
            registrarNotificacao($conn, $mensagemNotificacao, $usuarioLogado, $tipoNotificacao);
        }
    }

    mysqli_close($conn);

    header("Location: ../funcionarios.php");
    exit();
?>