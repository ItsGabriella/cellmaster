<?php
include("conexaoBD.php");

// Importações do Dompdf no escopo global (corrigindo o erro de sintaxe)
use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da OS inválido.");
}

$idos = (int)$_GET['id'];

$sql = "
    SELECT 
        os.*, 
        o.marca, 
        o.modelo, 
        o.imei, 
        o.defeito, 
        c.nome_clien, 
        c.tel_clien, 
        c.email_clien, 
        f.nome_func 
    FROM ordem_servico os
    LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
    LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
    LEFT JOIN funcionario f ON os.funcionario_idfuncionario = f.idfuncionario
    WHERE os.idos = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idos);
$stmt->execute();
$os = $stmt->get_result()->fetch_assoc();

if (!$os) {
    die("Ordem de serviço não encontrada.");
}

// Carregar imagem da logo e converter para base64 para garantir a renderização no Dompdf
$logoPath = 'img/logo.png'; // Altere para o caminho da sua imagem se necessário
$logoSrc = '';
if (file_exists($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoSrc = 'data:image/png;base64,' . $logoData;
}

// Se o Dompdf estiver disponível via Composer
if (file_exists('vendor/autoload.php')) {


    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $html = '
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 0; }
            .header-container { width: 100%; margin-bottom: 20px; border-bottom: 2px solid #0056b3; padding-bottom: 10px; }
            .header-container td { vertical-align: middle; }
            .logo-cell { width: 120px; text-align: left; }
            .logo-img { max-width: 100px; max-height: 70px; }
            .title-cell { text-align: center; }
            .header-title { margin: 0; color: #0056b3; font-size: 20px; font-weight: bold; text-transform: uppercase; }
            .header-subtitle { margin: 5px 0 0 0; font-size: 13px; color: #555; }
            .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
            .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            .table th { background-color: #f2f2f2; color: #0056b3; }
            .total { text-align: right; font-size: 14px; font-weight: bold; color: #28a745; margin-top: 15px; }
            .signature { margin-top: 50px; text-align: center; }
            .signature-line { border-top: 1px solid #000; width: 250px; margin: 0 auto 5px auto; }
        </style>
    </head>
    <body>
        <table class="header-container">
            <tr>
                <td class="logo-cell">';
                if (!empty($logoSrc)) {
                    $html .= '<img src="' . $logoSrc . '" class="logo-img" alt="Logo">';
                }
                $html .= '
                </td>
                <td class="title-cell">
                    <h1 class="header-title">CELLMASTER - ASSISTÊNCIA TÉCNICA</h1>
                    <p class="header-subtitle">Comprovante de Ordem de Serviço #' . htmlspecialchars($os['numero_os'] ?: $os['idos']) . '</p>
                </td>
                <td class="logo-cell" style="width: 120px;"></td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th colspan="2">Dados do Cliente</th>
            </tr>
            <tr>
                <td><strong>Nome:</strong> ' . htmlspecialchars($os['nome_clien']) . '</td>
                <td><strong>Telefone:</strong> ' . htmlspecialchars($os['tel_clien']) . '</td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th colspan="3">Aparelho e Diagnóstico</th>
            </tr>
            <tr>
                <td><strong>Marca/Modelo:</strong> ' . htmlspecialchars($os['marca'] . ' ' . $os['modelo']) . '</td>
                <td><strong>IMEI:</strong> ' . htmlspecialchars($os['imei']) . '</td>
                <td><strong>Data:</strong> ' . date('d/m/Y', strtotime($os['data_abertura'])) . '</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Defeito Relatado:</strong> ' . htmlspecialchars($os['defeito']) . '</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Laudo Técnico:</strong> ' . htmlspecialchars($os['laudo_tecnico']) . '</td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th>Descrição do Serviço</th>
                <th>Valor (R$)</th>
            </tr>
            <tr>
                <td>Peças</td>
                <td>R$ ' . number_format((float)$os['valor_pecas'], 2, ',', '.') . '</td>
            </tr>
            <tr>
                <td>Mão de Obra</td>
                <td>R$ ' . number_format((float)$os['valor_mao_obra'], 2, ',', '.') . '</td>
            </tr>
            <tr>
                <td>Desconto</td>
                <td>- R$ ' . number_format((float)$os['desconto'], 2, ',', '.') . '</td>
            </tr>
        </table>

        <div class="total">
            Valor Total: R$ ' . number_format((float)$os['valor_final'], 2, ',', '.') . '
        </div>

        <div class="signature">
            <div class="signature-line"></div>
            <p>Assinatura do Cliente</p>
        </div>
    </body>
    </html>
    ';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("OS_" . $os['idos'] . ".pdf", array("Attachment" => false));

} else {
    // Fallback para impressão via janela do navegador caso o Dompdf não esteja instalado
    echo '<script>window.onload = function() { window.print(); }</script>';
    echo '<div style="font-family: Arial, sans-serif; padding: 20px;">';
    echo '<div style="display: flex; align-items: center; border-bottom: 2px solid #0056b3; padding-bottom: 10px; margin-bottom: 20px;">';
    if (!empty($logoSrc)) {
        echo '<img src="' . $logoSrc . '" style="max-width: 80px; margin-right: 15px;" alt="Logo">';
    }
    echo '<div>';
    echo '<h1 style="margin: 0; color: #0056b3;">CELLMASTER - ASSISTÊNCIA TÉCNICA</h1>';
    echo '<h3 style="margin: 5px 0 0 0;">OS #' . htmlspecialchars($os['numero_os'] ?: $os['idos']) . '</h3>';
    echo '</div>';
    echo '</div>';
    echo '<p><strong>Cliente:</strong> ' . htmlspecialchars($os['nome_clien']) . '</p>';
    echo '<p><strong>Aparelho:</strong> ' . htmlspecialchars($os['marca'] . ' ' . $os['modelo']) . '</p>';
    echo '<p><strong>Laudo Técnico:</strong> ' . htmlspecialchars($os['laudo_tecnico']) . '</p>';
    echo '<h3>Total: R$ ' . number_format((float)$os['valor_final'], 2, ',', '.') . '</h3>';
    echo '</div>';
}
?>