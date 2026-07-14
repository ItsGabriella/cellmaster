<?php

//Função para listar todos os produtos
function listaCliente(){

    include("conexaoBD.php");
    $sql = "SELECT * FROM cliente;";
            
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    $lista = '';

    if (mysqli_num_rows($result) > 0) {        
        
        foreach ($result as $coluna) {


            $lista .= 
            '<tr>
                <td>'.$coluna["idcliente"].'</td>
                <td>'.$coluna["nome_clien"].'</td>
                <td>'.$coluna["endereco_clien"].'</td>
                <td>'.$coluna["cpf_clien"].'</td>
                <td>'.$coluna["tel_clien"].'</td>
                <td>'.$coluna["email_clien"].'</td>

                <td>
                    <button class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditar'.$coluna["idcliente"].'">
                    <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalExcluir'.$coluna["idcliente"].'">
                    <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="modalExcluir'.$coluna["idcliente"].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">

                        <div class="modal-body text-center p-4">

                            <div class="mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width:80px;height:80px;">
                                    <i class="bi bi-trash text-danger fs-1"></i>
                                </div>
                            </div>

                            <h3 class="fw-bold">Excluir Cliente</h3>

                            <form method="POST" action="php/salvarCliente.php?funcao=D&IDClien='.$coluna["idcliente"].'" enctype="multipart/form-data">

                                <p class="text-secondary">
                                    Tem certeza que deseja excluir o cliente
                                    <strong style="color: red;">'.$coluna["nome_clien"].'</strong>?
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
            
            <div class="modal fade" id="modalEditar'.$coluna["idcliente"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">

                    <div class="modal-content border-0 shadow-lg">

                        <div class="modal-header bg-success text-white">

                            <h5 class="modal-title">
                                <i class="fa-solid fa-box-archive me-2"></i>
                                Editar Cliente
                            </h5>

                            <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <form method="POST" action="php/salvarCliente.php?funcao=U&IDClien='.$coluna["idcliente"].'"
                                enctype="multipart/form-data">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Nome do Cliente
                                        </label>

                                        <input type="text"
                                            class="form-control nome"
                                            id="iCliente" name="nCliente"
                                            value="'.$coluna["nome_clien"].'">
                                            
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Endereço
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            id="iEndereco" name="nEndereco"
                                            value="'.$coluna["endereco_clien"].'">
                                            
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            CPF
                                        </label>

                                        <input type="text"
                                            class="form-control cpf"
                                            id="iCPF" name="nCPF"
                                            value="'.$coluna["cpf_clien"].'">
                                            
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Telefone
                                        </label>

                                        <input type="text"
                                            class="form-control telefone"
                                            name="nTelefone"
                                            value="'.$coluna["tel_clien"].'"
                                            maxlength="15">
                                            
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            E-mail
                                        </label>

                                        <input type="email"
                                            class="form-control"
                                            id="imail" name="nmail"
                                            value="'.$coluna["email_clien"].'">
                                            
                                    </div>
                        
                                </div>

                                <hr>

                                <p class="text-secondary mb-1">
                                    Tem certeza que deseja editar o cliente
                                    <strong class="text-success">'.$coluna["nome_clien"].'</strong>?
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
function proxIdCliente(){

    $id = "";

    include("conexaoBD.php");
    $sql = "SELECT MAX(idcliente) AS Maior FROM cliente;";        
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

function BuscarCliente($busca)
{
    include("conexaoBD.php");

    $busca = mysqli_real_escape_string($conn, $busca);

    $sql = "SELECT *
        FROM cliente
        WHERE nome_clien LIKE '%$busca%'
           OR cpf_clien LIKE '%$busca%'
        ORDER BY nome_clien";

    $result = mysqli_query($conn, $sql);

    $lista = "";

    if(mysqli_num_rows($result) > 0)
    {
        foreach($result as $coluna)
        {
            $lista .= '
            <tr>
                <td>'.$coluna["idcliente"].'</td>
                <td>'.$coluna["nome_clien"].'</td>
                <td>'.$coluna["endereco_clien"].'</td>
                <td>'.$coluna["cpf_clien"].'</td>
                <td>'.$coluna["tel_clien"].'</td>
                <td>'.$coluna["email_clien"].'</td>
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