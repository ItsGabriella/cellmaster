<?php
// php/exportarCsv.php

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

// Configurar o nome do arquivo CSV
$filename = "relatorio_" . strtolower($tipoRelatorio) . "_" . date('Ymd_His') . ".csv";

// Configurar cabeçalhos HTTP para download do CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');
header('Pragma: no-cache');
header('Expires: 0');

// Abrir fluxo de saída para escrita direta
$output = fopen('php://output', 'w');

// UTF-8 BOM para garantir acentuação correta no Excel em português
fwrite($output, "\xEF\xBB\xBF");

// Define o delimitador padrão (ponto e vírgula é o padrão do Excel em português)
$delim = ';';

// --- CABEÇALHO DO DOCUMENTO ---
fputcsv($output, ['RELATÓRIO GERAL DE ' => mb_strtoupper($tipoRelatorio, 'UTF-8')], $delim);
fputcsv($output, ['Exportado em:', date('d/m/Y H:i')], $delim);
fputcsv($output, [], $delim); // Linha em branco

// --- INFORMAÇÕES DOS RELATÓRIOS SELECIONADOS ---
fputcsv($output, ['INFORMAÇÕES DO(S) RELATÓRIO(S) SELECIONADO(S)'], $delim);
fputcsv($output, ['ID', 'Nome do Relatório', 'Tipo', 'Data', 'Responsável', 'Status'], $delim);

while ($rel = mysqli_fetch_assoc($resRelatorios)) {
    fputcsv($output, [
        '#' . $rel["idrelatorio"],
        $rel["nome_relatorio"],
        $rel["tipo"],
        date("d/m/Y", strtotime($rel["geracao_data"])),
        $rel["responsavel"],
        $rel["status"]
    ], $delim);
}

fputcsv($output, [], $delim); // Linha em branco

// 3. BLOCO DE DADOS CONFORME O TIPO
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

        // Resumo
        fputcsv($output, ['RESUMO DO ESTOQUE ATUAL'], $delim);
        fputcsv($output, ['Tipos de Peças', 'Total de Peças', 'Valor em Estoque (R$)', 'Estoque Baixo'], $delim);
        fputcsv($output, [
            number_format($dadosResumo['total_tipos'] ?? 0, 0, ',', '.'),
            number_format($dadosResumo['total_qtd'] ?? 0, 0, ',', '.'),
            number_format($dadosResumo['valor_total'] ?? 0, 2, ',', '.'),
            number_format($dadosBaixo['qtd_baixo'] ?? 0, 0, ',', '.')
        ], $delim);

        fputcsv($output, [], $delim);

        // Tabela detalhada
        fputcsv($output, ['DETALHAMENTO DE PEÇAS EM ESTOQUE'], $delim);
        fputcsv($output, ['ID', 'Nome da Peça', 'Categoria', 'Qtd', 'Est. Mínimo', 'Valor Unit. (R$)', 'Subtotal (R$)', 'Status'], $delim);

        $sqlPecas = "SELECT * FROM peca ORDER BY nome_peca ASC";
        $resPecas = mysqli_query($conn, $sqlPecas);

        if ($resPecas && mysqli_num_rows($resPecas) > 0) {
            while ($p = mysqli_fetch_assoc($resPecas)) {
                $subtotal = $p['qtdade_peca'] * $p['valor_unit'];
                $status = ($p['qtdade_peca'] <= $p['estoque_min']) ? 'Estoque Baixo' : 'Normal';

                fputcsv($output, [
                    $p["idpeca"],
                    $p["nome_peca"],
                    $p["categoria"],
                    $p["qtdade_peca"],
                    $p["estoque_min"],
                    number_format($p["valor_unit"], 2, ',', '.'),
                    number_format($subtotal, 2, ',', '.'),
                    $status
                ], $delim);
            }
        } else {
            fputcsv($output, ['Nenhuma peça cadastrada.'], $delim);
        }
        break;

    // ==========================================
    // TIPO: CLIENTES
    // ==========================================
    case 'Clientes':
        $sqlCount = "SELECT COUNT(*) as total FROM cliente";
        $resCount = mysqli_query($conn, $sqlCount);
        $totalClientes = mysqli_fetch_assoc($resCount)['total'] ?? 0;

        fputcsv($output, ['RESUMO DE CLIENTES'], $delim);
        fputcsv($output, ['Total de Clientes'], $delim);
        fputcsv($output, [$totalClientes], $delim);

        fputcsv($output, [], $delim);

        fputcsv($output, ['DETALHAMENTO DE CLIENTES CADASTRADOS'], $delim);
        fputcsv($output, ['ID', 'Nome Completo', 'Telefone', 'E-mail', 'Cidade'], $delim);

        $sqlClientes = "SELECT * FROM cliente ORDER BY nome ASC";
        $resClientes = mysqli_query($conn, $sqlClientes);

        if ($resClientes && mysqli_num_rows($resClientes) > 0) {
            while ($c = mysqli_fetch_assoc($resClientes)) {
                fputcsv($output, [
                    $c["idcliente"],
                    $c["nome"],
                    $c["telefone"] ?? '-',
                    $c["email"] ?? '-',
                    $c["cidade"] ?? '-'
                ], $delim);
            }
        } else {
            fputcsv($output, ['Nenhum cliente cadastrado.'], $delim);
        }
        break;

    // ==========================================
    // TIPO: FUNCIONÁRIOS
    // ==========================================
    case 'Funcionários':
        $sqlCount = "SELECT COUNT(*) as total FROM funcionario";
        $resCount = mysqli_query($conn, $sqlCount);
        $totalFunc = mysqli_fetch_assoc($resCount)['total'] ?? 0;

        fputcsv($output, ['RESUMO DA EQUIPE'], $delim);
        fputcsv($output, ['Funcionários Ativos'], $delim);
        fputcsv($output, [$totalFunc], $delim);

        fputcsv($output, [], $delim);

        fputcsv($output, ['QUADRO DE FUNCIONÁRIOS'], $delim);
        fputcsv($output, ['ID', 'Nome do Funcionário', 'Cargo', 'Telefone'], $delim);

        $sqlFunc = "SELECT * FROM funcionario ORDER BY nome_func ASC";
        $resFunc = mysqli_query($conn, $sqlFunc);

        if ($resFunc && mysqli_num_rows($resFunc) > 0) {
            while ($f = mysqli_fetch_assoc($resFunc)) {
                fputcsv($output, [
                    $f["idfuncionario"],
                    $f["nome_func"],
                    $f["cargo"] ?? 'Gerente',
                    $f["tel_func"]
                ], $delim);
            }
        } else {
            fputcsv($output, ['Nenhum funcionário cadastrado.'], $delim);
        }
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

        fputcsv($output, ['RESUMO DE SERVIÇOS'], $delim);
        fputcsv($output, ['Total de Serviços', 'Serviços Ativos', 'Serviços Inativos'], $delim);
        fputcsv($output, [$totalServico, $totalAtivos, $totalInativos], $delim);

        fputcsv($output, [], $delim);

        fputcsv($output, ['TABELA DE SERVIÇOS'], $delim);
        fputcsv($output, ['ID', 'Nome do Serviço', 'Descrição', 'Valor (R$)', 'Tempo', 'Status'], $delim);

        $sqlServico = "SELECT * FROM servico ORDER BY nome_servico ASC";
        $resServico = mysqli_query($conn, $sqlServico);

        if ($resServico && mysqli_num_rows($resServico) > 0) {
            while ($s = mysqli_fetch_assoc($resServico)) {
                $valorFormatado = isset($s['valor']) ? number_format($s['valor'], 2, ',', '.') : '0,00';

                fputcsv($output, [
                    $s["idservico"],
                    $s["nome_servico"],
                    $s["descricao_servico"] ?? '-',
                    $valorFormatado,
                    $s["tempo"] ?? '-',
                    $s["status"] ?? '-'
                ], $delim);
            }
        } else {
            fputcsv($output, ['Nenhum serviço cadastrado.'], $delim);
        }
        break;

    default:
        fputcsv($output, ['RESUMO DO RELATÓRIO'], $delim);
        fputcsv($output, ['Dados consolidados do relatório de ' . $tipoRelatorio], $delim);
        break;
}

// Fechar conexão e stream de saída
fclose($output);
mysqli_close($conn);
exit;