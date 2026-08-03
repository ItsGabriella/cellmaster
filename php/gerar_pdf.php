<?php
include("conexaoBD.php");

// Corrigir caminho do autoload conforme o seu ambiente
if (file_exists('../dompdf/autoload.inc.php')) {
    require_once '../dompdf/autoload.inc.php';
} elseif (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da OS inválido.");
}

$idos = (int)$_GET['id'];

// Consulta ajustada para garantir que traz todos os campos corretos da base de dados
$sql = "
    SELECT 
        os.*, 
        o.marca, 
        o.modelo, 
        o.imei, 
        o.defeito AS defeito_orcamento, 
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

// Buscar itens específicos da OS (caso utilize a tabela ordem_servico_itens)
$sql_itens = "SELECT * FROM ordem_servico_itens WHERE os_idos = ?";
$stmt_itens = $conn->prepare($sql_itens);
$stmt_itens->bind_param("i", $idos);
$stmt_itens->execute();
$result_itens = $stmt_itens->get_result();

// Carregar imagem da logo e converter para base64
$logoPath = 'img/CellMaster.png';
$logoSrc = '';
if (file_exists($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoSrc = 'data:image/png;base64,' . $logoData;
}

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Tratar defeito (pode vir de os.defeito_informado ou orcamento.defeito)
$defeito_relatado = !empty($os['defeito_informado']) ? $os['defeito_informado'] : (!empty($os['defeito_orcamento']) ? $os['defeito_orcamento'] : 'Não informado');

$html = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 10mm 10mm;
        }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #2f3e46;
            line-height: 1.35;
        }
        
        /* Cabeçalho */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-bottom: 2px solid #2d6a4f;
            padding-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 110px;
        }
        .logo-img {
            max-width: 100px;
            max-height: 50px;
        }
        .company-info {
            text-align: left;
            padding-left: 10px;
        }
        .company-name {
            font-size: 15px;
            font-weight: bold;
            color: #1b4332;
            text-transform: uppercase;
            margin: 0;
        }
        .company-sub {
            font-size: 8.5px;
            color: #52796f;
            margin-top: 1px;
        }
        .os-badge-cell {
            text-align: right;
            width: 150px;
        }
        .os-badge {
            background-color: #d8f3dc;
            border: 1px solid #b7e4c7;
            border-radius: 5px;
            padding: 5px 8px;
            text-align: center;
        }
        .os-title {
            font-size: 8px;
            text-transform: uppercase;
            color: #2d6a4f;
            font-weight: bold;
        }
        .os-number {
            font-size: 14px;
            font-weight: bold;
            color: #1b4332;
        }

        /* Seções */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #1b4332;
            text-transform: uppercase;
            border-left: 3px solid #40916c;
            padding-left: 5px;
            margin-bottom: 4px;
            margin-top: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .info-table td, .info-table th {
            padding: 4px 6px;
            border: 1px solid #d8f3dc;
            font-size: 9.5px;
        }
        .info-table th {
            background-color: #f1f8f5;
            color: #2d6a4f;
            font-weight: bold;
            text-align: left;
            width: 22%;
        }
        .bg-light {
            background-color: #f1f8f5;
        }

        /* Tabela de Itens e Valores */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .items-table th {
            background-color: #2d6a4f;
            color: #ffffff;
            padding: 5px 8px;
            font-size: 9px;
            text-transform: uppercase;
            text-align: left;
        }
        .items-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #d8f3dc;
            font-size: 9.5px;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }

        /* Totais */
        .totals-container {
            width: 100%;
            margin-top: 8px;
        }
        .totals-table {
            width: 220px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 3px 6px;
            font-size: 10px;
        }
        .totals-table .total-row {
            background-color: #1b4332;
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
        }

        /* Assinaturas */
        .signatures-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .signatures-table td {
            width: 48%;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-line {
            border-top: 1px solid #52796f;
            width: 75%;
            margin: 0 auto 3px auto;
        }
        .signature-title {
            font-size: 9px;
            color: #40916c;
            font-weight: bold;
        }

        .footer-note {
            margin-top: 15px;
            padding-top: 6px;
            border-top: 1px dashed #b7e4c7;
            font-size: 7.5px;
            color: #52796f;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Cabeçalho -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">';
            if (!empty($logoSrc)) {
                $html .= '<img src="' . $logoSrc . '" class="logo-img" alt="Logo">';
            } else {
                $html .= '<div style="width: 90px; height: 40px; border: 1px dashed #b7e4c7; text-align: center; line-height: 40px; color: #52796f; font-size: 8px; background-color: #f1f8f5;">LOGO</div>';
            }
            $html .= '
            </td>
            <td class="company-info">
                <h1 class="company-name">CELLMASTER</h1>
                <div class="company-sub">Assistência Técnica & Soluções em Tecnologia</div>
            </td>
            <td class="os-badge-cell">
                <div class="os-badge">
                    <div class="os-title">Ordem de Serviço</div>
                    <div class="os-number">#' . htmlspecialchars($os['numero_os'] ?: str_pad($os['idos'], 5, '0', STR_PAD_LEFT)) . '</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Informações do Cliente -->
    <div class="section-title">Informações do Cliente</div>
    <table class="info-table">
        <tr>
            <th class="bg-light">Cliente:</th>
            <td>' . htmlspecialchars($os['nome_clien'] ?? 'Não informado') . '</td>
            <th class="bg-light">Data Abertura:</th>
            <td>' . (!empty($os['data_abertura']) ? date('d/m/Y', strtotime($os['data_abertura'])) : 'N/A') . '</td>
        </tr>
        <tr>
            <th class="bg-light">Telefone:</th>
            <td>' . htmlspecialchars($os['tel_clien'] ?? 'Não informado') . '</td>
            <th class="bg-light">Técnico:</th>
            <td>' . htmlspecialchars($os['nome_func'] ?? 'Não atribuído') . '</td>
        </tr>
        <tr>
            <th class="bg-light">E-mail:</th>
            <td colspan="3">' . htmlspecialchars($os['email_clien'] ?? 'Não informado') . '</td>
        </tr>
    </table>

    <!-- Aparelho e Diagnóstico -->
    <div class="section-title">Dados do Equipamento & Diagnóstico</div>
    <table class="info-table">
        <tr>
            <th class="bg-light">Aparelho:</th>
            <td>' . htmlspecialchars(trim(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? '')) ?: 'Não informado') . '</td>
            <th class="bg-light">IMEI / Série:</th>
            <td>' . htmlspecialchars($os['imei'] ?? 'N/A') . '</td>
        </tr>
        <tr>
            <th class="bg-light">Defeito Relatado:</th>
            <td colspan="3">' . nl2br(htmlspecialchars($defeito_relatado)) . '</td>
        </tr>
        <tr>
            <th class="bg-light">Laudo Técnico:</th>
            <td colspan="3">' . nl2br(htmlspecialchars($os['laudo_tecnico'] ?? 'Aguardando avaliação detalhada.')) . '</td>
        </tr>
        ' . (!empty($os['descricao_servico']) ? '
        <tr>
            <th class="bg-light">Serviço Executado:</th>
            <td colspan="3">' . nl2br(htmlspecialchars($os['descricao_servico'])) . '</td>
        </tr>' : '') . '
    </table>

    <!-- Detalhamento de Itens / Serviços (se houver na tabela ordem_servico_itens) -->
    <div class="section-title">Detalhamento Financeiro & Serviços</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Tipo / Descrição do Item</th>
                <th class="text-center" style="width: 60px;">Qtd</th>
                <th class="text-right" style="width: 90px;">V. Unitário</th>
                <th class="text-right" style="width: 90px;">Total</th>
            </tr>
        </thead>
        <tbody>';

        $tem_itens = false;
        if ($result_itens && $result_itens->num_rows > 0) {
            while ($item = $result_itens->fetch_assoc()) {
                $tem_itens = true;
                $html .= '
                <tr>
                    <td>[' . htmlspecialchars($item['tipo_item']) . '] ' . htmlspecialchars($item['descricao']) . '</td>
                    <td class="text-center">' . (int)$item['quantidade'] . '</td>
                    <td class="text-right">R$ ' . number_format((float)$item['valor_unitario'], 2, ',', '.') . '</td>
                    <td class="text-right">R$ ' . number_format((float)$item['valor_total'], 2, ',', '.') . '</td>
                </tr>';
            }
        }

        // Caso não haja itens na tabela de itens, exibe os valores gerais da OS
        if (!$tem_itens) {
            if ((float)$os['valor_mao_obra'] > 0) {
                $html .= '
                <tr>
                    <td colspan="3">Mão de Obra Especializada</td>
                    <td class="text-right">R$ ' . number_format((float)$os['valor_mao_obra'], 2, ',', '.') . '</td>
                </tr>';
            }
            if ((float)$os['valor_pecas'] > 0) {
                $html .= '
                <tr>
                    <td colspan="3">Peças / Componentes de Reposição</td>
                    <td class="text-right">R$ ' . number_format((float)$os['valor_pecas'], 2, ',', '.') . '</td>
                </tr>';
            }
            if ((float)$os['valor_mao_obra'] == 0 && (float)$os['valor_pecas'] == 0 && (float)$os['valor_final'] > 0) {
                $html .= '
                <tr>
                    <td colspan="3">Serviço Técnico Geral / Orçamento</td>
                    <td class="text-right">R$ ' . number_format((float)$os['valor_final'], 2, ',', '.') . '</td>
                </tr>';
            }
        }

        if ((float)$os['desconto'] > 0) {
            $html .= '
            <tr>
                <td colspan="3" style="color: #c5221f;">Desconto Aplicado</td>
                <td class="text-right" style="color: #c5221f;">- R$ ' . number_format((float)$os['desconto'], 2, ',', '.') . '</td>
            </tr>';
        }

        $html .= '
        </tbody>
    </table>

    <!-- Total -->
    <div class="totals-container">
        <table class="totals-table">
            <tr class="total-row">
                <td style="padding: 5px 6px;">VALOR TOTAL:</td>
                <td class="text-right" style="padding: 5px 6px;">R$ ' . number_format((float)$os['valor_final'], 2, ',', '.') . '</td>
            </tr>
        </table>
    </div>

    <!-- Assinaturas -->
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div class="signature-title">Assinatura do Cliente</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div class="signature-title">Assinatura do Técnico / Empresa</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Este documento é um comprovante da Ordem de Serviço descrita acima. O equipamento será retirado mediante apresentação deste documento.
    </div>

</body>
</html>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("OS_" . $os['idos'] . ".pdf", array("Attachment" => false));
?>
