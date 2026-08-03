<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexaoBD.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idos = isset($_POST['idos']) ? (int)$_POST['idos'] : 0;
    $laudo = isset($_POST['laudo_tecnico']) ? trim($_POST['laudo_tecnico']) : '';
    $valor = isset($_POST['valor_final']) ? (float)$_POST['valor_final'] : 0.00;
    $status = isset($_POST['status_os']) ? trim($_POST['status_os']) : '';
    $origem = $_POST['origem'] ?? 'lista';

    if ($idos > 0) {
        $stmt = $conn->prepare("UPDATE ordem_servico SET laudo_tecnico = ?, valor_final = ?, status_os = ? WHERE idos = ?");
        
        if ($stmt) {
            $stmt->bind_param("sdsi", $laudo, $valor, $status, $idos);
            
            if ($stmt->execute()) {
                $stmt->close();
                // Se o formulário veio da tela de visualização, volta para ela
                if ($origem === 'visualizar') {
                    header("Location: visualizar_os.php?id={$idos}&sucesso=1");
                } else {
                    header("Location: ordens_servico.php?sucesso=1");
                }
                exit();
            }
        }
    }
}

header("Location: ordens_servico.php?erro=1");
exit();