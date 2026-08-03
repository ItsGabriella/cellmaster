<?php

// Função para converter o código do cargo em texto amigável
function getNomeCargo($idCargo) {
    switch ($idCargo) {
        case 1:
            return 'Gerente';
        case 2:
            return 'Técnico';
        case 3:
            return 'Atendente';
        default:
            return 'Não Definido';
    }
}

// Função para listar todos os funcionários
function listaFuncionario(){
    include("conexaoBD.php");
    $sql = "SELECT * FROM funcionario ORDER BY idfuncionario DESC;";
            
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    $lista = '';

    if ($result && mysqli_num_rows($result) > 0) {        
        foreach ($result as $coluna) {
            $cargoNome = getNomeCargo($coluna["cargos_idcargos"]);

            // Converte o valor de data_cadastro para o formato dd/mm/AAAA
            $dataCadastro = !empty($coluna["data_cadastro"]) ? date("d/m/Y", strtotime($coluna["data_cadastro"])) : "-";

            $lista .= 
            '<tr>
                <td>'.$coluna["idfuncionario"].'</td>
                <td>'.$coluna["nome_func"].'</td>
                <td><span class="badge bg-secondary">'.$cargoNome.'</span></td>
                <td>'.$coluna["tel_func"].'</td>
                <td>'.$coluna["email_func"].'</td>
                <td>'.$dataCadastro.'</td>

                <td>
                    <button class="btn btn-success btn-sm me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditar'.$coluna["idfuncionario"].'"
                            title="Editar">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalExcluir'.$coluna["idfuncionario"].'"
                            title="Excluir">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="modalExcluir'.$coluna["idfuncionario"].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-body text-center p-4">
                            <div class="mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width:80px;height:80px;">
                                    <i class="bi bi-trash text-danger fs-1"></i>
                                </div>
                            </div>

                            <h3 class="fw-bold">Excluir Funcionário</h3>

                            <form method="POST" action="php/salvarFuncionario.php?funcao=D&IDFunc='.$coluna["idfuncionario"].'">
                                <p class="text-secondary mb-1">
                                    Tem certeza que deseja excluir o funcionário
                                    <strong class="text-danger">'.$coluna["nome_func"].'</strong>?
                                </p>
                                <p class="text-muted small">Esta ação não poderá ser desfeita.</p>

                                <div class="d-flex gap-2 justify-content-center mt-4">
                                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-danger px-4">
                                        Excluir
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="modalEditar'.$coluna["idfuncionario"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fa-solid fa-user-pen me-2"></i>
                                Editar Funcionário
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form method="POST" action="php/salvarFuncionario.php?funcao=U&IDFunc='.$coluna["idfuncionario"].'">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nome do Funcionário</label>
                                        <input type="text" class="form-control nome" name="nFuncionario" value="'.$coluna["nome_func"].'" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cargo</label>
                                        <select class="form-select cargo" name="nCargo" required>
                                            <option value="1" '.($coluna["cargos_idcargos"] == 1 ? 'selected' : '').'>1 - Gerente</option>
                                            <option value="2" '.($coluna["cargos_idcargos"] == 2 ? 'selected' : '').'>2 - Técnico</option>
                                            <option value="3" '.($coluna["cargos_idcargos"] == 3 ? 'selected' : '').'>3 - Atendente</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Telefone</label>
                                        <input type="text" class="form-control telefone" name="nTelefone" value="'.$coluna["tel_func"].'" maxlength="15" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">E-mail</label>
                                        <input type="email" class="form-control" name="nmail" value="'.$coluna["email_func"].'" required>
                                    </div>
                                </div>

                                <div class="modal-footer mt-4 px-0 pb-0">
                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa-solid fa-floppy-disk me-2"></i>
                                        Salvar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>';
        }    
    } else {
        $lista = '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum funcionário encontrado.</td></tr>';
    }
    
    return $lista;
}

// Próximo ID do Funcionário
function proxIdFuncionario(){
    $id = 1;

    include("conexaoBD.php");
    $sql = "SELECT MAX(idfuncionario) AS Maior FROM funcionario;";        
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $linha = mysqli_fetch_assoc($result);
        if ($linha["Maior"] != null) {
            $id = $linha["Maior"] + 1;
        }
    } 
    mysqli_close($conn);

    return $id;
}

// Função de Busca com Modais incluídos
function filtrarFuncionarios($busca, $cargo) {
    include("conexaoBD.php");

    $sql = "SELECT * FROM funcionario";
    $condicoes = array();

    // 1. Filtro de Texto (Nome ou E-mail)
    if (!empty($busca)) {
        $buscaEscaped = mysqli_real_escape_string($conn, $busca);
        $condicoes[] = "(nome_func LIKE '%$buscaEscaped%' OR email_func LIKE '%$buscaEscaped%')";
    }

    // 2. Filtro por Cargo
    if ($cargo !== "Todos" && !empty($cargo)) {
        $cargoEscaped = mysqli_real_escape_string($conn, $cargo);
        $condicoes[] = "cargos_idcargos = '$cargoEscaped'";
    }

    // Aplica as cláusulas WHERE
    if (count($condicoes) > 0) {
        $sql .= " WHERE " . implode(" AND ", $condicoes);
    }

    $sql .= " ORDER BY idfuncionario DESC";

    $result = mysqli_query($conn, $sql);
    $lista = "";

    if ($result && mysqli_num_rows($result) > 0) {
        foreach ($result as $coluna) {
            $cargoNome = getNomeCargo($coluna["cargos_idcargos"]);
            
            // Converte o valor de data_cadastro para o formato dd/mm/AAAA
            $dataCadastro = !empty($coluna["data_cadastro"]) ? date("d/m/Y", strtotime($coluna["data_cadastro"])) : "-";

            $lista .= '
            <tr>
                <td>'.$coluna["idfuncionario"].'</td>
                <td>'.$coluna["nome_func"].'</td>
                <td><span class="badge bg-secondary">'.$cargoNome.'</span></td>
                <td>'.$coluna["tel_func"].'</td>
                <td>'.$coluna["email_func"].'</td>
                <td>'.$dataCadastro.'</td>
                <td>
                    <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEditar'.$coluna["idfuncionario"].'" title="Editar">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalExcluir'.$coluna["idfuncionario"].'" title="Excluir">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="modalExcluir'.$coluna["idfuncionario"].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-body text-center p-4">
                            <h3 class="fw-bold">Excluir Funcionário</h3>
                            <form method="POST" action="php/salvarFuncionario.php?funcao=D&IDFunc='.$coluna["idfuncionario"].'">
                                <p class="text-secondary">Tem certeza que deseja excluir <strong>'.$coluna["nome_func"].'</strong>?</p>
                                <div class="d-flex gap-2 justify-content-center mt-4">
                                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger px-4">Excluir</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalEditar'.$coluna["idfuncionario"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="fa-solid fa-user-pen me-2"></i>Editar Funcionário</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="php/salvarFuncionario.php?funcao=U&IDFunc='.$coluna["idfuncionario"].'">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nome do Funcionário</label>
                                        <input type="text" class="form-control nome" name="nFuncionario" value="'.$coluna["nome_func"].'" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cargo</label>
                                        <select class="form-select cargo" name="nCargo" required>
                                            <option value="1" '.($coluna["cargos_idcargos"] == 1 ? 'selected' : '').'>1 - Gerente</option>
                                            <option value="2" '.($coluna["cargos_idcargos"] == 2 ? 'selected' : '').'>2 - Técnico</option>
                                            <option value="3" '.($coluna["cargos_idcargos"] == 3 ? 'selected' : '').'>3 - Atendente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Telefone</label>
                                        <input type="text" class="form-control telefone" name="nTelefone" value="'.$coluna["tel_func"].'" maxlength="15" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">E-mail</label>
                                        <input type="email" class="form-control" name="nmail" value="'.$coluna["email_func"].'" required>
                                    </div>
                                </div>
                                <div class="modal-footer mt-4 px-0 pb-0">
                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Salvar Alterações</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>';
        }
    } else {
        $lista = '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum funcionário encontrado.</td></tr>';
    }

    mysqli_close($conn);
    return $lista;
}

// --- FUNÇÕES DE ESTATÍSTICAS (CARDS SUPERIORES) ---

function TotalFuncionarios() {
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM funcionario;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $row['total'] ?? 0;
}

function TotalTecnicos() {
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM funcionario WHERE cargos_idcargos = 2;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $row['total'] ?? 0;
}

function TotalAtendentes() {
    include("conexaoBD.php");
    $sql = "SELECT COUNT(*) AS total FROM funcionario WHERE cargos_idcargos = 3;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $row['total'] ?? 0;
}

?>