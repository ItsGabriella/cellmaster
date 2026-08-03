<?php
// php/exportarExcel.php

include("conexaoBD.php");

// 1. Receber os IDs selecionados do formulário
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

// 2. Buscar os relatórios selecionados
$sqlRelatorios = "SELECT * FROM relatorio WHERE idrelatorio IN ($ids_string) ORDER BY idrelatorio DESC";
$resRelatorios = mysqli_query($conn, $sqlRelatorios);

// Atualizar status de exportado no banco
$sqlUpdate = "UPDATE relatorio SET exportado = 'Sim' WHERE idrelatorio IN ($ids_string)";
mysqli_query($conn, $sqlUpdate);

// Identificar o TIPO do relatório
$primeiroRelatorio = mysqli_fetch_assoc($resRelatorios);
$tipoRelatorio = $primeiroRelatorio['tipo'] ?? 'Estoque';

// Reseta o ponteiro da consulta
mysqli_data_seek($resRelatorios, 0);

// Configurar o nome do arquivo XLS
$filename = "relatorio_" . strtolower($tipoRelatorio) . "_" . date('Ymd_His') . ".xls";

// Define os cabeçalhos HTTP para download no Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header("Cache-Control: max-age=0");

// UTF-8 BOM para garantir acentuação correta no Excel
echo "\xEF\xBB\xBF";

// 3. Estilos CSS para visualização no Excel
$css = '
<style>
    body { font-family: Arial, sans-serif; font-size: 11px; }
    .header-title { text-align: center; color: #198754; font-size: 16px; font-weight: bold; }
    .data-geracao { text-align: center; font-size: 10px; color: #666; }
    .secao-titulo { font-size: 12px; font-weight: bold; color: #198754; background-color: #e8f5e9; }
    table { border-collapse: collapse; width: 100%; }
    th { background-color: #198754; color: #ffffff; font-weight: bold; border: 1px solid #cccccc; }
    td { border: 1px solid #cccccc; vertical-align: middle; }
    .card-box { background-color: #f8f9fa; border: 1px solid #dee2e6; text-align: center; font-weight: bold; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .badge-baixo { background-color: #f8d7da; color: #842029; font-weight: bold; }
</style>';

// Header do Documento
$htmlHeader = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    ' . $css . '
</head>
<body>
    <table>
        <tr>
            <td colspan="6" class="header-title">Relatório Geral de ' . htmlspecialchars($tipoRelatorio) . '</td>
        </tr>
        <tr>
            <td colspan="6" class="data-geracao">Exportado em: ' . date('d/m/Y H:i') . '</td>
        </tr>
        <tr><td colspan="6"></td></tr>
        <tr>
            <td colspan="6" class="secao-titulo">Informações do(s) Relatório(s) Selecionado(s)</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome do Relatório</th>
                <th>Tipo</th>
                <th>Data</th>
                <th>Responsável</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

        while ($rel = mysqli_fetch_assoc($resRelatorios)) {
            $htmlHeader .= '<tr>
                <td class="text-center">#' . $rel["idrelatorio"] . '</td>
                <td>' . htmlspecialchars($rel["nome_relatorio"]) . '</td>
                <td>' . htmlspecialchars($rel["tipo"]) . '</td>
                <td class="text-center">' . date("d/m/Y", strtotime($rel["geracao_data"])) . '</td>
                <td>' . htmlspecialchars($rel["responsavel"]) . '</td>
                <td class="text-center">' . htmlspecialchars($rel["status"]) . '</td>
            </tr>';
        }

$htmlHeader .= '
        </tbody>
    </table>
    <br>';

// 4. Montar dados específicos por TIPO
$htmlBody = '';

switch ($tipoRelatorio) {

    // ==========================================
    // TIPO: ESTOQUE
    // ==========================================
    case 'Estoque':
        $sqlResumo = "SELECT COUNT(*) as total_tipos, SUM(qtdade_peca) as total_qtd, SUM(qtdade_peca * valor_unit) as valor_total FROM peca";
        $resResumo = mysqli_query($conn, $sqlResumo);
        $dadosResumo = mysqli_fetch_assoc($resResumo);

        $sqlBaixo = "SELECT COUNT(*) as qtd_baixo FROM peca WHERE qtdade_peca <= estoque_min";
        $resBaixo = mysqli_query($conn, $sqlBaixo);
        $dadosBaixo = mysqli_fetch_assoc($resBaixo);

        $htmlBody .= '
        <table>
            <tr><td colspan="8" class="secao-titulo">Resumo do Estoque Atual</td></tr>
            <tr>
                <th colspan="2">Tipos de Peças</th>
                <th colspan="2">Total de Peças</th>
                <th colspan="2">Valor em Estoque</th>
                <th colspan="2">Estoque Baixo</th>
            </tr>
            <tr>
                <td colspan="2" class="text-center">' . number_format($dadosResumo['total_tipos'] ?? 0, 0, ',', '.') . '</td>
                <td colspan="2" class="text-center">' . number_format($dadosResumo['total_qtd'] ?? 0, 0, ',', '.') . '</td>
                <td colspan="2" class="text-center">R$ ' . number_format($dadosResumo['valor_total'] ?? 0, 2, ',', '.') . '</td>
                <td colspan="2" class="text-center">' . number_format($dadosBaixo['qtd_baixo'] ?? 0, 0, ',', '.') . '</td>
            </tr>
        </table>
        <br>
        <table>
            <tr><td colspan="8" class="secao-titulo">Detalhamento de Peças em Estoque</td></tr>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Peça</th>
                    <th>Categoria</th>
                    <th>Qtd</th>
                    <th>Est. Mínimo</th>
                    <th>Valor Unit.</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';

        $sqlPecas = "SELECT * FROM peca ORDER BY nome_peca ASC";
        $resPecas = mysqli_query($conn, $sqlPecas);

        if ($resPecas && mysqli_num_rows($resPecas) > 0) {
            while ($p = mysqli_fetch_assoc($resPecas)) {
                $subtotal = $p['qtdade_peca'] * $p['valor_unit'];
                $status = ($p['qtdade_peca'] <= $p['estoque_min']) ? 'Estoque Baixo' : 'Normal';

                $htmlBody .= '<tr>
                    <td class="text-center">' . $p["idpeca"] . '</td>
                    <td>' . htmlspecialchars($p["nome_peca"]) . '</td>
                    <td>' . htmlspecialchars($p["categoria"]) . '</td>
                    <td class="text-right">' . $p["qtdade_peca"] . '</td>
                    <td class="text-right">' . $p["estoque_min"] . '</td>
                    <td class="text-right">R$ ' . number_format($p["valor_unit"], 2, ',', '.') . '</td>
                    <td class="text-right">R$ ' . number_format($subtotal, 2, ',', '.') . '</td>
                    <td class="text-center">' . $status . '</td>
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
        <table>
            <tr><td colspan="5" class="secao-titulo">Resumo de Clientes</td></tr>
            <tr>
                <th colspan="5">Total de Clientes</th>
            </tr>
            <tr>
                <td colspan="5" class="text-center">' . $totalClientes . '</td>
            </tr>
        </table>
        <br>
        <table>
            <tr><td colspan="5" class="secao-titulo">Detalhamento de Clientes Cadastrados</td></tr>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome Completo</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Cidade</th>
                </tr>
            </thead>
            <tbody>';

        $sqlClientes = "SELECT * FROM cliente ORDER BY nome ASC";
        $resClientes = mysqli_query($conn, $sqlClientes);

        if ($resClientes && mysqli_num_rows($resClientes) > 0) {
            while ($c = mysqli_fetch_assoc($resClientes)) {
                $htmlBody .= '<tr>
                    <td class="text-center">' . $c["idcliente"] . '</td>
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
        <table>
            <tr><td colspan="4" class="secao-titulo">Resumo da Equipe</td></tr>
            <tr>
                <th colspan="4">Funcionários Ativos</th>
            </tr>
            <tr>
                <td colspan="4" class="text-center">' . $totalFunc . '</td>
            </tr>
        </table>
        <br>
        <table>
            <tr><td colspan="4" class="secao-titulo">Quadro de Funcionários</td></tr>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Funcionário</th>
                    <th>Cargo</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>';

        $sqlFunc = "SELECT * FROM funcionario ORDER BY nome_func ASC";
        $resFunc = mysqli_query($conn, $sqlFunc);

        if ($resFunc && mysqli_num_rows($resFunc) > 0) {
            while ($f = mysqli_fetch_assoc($resFunc)) {
                $htmlBody .= '<tr>
                    <td class="text-center">' . $f["idfuncionario"] . '</td>
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

    // ==========================================
    // TIPO: SERVIÇOS
    // ==========================================
    case 'Serviços':
        $sqlCount = "SELECT COUNT(*) as total FROM servico";
        $resCount = mysqli_query($conn, $sqlCount);
        $totalServico = mysqli_fetch_assoc($resCount)['total'] ?? 0;

        $sqlCountAtivos = "SELECT COUNT(*) as total FROM servico WHERE status = 'a'";
        $resCountAtivos = mysqli_query($conn, $sqlCountAtivos);
        $totalAtivos = mysqli_fetch_assoc($resCountAtivos)['total'] ?? 0;

        $sqlCountInativos = "SELECT COUNT(*) as total FROM servico WHERE status != 'a'";
        $resCountInativos = mysqli_query($conn, $sqlCountInativos);
        $totalInativos = mysqli_fetch_assoc($resCountInativos)['total'] ?? 0;

        $htmlBody .= '
        <table>
            <tr><td colspan="6" class="secao-titulo">Resumo de Serviços</td></tr>
            <tr>
                <th colspan="2">Total de Serviços</th>
                <th colspan="2">Serviços Ativos</th>
                <th colspan="2">Serviços Inativos</th>
            </tr>
            <tr>
                <td colspan="2" class="text-center">' . $totalServico . '</td>
                <td colspan="2" class="text-center">' . $totalAtivos . '</td>
                <td colspan="2" class="text-center">' . $totalInativos . '</td>
            </tr>
        </table>
        <br>
        <table>
            <tr><td colspan="6" class="secao-titulo">Tabela de Serviços</td></tr>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Serviço</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';

        $sqlServico = "SELECT * FROM servico ORDER BY nome_servico ASC";
        $resServico = mysqli_query($conn, $sqlServico);

        if ($resServico && mysqli_num_rows($resServico) > 0) {
            while ($s = mysqli_fetch_assoc($resServico)) {
                $valorFormatado = isset($s['valor']) ? 'R$ ' . number_format($s['valor'], 2, ',', '.') : 'R$ 0,00';

                $htmlBody .= '<tr>
                    <td class="text-center">' . $s["idservico"] . '</td>
                    <td>' . htmlspecialchars($s["nome_servico"]) . '</td>
                    <td>' . htmlspecialchars($s["descricao_servico"] ?? '-') . '</td>
                    <td class="text-right">' . $valorFormatado . '</td>
                    <td class="text-center">' . htmlspecialchars($s["tempo"] ?? '-') . '</td>
                    <td class="text-center">' . htmlspecialchars($s["status"] ?? '-') . '</td>
                </tr>';
            }
        } else {
            $htmlBody .= '<tr><td colspan="6" class="text-center">Nenhum serviço cadastrado.</td></tr>';
        }
        $htmlBody .= '</tbody></table>';
        break;

    default:
        $htmlBody .= '
        <table>
            <tr><td class="secao-titulo">Resumo do Relatório</td></tr>
            <tr><td>Exibindo os dados consolidados do relatório de <strong>' . htmlspecialchars($tipoRelatorio) . '</strong>.</td></tr>
        </table>';
        break;
}

$htmlFooter = '
</body>
</html>';

mysqli_close($conn);

// Limpa qualquer saída no buffer antes de enviar o arquivo
if (ob_get_length()) ob_end_clean();

// Imprime a estrutura HTML configurada como XLS
echo $htmlHeader . $htmlBody . $htmlFooter;
exit;