<?php

// Próximo ID do serviço
function proxIdServico(){
    $id = 1;

    include("conexaoBD.php");
    $sql = "SELECT MAX(idservico) AS Maior FROM servico;";        
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $linha = mysqli_fetch_assoc($result);
        if (!empty($linha["Maior"])) {
            $id = $linha["Maior"] + 1;
        }
    } 
    mysqli_close($conn);

    return $id;
}

function TotalServicos() {
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM servico";
    $resultado = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_close($conn);
    return $dados["total"] ?? 0;
}

function ServicosAtivos() {
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM servico WHERE LOWER(status) = 'ativo'";
    $resultado = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_close($conn);
    return $dados["total"] ?? 0;
}

function ServicosInativos() {
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM servico WHERE LOWER(status) = 'inativo'";
    $resultado = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_close($conn);
    return $dados["total"] ?? 0;
}

function ValorMedioServico() {
    include("conexaoBD.php");
    $sql = "SELECT AVG(valor) AS media FROM servico";
    $resultado = mysqli_query($conn, $sql);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_close($conn);
    return $dados["media"] ?? 0;
}

// Função de busca unificada (com suporte a filtros por nome e/ou status e inclusão de modais)
function BuscarServico($buscaS = "", $statusS = "") {
    include("conexaoBD.php");

    $sql = "SELECT * FROM servico WHERE 1=1";

    // Filtro por Nome
    if (!empty($buscaS)) {
        $buscaEscaped = mysqli_real_escape_string($conn, $buscaS);
        $sql .= " AND nome_servico LIKE '%$buscaEscaped%'";
    }

    // Filtro por Status
    if (!empty($statusS)) {
        $statusEscaped = mysqli_real_escape_string($conn, $statusS);
        $sql .= " AND status = '$statusEscaped'";
    }

    $sql .= " ORDER BY idservico DESC";

    $result = mysqli_query($conn, $sql);
    $lista = "";

    if ($result && mysqli_num_rows($result) > 0) {
        foreach ($result as $coluna) {
            $valorFormatado = number_format($coluna["valor"], 2, ",", ".");
            $statusBadge = (strtolower($coluna["status"]) == "ativo") 
                ? '<span class="badge bg-success">Ativo</span>' 
                : '<span class="badge bg-secondary">Inativo</span>';

            $lista .= '
            <tr>
                <td>' . $coluna["idservico"] . '</td>
                <td>' . htmlspecialchars($coluna["nome_servico"]) . '</td>
                <td>' . htmlspecialchars($coluna["descricao_servico"]) . '</td>
                <td>R$ ' . $valorFormatado . '</td>
                <td>' . htmlspecialchars($coluna["tempo"]) . '</td>
                <td>' . $statusBadge . '</td>
                <td>
                    <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEditarServico' . $coluna["idservico"] . '" title="Editar">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalExcluirServico' . $coluna["idservico"] . '" title="Excluir">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="modalExcluirServico'.$coluna["idservico"].'" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Confirmar Exclusão</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body text-center py-4">
                    <i class="fa-solid fa-trash text-danger fa-3x mb-3"></i>
                    <p class="fs-5 fw-semibold mb-1">Tem certeza que deseja excluir?</p>
                    <p class="text-muted small mb-0">Você está prestes a remover o serviço <strong>'.htmlspecialchars($coluna["nome_servico"]).'</strong>. Esta ação não poderá ser desfeita.</p>
                  </div>
                  <div class="modal-footer bg-light border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <a href="php/salvarServico.php?codigo='.$coluna["idservico"].'&funcao=D" class="btn btn-danger px-4 fw-semibold">Sim, Excluir</a>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="modal fade" id="modalEditarServico' . $coluna["idservico"] . '" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fa-solid fa-pen-to-square me-2"></i>
                                Editar Serviço
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form method="POST" action="php/salvarServico.php?funcao=U&codigo=' . $coluna["idservico"] . '">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nome do Serviço</label>
                                        <input type="text" class="form-control" name="nServico" value="' . htmlspecialchars($coluna["nome_servico"]) . '" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Valor</label>
                                        <input type="number" class="form-control" step="0.01" name="nValor" value="' . $coluna["valor"] . '" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tempo estimado</label>
                                        <input type="time" class="form-control" name="nTempo" value="' . $coluna["tempo"] . '">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select" name="nStatus">
                                            <option value="Ativo" ' . ($coluna["status"] == 'Ativo' ? 'selected' : '') . '>Ativo</option>
                                            <option value="Inativo" ' . ($coluna["status"] == 'Inativo' ? 'selected' : '') . '>Inativo</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Descrição</label>
                                        <textarea class="form-control" name="nDescricao" rows="3">' . htmlspecialchars($coluna["descricao_servico"]) . '</textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> Salvar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>';
        }
    } else {
        $lista = '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum serviço encontrado.</td></tr>';
    }

    mysqli_close($conn);
    return $lista;
}

// Mantido para retrocompatibilidade
function listaServico() {
    return BuscarServico("", "");
}

?>