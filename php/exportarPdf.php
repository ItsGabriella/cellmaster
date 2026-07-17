<?php
// php/exportarPdf.php

// Substitua a linha do vendor por esta:
require_once '../dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

// ... (o restante do código permanece o mesmo)

// 2. Incluir conexões e funções existentes do seu sistema
include("conexaoBD.php");

// 3. Configurações da biblioteca (para permitir imagens externas ou CSS remoto se necessário)
$options = new Options();
$options->set('isRemoteEnabled', true); 
$dompdf = new Dompdf($options);

// 4. Buscar os dados que vão popular o relatório PDF
$sql = "SELECT * FROM relatorio ORDER BY idrelatorio DESC";
$resultado = mysqli_query($conn, $sql);

// 5. Montar a estrutura HTML do relatório que será transformada em PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; color: #198754; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #198754; color: white; padding: 10px; text-align: left; }
        td { border-bottom: 1px solid #ddd; padding: 8px; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>

    <h2>Relatório de Atividades</h2>
    <p>Gerado em: ' . date('d/m/Y H:i') . '</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Relatório</th>
                <th>Tipo</th>
                <th>Data</th>
                <th>Responsável</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

        if (mysqli_num_rows($resultado) > 0) {
            while ($coluna = mysqli_fetch_assoc($resultado)) {
                $html .= '<tr>
                    <td>' . $coluna["idrelatorio"] . '</td>
                    <td>' . $coluna["nome_relatorio"] . '</td>
                    <td>' . $coluna["tipo"] . '</td>
                    <td>' . date("d/m/Y", strtotime($coluna["data"])) . '</td>
                    <td>' . $coluna["responsavel"] . '</td>
                    <td>' . $coluna["status"] . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="6" style="text-align:center;">Nenhum registro encontrado.</td></tr>';
        }

$html .= '
        </tbody>
    </table>

    <div class="footer">
        Sistema de Relatórios - Página 1
    </div>

</body>
</html>';

mysqli_close($conn);

// 6. Carregar o HTML criado no Dompdf
$dompdf->loadHtml($html);

// 7. Definir o formato do papel (A4) e a orientação (Portrait / Retrato ou Landscape / Paisagem)
$dompdf->setPaper('A4', 'portrait');

// 8. Renderizar o HTML em PDF
$dompdf->render();

// 9. Forçar o download do arquivo gerado
$dompdf->stream("relatorio_atividades_" . date('Ymd') . ".pdf", array("Attachment" => true));
exit;