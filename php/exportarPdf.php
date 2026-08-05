<?php
// php/exportarPdf.php

require_once '../dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

include("conexaoBD.php");

// 1. Receber os IDs selecionados do formulário (trata relatorios e relatorios[])
$relatorios_post = $_POST['relatorios'] ?? $_POST['relatorios[]'] ?? $_POST['ids'] ?? [];

if (empty($relatorios_post)) {
    echo "<script>
            alert('Por favor, selecione pelo menos um relatório para exportar.');
            window.history.back();
          </script>";
    exit;
}

// Sanitizar IDs
$ids_selecionados = array_map('intval', (array)$relatorios_post);
$ids_string = implode(',', $ids_selecionados);

// 2. Buscar os relatórios selecionados na tabela 'relatorio'
$sqlRelatorios = "SELECT * FROM relatorio WHERE idrelatorio IN ($ids_string) ORDER BY idrelatorio DESC";
$resRelatorios = mysqli_query($conn, $sqlRelatorios);

// Atualizar status de exportado no banco
$sqlUpdate = "UPDATE relatorio SET exportado = 'Sim' WHERE idrelatorio IN ($ids_string)";
mysqli_query($conn, $sqlUpdate);

// Identificar o TIPO do relatório (com base no primeiro item selecionado)
$primeiroRelatorio = mysqli_fetch_assoc($resRelatorios);
$tipoRelatorio = $primeiroRelatorio['tipo'] ?? 'Estoque';

// Reseta o ponteiro da consulta para reutilizar na tabela
mysqli_data_seek($resRelatorios, 0);

// 3. Estilos CSS fiéis ao design da imagem (compatível com Dompdf)
$css = '
<style>
    @page { margin: 25px 25px; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 10px; }
    
    .header-title { text-align: center; color: #198754; font-size: 20px; font-weight: bold; margin-bottom: 4px; }
    .data-geracao { text-align: center; font-size: 10px; color: #666; margin-bottom: 20px; }
    
    .secao-titulo { 
        font-size: 12px; 
        font-weight: bold; 
        color: #198754; 
        border-bottom: 2px solid #198754; 
        padding-bottom: 4px; 
        margin-top: 20px; 
        margin-bottom: 12px; 
    }

    /* Tabelas */
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    table.data-table th { background-color: #198754; color: #ffffff; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: bold; }
    table.data-table td { border-bottom: 1px solid #e2e8f0; padding: 7px 8px; font-size: 10px; }
    table.data-table tr:nth-child(even) { background-color: #fcfcfc; }

    /* Layout dos Cards de Métricas (Uso de tabela para renderização perfeita no Dompdf) */
    .cards-table { width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 10px; margin-left: -8px; }
    .card-td { width: 25%; vertical-align: top; }
    .card-box { 
        background-color: #f8f9fa; 
        border: 1px solid #dee2e6; 
        border-radius: 5px; 
        padding: 10px 5px; 
        text-align: center; 
    }
    .card-title { font-size: 8px; color: #6c757d; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
    .card-value { font-size: 14px; font-weight: bold; color: #198754; }
    .card-value.danger { color: #dc3545; }

    /* Utilitários */
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .badge-baixo { background-color: #f8d7da; color: #842029; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 9px; }
    .badge-normal { color: #333; }
    
    .footer { position: fixed; bottom: -10px; left: 0; right: 0; text-align: center; font-size: 9px; color: #888; }
</style>';

// Header Principal
$htmlHeader = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório - ' . htmlspecialchars($tipoRelatorio) . '</title>
    ' . $css . '
</head>
<body>
    <div class="header-title">Relatório Geral de ' . htmlspecialchars($tipoRelatorio) . '</div>
    <div class="data-geracao">Exportado em: ' . date('d/m/Y H:i') . '</div>

    <div class="secao-titulo">Informações do(s) Relatório(s) Selecionado(s)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 42%;">Nome do Relatório</th>
                <th style="width: 15%;">Tipo</th>
                <th style="width: 12%;">Data</th>
                <th style="width: 13%;">Responsável</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>';

        while ($rel = mysqli_fetch_assoc($resRelatorios)) {
            $htmlHeader .= '<tr>
                <td>#' . $rel["idrelatorio"] . '</td>
                <td>' . htmlspecialchars($rel["nome_relatorio"]) . '</td>
                <td>' . htmlspecialchars($rel["tipo"]) . '</td>
                <td>' . date("d/m/Y", strtotime($rel["geracao_data"])) . '</td>
                <td>' . htmlspecialchars($rel["responsavel"]) . '</td>
                <td>' . htmlspecialchars($rel["status"]) . '</td>
            </tr>';
        }

$htmlHeader .= '
        </tbody>
    </table>';

// 4. Montar o Bloco de Métricas e Detalhes de acordo com o TIPO
$htmlBody = '';

switch ($tipoRelatorio) {

    // ==========================================
    // TIPO: ESTOQUE
    // ==========================================
    case 'Estoque':
        // Métricas
        $sqlResumo = "SELECT COUNT(*) as total_tipos, SUM(qtdade_peca) as total_qtd, SUM(qtdade_peca * valor_unit) as valor_total FROM peca";
        $resResumo = mysqli_query($conn, $sqlResumo);
        $dadosResumo = mysqli_fetch_assoc($resResumo);

        $sqlBaixo = "SELECT COUNT(*) as qtd_baixo FROM peca WHERE qtdade_peca <= estoque_min";
        $resBaixo = mysqli_query($conn, $sqlBaixo);
        $dadosBaixo = mysqli_fetch_assoc($resBaixo);

        $htmlBody .= '
        <div class="secao-titulo">Resumo do Estoque Atual</div>
        <table class="cards-table">
            <tr>
                <td class="card-td">
                    <div class="card-box">
                        <div class="card-title">Tipos de Peças</div>
                        <div class="card-value">' . number_format($dadosResumo['total_tipos'] ?? 0, 0, ',', '.') . '</div>
                    </div>
                </td>
                <td class="card-td">
                    <div class="card-box">
                        <div class="card-title">Total de Peças</div>
                        <div class="card-value">' . number_format($dadosResumo['total_qtd'] ?? 0, 0, ',', '.') . '</div>
                    </div>
                </td>
                <td class="card-td">
                    <div class="card-box">
                        <div class="card-title">Valor em Estoque</div>
                        <div class="card-value">R$ ' . number_format($dadosResumo['valor_total'] ?? 0, 2, ',', '.') . '</div>
                    </div>
                </td>
                <td class="card-td">
                    <div class="card-box">
                        <div class="card-title">Estoque Baixo</div>
                        <div class="card-value danger">' . number_format($dadosBaixo['qtd_baixo'] ?? 0, 0, ',', '.') . '</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="secao-titulo">Detalhamento de Peças em Estoque</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 6%;">ID</th>
                    <th style="width: 28%;">Nome da Peça</th>
                    <th style="width: 14%;">Categoria</th>
                    <th class="text-right" style="width: 8%;">Qtd</th>
                    <th class="text-right" style="width: 12%;">Est. Mínimo</th>
                    <th class="text-right" style="width: 12%;">Valor Unit.</th>
                    <th class="text-right" style="width: 12%;">Subtotal</th>
                    <th style="width: 8%;">Status</th>
                </tr>
            </thead>
            <tbody>';

        $sqlPecas = "SELECT * FROM peca ORDER BY nome_peca ASC";
        $resPecas = mysqli_query($conn, $sqlPecas);

        if ($resPecas && mysqli_num_rows($resPecas) > 0) {
            while ($p = mysqli_fetch_assoc($resPecas)) {
                $subtotal = $p['qtdade_peca'] * $p['valor_unit'];
                $status = ($p['qtdade_peca'] <= $p['estoque_min']) 
                    ? '<span class="badge-baixo">Estoque Baixo</span>' 
                    : '<span class="badge-normal">Normal</span>';

                $htmlBody .= '<tr>
                    <td>' . $p["idpeca"] . '</td>
                    <td>' . htmlspecialchars($p["nome_peca"]) . '</td>
                    <td>' . htmlspecialchars($p["categoria"]) . '</td>
                    <td class="text-right">' . $p["qtdade_peca"] . '</td>
                    <td class="text-right">' . $p["estoque_min"] . '</td>
                    <td class="text-right">R$ ' . number_format($p["valor_unit"], 2, ',', '.') . '</td>
                    <td class="text-right">R$ ' . number_format($subtotal, 2, ',', '.') . '</td>
                    <td>' . $status . '</td>
                </tr>';
            }
        } else {
            $htmlBody .= '<tr><td colspan="8" class="text-center">Nenhuma peça cadastrada.</td></tr>';
        }
        $htmlBody .= '</tbody></table>';
        break;

    // ==========================================
    // TIPO: CLIENTES
    // ==========================================
    case 'Clientes':
        $sqlCount = "SELECT COUNT(*) as total FROM cliente";
        $resCount = mysqli_query($conn, $sqlCount);
        $totalClientes = mysqli_fetch_assoc($resCount)['total'] ?? 0;

        $htmlBody .= '
        <div class="secao-titulo">Resumo de Clientes</div>
        <table class="cards-table">
            <tr>
                <td class="card-td" style="width: 33%;">
                    <div class="card-box">
                        <div class="card-title">Total de Clientes</div>
                        <div class="card-value">' . $totalClientes . '</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="secao-titulo">Detalhamento de Clientes Cadastrados</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 32%;">Nome Completo</th>
                    <th style="width: 20%;">Telefone</th>
                    <th style="width: 25%;">E-mail</th>
                    <th style="width: 15%;">Cidade</th>
                </tr>
            </thead>
            <tbody>';

        $sqlClientes = "SELECT * FROM cliente ORDER BY nome ASC";
        $resClientes = mysqli_query($conn, $sqlClientes);

        if ($resClientes && mysqli_num_rows($resClientes) > 0) {
            while ($c = mysqli_fetch_assoc($resClientes)) {
                $htmlBody .= '<tr>
                    <td>' . $c["idcliente"] . '</td>
                    <td>' . htmlspecialchars($c["nome"]) . '</td>
                    <td>' . htmlspecialchars($c["telefone"] ?? '-') . '</td>
                    <td>' . htmlspecialchars($c["email"] ?? '-') . '</td>
                    <td>' . htmlspecialchars($c["cidade"] ?? '-') . '</td>
                </tr>';
            }
        } else {
            $htmlBody .= '<tr><td colspan="5" class="text-center">Nenhum cliente cadastrado.</td></tr>';
        }
        $htmlBody .= '</tbody></table>';
        break;

    // ==========================================
    // TIPO: FUNCIONÁRIOS
    // ==========================================
    case 'Funcionários':
        $sqlCount = "SELECT COUNT(*) as total FROM funcionario";
        $resCount = mysqli_query($conn, $sqlCount);
        $totalFunc = mysqli_fetch_assoc($resCount)['total'] ?? 0;

        $htmlBody .= '
        <div class="secao-titulo">Resumo da Equipe</div>
        <table class="cards-table">
            <tr>
                <td class="card-td" style="width: 33%;">
                    <div class="card-box">
                        <div class="card-title">Funcionários Ativos</div>
                        <div class="card-value">' . $totalFunc . '</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="secao-titulo">Quadro de Funcionários</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 45%;">Nome do Funcionário</th>
                    <th style="width: 25%;">Cargo</th>
                    <th style="width: 20%;">Telefone</th>
                </tr>
            </thead>
            <tbody>';

        $sqlFunc = "SELECT * FROM funcionario ORDER BY nome_func ASC";
        $resFunc = mysqli_query($conn, $sqlFunc);

        if ($resFunc && mysqli_num_rows($resFunc) > 0) {
            while ($f = mysqli_fetch_assoc($resFunc)) {
                $htmlBody .= '<tr>
                    <td>' . $f["idfuncionario"] . '</td>
                    <td>' . htmlspecialchars($f["nome_func"]) . '</td>
                    <td>' . htmlspecialchars($f["cargo"] ?? 'Gerente') . '</td>
                    <td>' . htmlspecialchars($f["tel_func"]) . '</td>
                </tr>';
            }
        } else {
            $htmlBody .= '<tr><td colspan="4" class="text-center">Nenhum funcionário cadastrado.</td></tr>';
        }
        $htmlBody .= '</tbody></table>';
        break;



    case 'Serviços':
    // Contagem total de serviços
    $sqlCount = "SELECT COUNT(*) as total FROM servico";
    $resCount = mysqli_query($conn, $sqlCount);
    $totalServico = mysqli_fetch_assoc($resCount)['total'] ?? 0;

    // Contagem de ativos
    $sqlCountAtivos = "SELECT COUNT(*) as total FROM servico WHERE status = 'Ativo'";
    $resCountAtivos = mysqli_query($conn, $sqlCountAtivos);
    $totalAtivos = mysqli_fetch_assoc($resCountAtivos)['total'] ?? 0;

    // Contagem de inativos
    $sqlCountInativos = "SELECT COUNT(*) as total FROM servico WHERE status != 'Inativo'";
    $resCountInativos = mysqli_query($conn, $sqlCountInativos);
    $totalInativos = mysqli_fetch_assoc($resCountInativos)['total'] ?? 0;

    $htmlBody .= '
    <div class="secao-titulo">Resumo de Serviços</div>
    <table class="cards-table">
        <tr>
            <td class="card-td" style="width: 33.33%;">
                <div class="card-box">
                    <div class="card-title">Total de Serviços</div>
                    <div class="card-value">' . $totalServico . '</div>
                </div>
            </td>
            <td class="card-td" style="width: 33.33%;">
                <div class="card-box">
                    <div class="card-title">Serviços Ativos</div>
                    <div class="card-value">' . $totalAtivos . '</div>
                </div>
            </td>
            <td class="card-td" style="width: 33.33%;">
                <div class="card-box">
                    <div class="card-title">Serviços Inativos</div>
                    <div class="card-value">' . $totalInativos . '</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="secao-titulo">Tabela de Serviços</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 22%;">Nome do Serviço</th>
                <th style="width: 35%;">Descrição</th>
                <th style="width: 12%;">Valor</th>
                <th style="width: 13%;">Tempo</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>';

    $sqlServico = "SELECT * FROM servico ORDER BY nome_servico ASC";
    $resServico = mysqli_query($conn, $sqlServico);

    if ($resServico && mysqli_num_rows($resServico) > 0) {
        while ($s = mysqli_fetch_assoc($resServico)) {
            $valorFormatado = isset($s['valor']) ? 'R$ ' . number_format($s['valor'], 2, ',', '.') : 'R$ 0,00';

            $htmlBody .= '<tr>
                <td>' . $s["idservico"] . '</td>
                <td>' . htmlspecialchars($s["nome_servico"]) . '</td>
                <td>' . htmlspecialchars($s["descricao_servico"] ?? '-') . '</td>
                <td>' . $valorFormatado . '</td>
                <td>' . htmlspecialchars($s["tempo"] ?? '-') . '</td>
                <td>' . htmlspecialchars($s["status"] ?? '-') . '</td>
            </tr>';
        }
    } else {
        $htmlBody .= '<tr><td colspan="6" class="text-center">Nenhum serviço cadastrado.</td></tr>';
    }
    $htmlBody .= '</tbody></table>';
    break;

    // ==========================================
    // OUTROS TIPOS (Serviços, Orçamento, Ordem de Serviço)
    // ==========================================
    default:
        $htmlBody .= '
        <div class="secao-titulo">Resumo do Relatório</div>
        <div style="padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 15px;">
            Exibindo os dados consolidados do relatório de <strong>' . htmlspecialchars($tipoRelatorio) . '</strong>.
        </div>';
        break;
}

$htmlFooter = '
    <div class="footer">
        Sistema de Gestão - Relatório de ' . htmlspecialchars($tipoRelatorio) . '
    </div>
</body>
</html>';

$htmlFinal = $htmlHeader . $htmlBody . $htmlFooter;

mysqli_close($conn);

// 5. Gerar o PDF via Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true); 
$dompdf = new Dompdf($options);

$dompdf->loadHtml($htmlFinal);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Limpa qualquer saída no buffer antes do envio do arquivo
if (ob_get_length()) ob_end_clean();

$dompdf->stream("relatorio_" . strtolower($tipoRelatorio) . "_" . date('Ymd_His') . ".pdf", array("Attachment" => true));
exit;