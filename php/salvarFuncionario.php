<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';

    $funcionario   = $_POST["nFuncionario"] ?? "";
    $cargo         = $_POST["nCargo"] ?? $_POST["ncargo"] ?? "";
    $tel           = $_POST["nTelefone"] ?? "";
    $mail          = $_POST["nmail"] ?? "";
    $senha         = MD5("Cellmaster123"); 
    
    // CAPTURA CORRETA DO ID (aceita 'codigo' enviado pelo modal de exclusão)
    $idFuncionario = intval($_GET["codigo"] ?? $_GET["IDFunc"] ?? 0);
    $funcao        = $_GET["funcao"] ?? "";

    $sql = "";
    $mensagemNotificacao = "";
    $tipoNotificacao = "";

    if ($funcao == "I") {
        $idFuncionario = proxIdFuncionario();
        $dtCadastro = date('Y-m-d H:i:s');

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

    } elseif ($funcao == "U") {
        $sql = "UPDATE funcionario SET
            nome_func = '".mysqli_real_escape_string($conn, $funcionario)."',
            cargos_idcargos = '".mysqli_real_escape_string($conn, $cargo)."',
            tel_func = '".mysqli_real_escape_string($conn, $tel)."',
            email_func = '".mysqli_real_escape_string($conn, $mail)."'
        WHERE idfuncionario = ".$idFuncionario.";";

        $mensagemNotificacao = "Alterou o funcionário: " . $funcionario . " (ID #" . $idFuncionario . ")";
        $tipoNotificacao = "alerta";

    } elseif ($funcao == "D") {
        // 1. Busca o cargo do funcionário
        $resFunc = mysqli_query($conn, "SELECT cargos_idcargos FROM funcionario WHERE idfuncionario = {$idFuncionario}");
        
        if ($resFunc && mysqli_num_rows($resFunc) > 0) {
            $dadosFunc = mysqli_fetch_assoc($resFunc);
            
            // 2. Se for gerente (cargos_idcargos == 1)
            if ($dadosFunc['cargos_idcargos'] == 1) {
                // Conta quantos gerentes restam
                $resGerentes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM funcionario WHERE cargos_idcargos = 1");
                $totalGerentes = mysqli_fetch_assoc($resGerentes)['total'] ?? 0;

                // 3. Se for 1 ou menos, bloqueia e redireciona com mensagem
                if ($totalGerentes <= 1) {
                    mysqli_close($conn);
                    
                    $_SESSION['mensagem_erro'] = "Ação bloqueada: Não é possível excluir o único Gerente cadastrado no sistema!";
                    
                    header("Location: ../funcionarios.php");
                    exit();
                }
            }
        }

        $sql = "DELETE FROM funcionario WHERE idfuncionario = ".$idFuncionario.";";

        $mensagemNotificacao = "Excluiu o funcionário ID #" . $idFuncionario;
        $tipoNotificacao = "perigo";
    }

    if (!empty($sql)) {
        $result = mysqli_query($conn, $sql);

        if ($result && !empty($mensagemNotificacao)) {
            registrarNotificacao($conn, $mensagemNotificacao, $usuarioLogado, $tipoNotificacao);
        }
    }

    mysqli_close($conn);

    header("Location: ../funcionarios.php");
    exit();
?>