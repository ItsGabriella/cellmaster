<?php

//Função para listar todos os produtos
function listaServico(){

    include("conexaoBD.php");
    $sql = "SELECT * FROM servico;";
            
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    $lista = '';

    //Validar se tem retorno do BD
    if (mysqli_num_rows($result) > 0) {        
        
        foreach ($result as $coluna) {

            //***Verificar os dados da consulta SQL

            $lista .= 
            '<tr>
                <td>'.$coluna["idservico"].'</td>
                <td>'.$coluna["nome_servico"].'</td>
                <td>'.$coluna["descricao_servico"].'</td>
                <td>'.$coluna["valor"].'</td>
                <td>'.$coluna["tempo"].'</td>

        
                <td>'.funcaoStatusServico($coluna["status"]).'</td>

                <td>
                    <button class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditar'.$coluna["idservico"].'">
                    <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalExcluir'.$coluna["idservico"].'">
                    <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="modalExcluir'.$coluna["idservico"].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">

                        <div class="modal-body text-center p-4">

                            <div class="mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width:80px;height:80px;">
                                    <i class="bi bi-trash text-danger fs-1"></i>
                                </div>
                            </div>

                            <h3 class="fw-bold">Excluir Serviço</h3>

                            <form method="POST" action="php/salvarServico.php?funcao=D&codigo='.$coluna["idservico"].'"enctype="multipart/form-data">

                                <p class="text-secondary">
                                    Tem certeza que deseja excluir o serviço
                                    <strong style="color: red;">'.$coluna["nome_servico"].'</strong>?
                                </p>

                                <p class="text-muted">
                                    Esta ação não poderá ser desfeita.
                                </p>

                                <div class="d-flex gap-2 justify-content-center mt-4">
                                    <button type="button" class="btn btn-outline-success px-4"
                                            data-bs-dismiss="modal">
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
            
            <div class="modal fade" id="modalEditar'.$coluna["idservico"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">

                    <div class="modal-content border-0 shadow-lg">

                        <!-- Cabeçalho -->
                        <div class="modal-header bg-success text-white">

                            <h5 class="modal-title">
                                <i class="fa-solid fa-box-archive me-2"></i>
                                Editar Serviço
                            </h5>

                            <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <form method="POST"
                                action="php/salvarServico.php?funcao=U&codigo='.$coluna["idservico"].'"
                                enctype="multipart/form-data">

                                <div class="row g-3">

                                    <!-- Nome -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Nome do Serviço
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            id="iServico" name="nServico"
                                            value="'.$coluna["nome_servico"].'">
                                            
                                    </div>



                                    <!-- Descrição -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Descrição
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            id="iDescricao" name="nDescricao"
                                            value="'.$coluna["descricao_servico"].'">
                                            
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Valor
                                        </label>

                                        <input type="number"
                                            class="form-control"
                                            step="0.01"
                                            id="iValor"
                                            name="nValor"
                                            value="'.$coluna["valor"].'">
                                            
                                    </div>


                                    
                                    <!-- Tempo estimado -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Tempo estimado
                                        </label>

                                        <input type="time"
                                            class="form-control"
                                            id="iTempo" name="nTempo"
                                            value="'.$coluna["tempo"].'">
                                            
                                    </div>


                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Status
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            id="iStatus" name="nStatus"
                                            value='.funcaoStatusServico($coluna["status"]).'>
                                            
                                    </div>

                                    

                                </div>

                                <hr>

                                <p class="text-secondary mb-1">
                                    Tem certeza que deseja editar o serviço
                                    <strong class="text-success">'.$coluna["nome_servico"].'</strong>?
                                </p>

                                <p class="text-muted">
                                    As alterações serão salvas no sistema.
                                </p>

                                <div class="d-flex justify-content-end gap-2 mt-4">

                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            data-bs-dismiss="modal">
                                        Cancelar
                                    </button>

                                    <button type="submit"
                                        class="btn btn-success">
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
    }
    
    return $lista;
}


//Próximo ID do produto
function proxIdServico(){

    $id = "";

    include("conexaoBD.php");
    $sql = "SELECT MAX(idservico) AS Maior FROM servico;";        
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    //Validar se tem retorno do BD
    if (mysqli_num_rows($result) > 0) {
                
        $array = array();
        
        while ($linha = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            array_push($array,$linha);
        }
        
        foreach ($array as $coluna) {            
            //***Verificar os dados da consulta SQL
            $id = $coluna["Maior"] + 1;
        }        
    } 

    return $id;
}


function funcaoStatusServico($status){
    if ($status == "a" or $status == "A"){
        $status = 'ativo';
    }else{
        $status = 'inativo';
    }
    return $status;
}

// function BuscarServico($busca)
// {
//     include("conexaoBD.php"); // ou sua conexão

//     $sql = "SELECT * FROM servico";

//     // Se digitou algo, adiciona o filtro
//     if (!empty($busca)) {
//         $sql .= " WHERE nome_servico LIKE ?";
//     }

//     $sql .= " ORDER BY nome_servico";

//     $stmt = mysqli_prepare($conn, $sql);

//     if (!empty($busca)) {
//         $busca = "%" . $busca . "%";
//         mysqli_stmt_bind_param($stmt, "s", $busca);
//     }

//     mysqli_stmt_execute($stmt);
//     $resultado = mysqli_stmt_get_result($stmt);

//     return $resultado;
// }

?>