<?php

    include ('funcaoEstoque.php');
    include ('funcaoServico.php');
    include ('funcaoRelatorio.php');
    include ('funcaoCliente.php');
    include ('funcaoFuncionario.php');

    function registrarNotificacao($conexao, $mensagem, $usuario, $tipo = 'info') {
    $sql = "INSERT INTO notificacoes (mensagem, usuario, tipo) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sss", $mensagem, $usuario, $tipo);
    $stmt->execute();
    $stmt->close();}

?>