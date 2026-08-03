<?php 
    session_start();

    include('funcoes.php');
    include("conexaoBD.php");

    $usuarioLogado = $_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Atendente';
    
    $servico      = $_POST["nServico"] ?? "";
    $descricao    = $_POST["nDescricao"] ?? "";
    $valorServico = $_POST["nValor"] ?? 0;
    $tempo        = $_POST["nTempo"] ?? "";
    $status       = $_POST["nStatus"] ?? "Atativo";
    $funcao       = $_GET["funcao"] ?? "";
    $idServico    = intval($_GET["codigo"] ?? 0);

    $sql = "";
    $mensagemNotificacao = "";
    $tipoNotificacao = "info";

    // --- FUNÇÃO AUXILIAR PARA CHECAR SE O SERVIÇO ESTÁ EM USO EM OS OU ORÇAMENTO ---
    function servicoEmUso($conn, $idServico) {
        // Ajuste o nome da tabela/coluna relacional conforme a estrutura do seu banco
        $sqlCheck = "SELECT COUNT(*) AS total FROM ordemservico_has_servico WHERE servico_idservico = {$idServico}";
        $res = mysqli_query($conn, $sqlCheck);
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            return intval($row['total'] ?? 0) > 0;
        }
        return false;
    }

    // Validar se é Inclusão, Alteração ou Exclusão
    if ($funcao == "I") {

        $idServico = proxIdServico();

        $sql = "INSERT INTO servico (idservico, nome_servico, descricao_servico, valor, tempo, status) 
                VALUES (
                    ".$idServico.",
                    '".mysqli_real_escape_string($conn, $servico)."',
                    '".mysqli_real_escape_string($conn, $descricao)."',
                    '".mysqli_real_escape_string($conn, $valorServico)."',
                    '".mysqli_real_escape_string($conn, $tempo)."',
                    '".mysqli_real_escape_string($conn, $status)."'
                );";

        $mensagemNotificacao = "Cadastrou o serviço: " . $servico;
        $tipoNotificacao = "sucesso";

    } elseif ($funcao == "U") {

        // --- VALIDAÇÃO: Bloqueia inativar se o serviço estiver vinculado ---
        if (strtolower($status) === 'inativo') {
            if (servicoEmUso($conn, $idServico)) {
                mysqli_close($conn);
                $_SESSION['mensagem_erro'] = "Ação bloqueada: Não é possível inativar este serviço pois ele está vinculado a uma Ordem de Serviço ou Orçamento!";
                header("Location: ../servicos.php");
                exit();
            }
        }

        $sql = "UPDATE servico SET 
                    nome_servico = '".mysqli_real_escape_string($conn, $servico)."', 
                    descricao_servico = '".mysqli_real_escape_string($conn, $descricao)."', 
                    valor = '".mysqli_real_escape_string($conn, $valorServico)."', 
                    tempo = '".mysqli_real_escape_string($conn, $tempo)."', 
                    status = '".mysqli_real_escape_string($conn, $status)."'
                WHERE idservico = ".$idServico.";";

        $mensagemNotificacao = "Alterou o serviço: " . $servico . " (ID #" . $idServico . ")";
        $tipoNotificacao = "alerta";

    } elseif ($funcao == "D") {

        // --- VALIDAÇÃO: Bloqueia excluir se o serviço estiver vinculado ---
        if (servicoEmUso($conn, $idServico)) {
            mysqli_close($conn);
            $_SESSION['mensagem_erro'] = "Ação bloqueada: Não é possível excluir este serviço pois ele está vinculado a uma Ordem de Serviço ou Orçamento!";
            header("Location: ../servicos.php");
            exit();
        }

        $sql = "DELETE FROM servico WHERE idservico = ".$idServico.";";

        $mensagemNotificacao = "Excluiu o serviço ID #" . $idServico;
        $tipoNotificacao = "perigo";
    }

    if (!empty($sql)) {
        $result = mysqli_query($conn, $sql);

        if ($result && !empty($mensagemNotificacao)) {
            registrarNotificacao($conn, $mensagemNotificacao, $usuarioLogado, $tipoNotificacao);
        }
    }

    mysqli_close($conn);

    header("Location: ../servicos.php");
    exit();
?>