<?php

function listaRelatorio($periodo = 'todos'){

    $funcionarios = ListarFuncionarios();

    include("conexaoBD.php");
    
    // 1. Criar a regra de filtragem de data para o SQL
    $where_date = "";

    switch ($periodo) {
        case 'hoje':
            $where_date = "WHERE DATE(geracao_data) = CURRENT_DATE()";
            break;

        case '7_dias':
            $where_date = "WHERE geracao_data >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
            break;

        case 'ultimo_mes':
            $where_date = "WHERE MONTH(geracao_data) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) 
                           AND YEAR(geracao_data) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))";
            break;

        case 'este_mes':
            $where_date = "WHERE MONTH(geracao_data) = MONTH(CURRENT_DATE()) 
                           AND YEAR(geracao_data) = YEAR(CURRENT_DATE())";
            break;

        case 'todos':
        default:
            $where_date = "";
            break;
    }

    $sql = "SELECT * FROM relatorio " . $where_date . " ORDER BY idrelatorio ASC;";
            
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    $lista = '';

    if ($result && mysqli_num_rows($result) > 0) {        
        
        foreach ($result as $coluna) {

            // Cards de Resumo dinâmicos baseados nas tabelas reais do BD
            $detalhesExtraHtml = '';
            $dtInicio = isset($coluna["data_inicio"]) ? $coluna["data_inicio"] : null;
            $dtFim    = isset($coluna["data_fim"]) ? $coluna["data_fim"] : null;

            switch ($coluna["tipo"]) {

                case 'Estoque':
                    $m = getResumoEstoque();
                    $detalhesExtraHtml = '
                    <div class="col-12 mt-3"><h6 class="fw-bold text-success border-bottom pb-2"><i class="fa-solid fa-boxes-stacked me-2"></i>Resumo do Estoque</h6></div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">TOTAL DE ITENS</span>
                            <span class="fs-4 fw-bold text-dark">'.number_format($m['total_itens'], 0, ',', '.').' un</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">VALOR ACUMULADO</span>
                            <span class="fs-4 fw-bold text-success">R$ '.number_format($m['valor_total'], 2, ',', '.').'</span>
                        </div>
                    </div>';
                    break;

                case 'Clientes':
                    $m = getResumoClientes($dtInicio, $dtFim);
                    $detalhesExtraHtml = '
                    <div class="col-12 mt-3"><h6 class="fw-bold text-success border-bottom pb-2"><i class="fa-solid fa-users me-2"></i>Resumo de Clientes</h6></div>
                    <div class="col-md-12">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">TOTAL DE CLIENTES CADASTRADOS</span>
                            <span class="fs-4 fw-bold text-primary">'.$m['total_clientes'].' clientes</span>
                        </div>
                    </div>';
                    break;

                case 'Funcionários':
                    $m = getResumoFuncionarios();
                    $detalhesExtraHtml = '
                    <div class="col-12 mt-3"><h6 class="fw-bold text-success border-bottom pb-2"><i class="fa-solid fa-id-card me-2"></i>Resumo da Equipe</h6></div>
                    <div class="col-md-12">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">TOTAL DE FUNCIONÁRIOS</span>
                            <span class="fs-4 fw-bold text-dark">'.$m['total_funcionarios'].' funcionários</span>
                        </div>
                    </div>';
                    break;

                case 'Serviços':
                    $m = getResumoServicos();
                    $detalhesExtraHtml = '
                    <div class="col-12 mt-3"><h6 class="fw-bold text-success border-bottom pb-2"><i class="fa-solid fa-wrench me-2"></i>Catálogo de Serviços</h6></div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">SERVIÇOS CADASTRADOS</span>
                            <span class="fs-4 fw-bold text-dark">'.$m['total_servicos'].'</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">VALOR TOTAL DO CATÁLOGO</span>
                            <span class="fs-4 fw-bold text-success">R$ '.number_format($m['valor_total'], 2, ',', '.').'</span>
                        </div>
                    </div>';
                    break;

                case 'Orçamento':
                    $m = getResumoOrcamentos($dtInicio, $dtFim);
                    $detalhesExtraHtml = '
                    <div class="col-12 mt-3"><h6 class="fw-bold text-success border-bottom pb-2"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Resumo de Orçamentos</h6></div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">ORÇAMENTOS EMITIDOS</span>
                            <span class="fs-4 fw-bold text-dark">'.$m['total_orcamentos'].'</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">VALOR TOTAL ORÇADO</span>
                            <span class="fs-4 fw-bold text-success">R$ '.number_format($m['valor_total'], 2, ',', '.').'</span>
                        </div>
                    </div>';
                    break;

                case 'Ordem de Serviço':
                    $m = getResumoOS($dtInicio, $dtFim);
                    $detalhesExtraHtml = '
                    <div class="col-12 mt-3"><h6 class="fw-bold text-success border-bottom pb-2"><i class="fa-solid fa-clipboard-list me-2"></i>Resumo de Ordens de Serviço (O.S.)</h6></div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">TOTAL DE O.S.</span>
                            <span class="fs-4 fw-bold text-dark">'.$m['total_os'].'</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="d-block text-secondary small fw-bold">FATURAMENTO O.S.</span>
                            <span class="fs-4 fw-bold text-primary">R$ '.number_format($m['valor_total'], 2, ',', '.').'</span>
                        </div>
                    </div>';
                    break;
            }

            $lista .= 
            '<tr>
                <td>
                    <input type="checkbox" 
                    class="form-check-input checkbox-relatorio" name="relatorios[]"
                    value="'.$coluna["idrelatorio"].'">
                </td>

                <td>'.$coluna["idrelatorio"].'</td>
                <td>'.$coluna["nome_relatorio"].'</td>
                <td>'.$coluna["tipo"].'</td>
                <td>'.(!empty($coluna["geracao_data"]) ? date('d/m/Y', strtotime($coluna["geracao_data"])) : 'N/A').'</td>
                <td>'.$coluna["status"].'</td>

                <td>
                    <button type="button" class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditarRelatorio'.$coluna["idrelatorio"].'">
                    <i class="fa-solid fa-pen"></i>
                    </button>

                    <button type="button" class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalExcluirRelatorio'.$coluna["idrelatorio"].'">
                    <i class="fa-solid fa-trash"></i>
                    </button>

                    <button type="button" class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalVisualizarRelatorio'.$coluna["idrelatorio"].'">
                    <i class="fa-solid fa-eye"></i>
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="modalExcluirRelatorio'.$coluna["idrelatorio"].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">

                        <div class="modal-body text-center p-4">

                            <div class="mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width:80px;height:80px;">
                                    <i class="bi bi-trash text-danger fs-1"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold">Excluir Relatório</h3>
                            <form method="POST" action="php/salvarRelatorio.php?funcao=D&codigo='.$coluna["idrelatorio"].'">
                                <p class="text-secondary">Tem certeza que deseja excluir o relatório <strong style="color: red;">'.$coluna["nome_relatorio"].'</strong>?</p>
                                <p class="text-muted">Esta ação não poderá ser desfeita.</p>
                                <div class="d-flex gap-2 justify-content-center mt-4">
                                    <button type="button" class="btn btn-outline-success px-4" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger px-4">Excluir</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="modalEditarRelatorio'.$coluna["idrelatorio"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="fa-solid fa-box-archive me-2"></i> Editar Relatório</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="php/salvarRelatorio.php?funcao=U&codigo='.$coluna["idrelatorio"].'">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary">Relatório</label>
                                        <input type="text" class="form-control" name="nRelatorio" value="'.$coluna["nome_relatorio"].'">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary">Tipo</label>
                                        <select name="nTipo" class="form-select">
                                            <option '.($coluna["tipo"]=='Clientes'?'selected':'').'>Clientes</option> 
                                            <option '.($coluna["tipo"]=='Funcionários'?'selected':'').'>Funcionários</option> 
                                            <option '.($coluna["tipo"]=='Serviços'?'selected':'').'>Serviços</option> 
                                            <option '.($coluna["tipo"]=='Estoque'?'selected':'').'>Estoque</option> 
                                            <option '.($coluna["tipo"]=='Orçamento'?'selected':'').'>Orçamento</option> 
                                            <option '.($coluna["tipo"]=='Ordem de Serviço'?'selected':'').'>Ordem de Serviço</option> 
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary">Data</label>
                                        <input type="date" class="form-control" name="nData" value="'.$coluna["geracao_data"].'">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary">Responsável</label>
                                        <select name="nResponsavel" class="form-select" required>';
                                            if($funcionarios){
                                                mysqli_data_seek($funcionarios, 0);
                                                while($func = mysqli_fetch_assoc($funcionarios)){
                                                    $selected = ($func["nome_func"] == $coluna["responsavel"]) ? 'selected' : '';
                                                    $lista .= '<option '.$selected.'>'.$func["nome_func"].'</option>';
                                                }
                                            }
                                        $lista .= '</select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary">Status</label>
                                        <select name="nStatus" class="form-select">
                                            <option '.($coluna["status"]=='Pendente'?'selected':'').'>Pendente</option>
                                            <option '.($coluna["status"]=='Em andamento'?'selected':'').'>Em andamento</option>
                                            <option '.($coluna["status"]=='Concluído'?'selected':'').'>Concluído</option>
                                            <option '.($coluna["status"]=='Enviado'?'selected':'').'>Enviado</option>
                                            <option '.($coluna["status"]=='Arquivado'?'selected':'').'>Arquivado</option>
                                            <option '.($coluna["status"]=='Cancelado'?'selected':'').'>Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i> Salvar Alterações</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalVisualizarRelatorio'.$coluna["idrelatorio"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="fa-solid fa-eye me-2"></i> Detalhes do Relatório #'.$coluna["idrelatorio"].'</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-secondary">Código ID</label>
                                    <input type="text" class="form-control bg-light" value="#'.$coluna["idrelatorio"].'" readonly>
                                </div>

                                <div class="col-md-9">
                                    <label class="form-label fw-bold text-secondary">Nome do Relatório</label>
                                    <input type="text" class="form-control bg-light" value="'.$coluna["nome_relatorio"].'" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Tipo de Relatório</label>
                                    <input type="text" class="form-control bg-light" value="'.$coluna["tipo"].'" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Data de Criação</label>
                                    <input type="text" class="form-control bg-light" value="'.(!empty($coluna["geracao_data"]) ? date('d/m/Y', strtotime($coluna["geracao_data"])) : 'N/A').'" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Período Início (De)</label>
                                    <input type="text" class="form-control bg-light" value="'.(!empty($coluna["data_inicio"]) ? date('d/m/Y', strtotime($coluna["data_inicio"])) : 'Não informado').'" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Período Fim (Até)</label>
                                    <input type="text" class="form-control bg-light" value="'.(!empty($coluna["data_fim"]) ? date('d/m/Y', strtotime($coluna["data_fim"])) : 'Não informado').'" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Última Alteração</label>
                                    <input type="text" class="form-control bg-light" value="'.(!empty($coluna["data_alteracao"]) ? date('d/m/Y H:i:s', strtotime($coluna["data_alteracao"])) : 'Nenhuma alteração').'" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Responsável</label>
                                    <input type="text" class="form-control bg-light" value="'.$coluna["responsavel"].'" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Status Atual</label>
                                    <input type="text" class="form-control bg-light" value="'.$coluna["status"].'" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Exportado?</label>
                                    <input type="text" class="form-control bg-light" value="'.(isset($coluna["exportado"]) && $coluna["exportado"] == 'Sim' ? 'Sim' : 'Não').'" readonly>
                                </div>

                                '.$detalhesExtraHtml.'
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }    
    }
    
    return $lista;
}


function TotalRelatorios()
{
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM relatorio";
    $resultado = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_close($conn);
    return $dados["total"];
}

function RelatoriosMes()
{
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM relatorio WHERE MONTH(geracao_data) = MONTH(CURRENT_DATE()) AND YEAR(geracao_data) = YEAR(CURRENT_DATE())";
    $resultado = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_close($conn);
    return $dados["total"];
}

function RelatoriosPendentes()
{
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM relatorio WHERE status = 'Pendente'";
    $resultado = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_close($conn);
    return $dados["total"];
}

function RelatoriosExportados()
{
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM relatorio WHERE exportado = 'Sim'";
    $resultado = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_close($conn);
    return $dados["total"];
}

function ListarFuncionarios()
{
    include("conexaoBD.php");
    $sql = "SELECT idfuncionario, nome_func FROM funcionario ORDER BY nome_func";
    $resultado = mysqli_query($conn, $sql);
    return $resultado;
}

function BuscarRelatorio($buscaR)
{
    include("conexaoBD.php");
    $buscaS = mysqli_real_escape_string($conn, $buscaR);
    $sql = "SELECT * FROM relatorio WHERE nome_relatorio LIKE '%$buscaS%' ORDER BY nome_relatorio";
    $result = mysqli_query($conn, $sql);
    $lista = "";

    if($result && mysqli_num_rows($result) > 0) {
        foreach($result as $coluna) {
            $lista .= '
            <tr>
                <td>'.$coluna["idrelatorio"].'</td>
                <td>'.$coluna["nome_relatorio"].'</td>
                <td>'.$coluna["tipo"].'</td>
                <td>'.$coluna["geracao_data"].'</td>
                <td>'.$coluna["responsavel"].'</td>
                <td>'.$coluna["exportado"].'</td>
                <td>'.$coluna["status"].'</td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarRelatorio'.$coluna["idrelatorio"].'"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalExcluirRelatorio'.$coluna["idrelatorio"].'"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>';
        }
    }
    mysqli_close($conn);
    return $lista;
}

function proxIdRelatorio(){
    $id = 1;
    include("conexaoBD.php");
    
    $sql = "SELECT IFNULL(MAX(idrelatorio), 0) AS Maior FROM relatorio;";        
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $coluna = mysqli_fetch_assoc($result);
        $id = $coluna["Maior"] + 1;
    } 
    
    mysqli_close($conn);
    return $id;
}

function graficoRelatoriosMes(){
    include("conexaoBD.php");
    $sql = "SELECT MONTH(geracao_data) AS mes, COUNT(*) AS total FROM relatorio GROUP BY MONTH(geracao_data) ORDER BY MONTH(geracao_data)";
    $resultado = mysqli_query($conn, $sql);
    $meses = [];
    $totais = [];
    $nomeMes = [1=>"Jan",2=>"Fev",3=>"Mar",4=>"Abr",5=>"Mai",6=>"Jun",7=>"Jul",8=>"Ago",9=>"Set",10=>"Out",11=>"Nov",12=>"Dez"];

    if($resultado){
        while($linha = mysqli_fetch_assoc($resultado)){
            $meses[] = $nomeMes[$linha["mes"]];
            $totais[] = $linha["total"];
        }
    }
    mysqli_close($conn);

    return ["meses" => $meses, "totais" => $totais];
}

function graficoRelatoriosTipo(){
    include("conexaoBD.php");
    $sql = "SELECT tipo, COUNT(*) AS total FROM relatorio GROUP BY tipo";
    $resultado = mysqli_query($conn, $sql);
    $tipos = [];
    $quantidades = [];

    if($resultado){
        while($linha = mysqli_fetch_assoc($resultado)){
            $tipos[] = $linha["tipo"];
            $quantidades[] = $linha["total"];
        }
    }
    mysqli_close($conn);

    return ["tipos" => $tipos, "quantidades" => $quantidades];
}

// --- FUNÇÕES DE CONSULTA BASEADAS NO BANCO DE DADOS REAL ---

// 1. Tabela: peca (qtdade_peca, valor_unit)
function getResumoEstoque() {
    include("conexaoBD.php");
    $sql = "SELECT IFNULL(SUM(qtdade_peca), 0) AS total_itens, IFNULL(SUM(qtdade_peca * valor_unit), 0) AS valor_total FROM peca";
    $res = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($res) ?: ['total_itens' => 0, 'valor_total' => 0];
    mysqli_close($conn);
    return $dados;
}

// 2. Tabela: cliente
function getResumoClientes($dt_inicio = null, $dt_fim = null) {
    include("conexaoBD.php");
    $where = (!empty($dt_inicio) && !empty($dt_fim)) ? " WHERE data_cadastro BETWEEN '$dt_inicio' AND '$dt_fim'" : "";
    $sql = "SELECT COUNT(*) AS total_clientes FROM cliente $where";
    $res = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($res) ?: ['total_clientes' => 0];
    mysqli_close($conn);
    return $dados;
}

// 3. Tabela: funcionario
function getResumoFuncionarios() {
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total_funcionarios FROM funcionario";
    $res = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($res) ?: ['total_funcionarios' => 0];
    mysqli_close($conn);
    return $dados;
}

// 4. Tabela: servico (valor)
function getResumoServicos() {
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total_servicos, IFNULL(SUM(valor), 0) AS valor_total FROM servico";
    $res = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($res) ?: ['total_servicos' => 0, 'valor_total' => 0];
    mysqli_close($conn);
    return $dados;
}

// 5. Tabela: orcamento (valor_total)
function getResumoOrcamentos($dt_inicio = null, $dt_fim = null) {
    include("conexaoBD.php");
    $where = (!empty($dt_inicio) && !empty($dt_fim)) ? " WHERE data_dia BETWEEN '$dt_inicio' AND '$dt_fim'" : "";
    $sql = "SELECT COUNT(*) AS total_orcamentos, IFNULL(SUM(valor_total), 0) AS valor_total FROM orcamento $where";
    $res = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($res) ?: ['total_orcamentos' => 0, 'valor_total' => 0];
    mysqli_close($conn);
    return $dados;
}

// 6. Tabela: ordem_servico (data_abertura, valor_final)
function getResumoOS($dt_inicio = null, $dt_fim = null) {
    include("conexaoBD.php");
    $where = (!empty($dt_inicio) && !empty($dt_fim)) ? " WHERE data_abertura BETWEEN '$dt_inicio' AND '$dt_fim'" : "";
    $sql = "SELECT COUNT(*) AS total_os, IFNULL(SUM(valor_final), 0) AS valor_total FROM ordem_servico $where";
    $res = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($res) ?: ['total_os' => 0, 'valor_total' => 0];
    mysqli_close($conn);
    return $dados;
}

?>