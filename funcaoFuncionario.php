<?php

//Função para listar todos os produtos
function listaFuncionario(){

    include("conexaoBD.php");
    $sql = "SELECT * FROM funcionario;";
            
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    $lista = '';

    if (mysqli_num_rows($result) > 0) {        
        
        foreach ($result as $coluna) {


            $lista .= 
            '<tr>
                <td>'.$coluna["idfuncionario"].'</td>
                <td>'.$coluna["nome_func"].'</td>
                <td>'.$coluna["cargos_idcargos"].'</td>
                <td>'.$coluna["tel_func"].'</td>
                <td>'.$coluna["email_func"].'</td>

                <td>
                    <button class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditar'.$coluna["idfuncionario"].'">
                    <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalExcluir'.$coluna["idfuncionario"].'">
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

                            <form method="POST" action="php/salvarFuncionario.php?funcao=D&IDFunc='.$coluna["idfuncionario"].'" enctype="multipart/form-data">

                                <p class="text-secondary">
                                    Tem certeza que deseja excluir o funcionário
                                    <strong style="color: red;">'.$coluna["nome_func"].'</strong>?
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
            
            <div class="modal fade" id="modalEditar'.$coluna["idfuncionario"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">

                    <div class="modal-content border-0 shadow-lg">

                        <div class="modal-header bg-success text-white">

                            <h5 class="modal-title">
                                <i class="fa-solid fa-box-archive me-2"></i>
                                Editar Funcionário
                            </h5>

                            <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <form method="POST" action="php/salvarFuncionario.php?funcao=U&IDFunc='.$coluna["idfuncionario"].'"
                                enctype="multipart/form-data">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Nome do Funcionário
                                        </label>

                                        <input type="text"
                                            class="form-control nome"
                                            id="ifuncionario" name="nFuncionario"
                                            value="'.$coluna["nome_func"].'">
                                            
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Cargo
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            id="iCargo" name="ncargo"
                                            value="'.$coluna["cargos_idcargos"].'">
                                            
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Telefone
                                        </label>

                                        <input type="text"
                                            class="form-control telefone"
                                            name="nTelefone"
                                            value="'.$coluna["tel_func"].'"
                                            maxlength="15">
                                            
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            E-mail
                                        </label>

                                        <input type="email"
                                            class="form-control"
                                            id="imail" name="nmail"
                                            value="'.$coluna["email_func"].'">
                                            
                                    </div>
                        
                                </div>

                                <hr>

                                <p class="text-secondary mb-1">
                                    Tem certeza que deseja editar o funcionário
                                    <strong class="text-success">'.$coluna["nome_func"].'</strong>?
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
function proxIdFuncionario(){

    $id = "";

    include("conexaoBD.php");
    $sql = "SELECT MAX(idfuncionario) AS Maior FROM funcionario;";        
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

function BuscarFuncionario($busca)
{
    include("conexaoBD.php");

    $busca = strtolower(trim($busca));
    if ($busca == "gerente") {
        $cargo = 1;
        } elseif ($busca == "técnico" || $busca == "tecnico") {
            $cargo = 2;
            } elseif ($busca == "atendente") {
                $cargo = 3;
                } else {
                    $cargo = $busca;
                }
                $sql = "SELECT * FROM funcionario
                WHERE nome_func LIKE '%$busca%'
                OR cargos_idcargos LIKE '%$cargo%'
                ORDER BY nome_func";

    $result = mysqli_query($conn, $sql);

    $lista = "";

    if(mysqli_num_rows($result) > 0)
    {
        foreach($result as $coluna)
        {
            $lista .= '
            <tr>
                <td>'.$coluna["idfuncionario"].'</td>
                <td>'.$coluna["nome_func"].'</td>
                <td>'.$coluna["cargos_idcargos"].'</td>
                <td>'.$coluna["tel_func"].'</td>
                <td>'.$coluna["email_func"].'</td>
                <td>
                    <button class="btn btn-success btn-sm">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>';
        }
    }

    mysqli_close($conn);

    return $lista;
}


?>