<?php

function listaRelatorio(){

    include("conexaoBD.php");
    $sql = "SELECT * FROM relatorio;";
            
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    $lista = '';

    //Validar se tem retorno do BD
    if (mysqli_num_rows($result) > 0) {        
        
        foreach ($result as $coluna) {

            //***Verificar os dados da consulta SQL

            $lista .= 
            '<tr>
                <td>'.$coluna["idrelatorio"].'</td>
                <td>'.$coluna["relatorio"].'</td>
                <td>'.$coluna["tipo"].'</td>
                <td>'.$coluna["data"].'</td>
                <td>'.$coluna["responsavel"].'</td>
                <td>'.$coluna["exportado"].'</td>
                <td>'.$coluna["status"].'</td>


                <td>
                    <button class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditarRelatorio'.$coluna["idrelatorio"].'">
                    <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalExcluirRelatorio'.$coluna["idrelatorio"].'">
                    <i class="fa-solid fa-trash"></i>
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

                            <form method="POST" action="php/salvarRelatorio.php?funcao=D&codigo='.$coluna["idrelatorio"].'"enctype="multipart/form-data">

                                <p class="text-secondary">
                                    Tem certeza que deseja excluir o relatório
                                    <strong style="color: red;">'.$coluna["relatorio"].'</strong>?
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
            
            <div class="modal fade" id="modalEditarRelatorio'.$coluna["idrelatorio"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow">

                        <div class="modal-header border-0">
                            <h4 class="modal-title fw-bold text-success">
                                <i class="fa-solid fa-pen me-2"></i>Editar Relatório
                            </h4>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <form method="POST"
                                action="php/salvarRelatorio.php?funcao=U&codigo='.$coluna["idrelatorio"].'"
                                enctype="multipart/form-data">

                                <div class="row g-3">

                                    <!-- Nome -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Relatório
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            id="iRelatorio" name="nRelatorio"
                                            value="'.$coluna["relatorio"].'">
                                            
                                    </div>

                                    <!-- Categoria -->
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">
                                            Tipo
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            id="iTipo" name="nTipo"
                                            value="'.$coluna["relatorio"].'">
                                            
                                    </div>

                                    <!-- Quantidade -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Data
                                        </label>

                                        <input type="date"
                                            class="form-control"
                                            id="iDate" name="nDate"
                                            value="'.$coluna["data"].'">
                                            
                                    </div>

                                    <!-- Estoque mínimo -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Responsável
                                        </label>

                                        <input type="number"
                                            class="form-control"
                                            id="iResponsavel"
                                            name="nResponsavel"
                                            value="'.$coluna["responsavel"].'">
                                            
                                    </div>

                                    <!-- Valor -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Status
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            step="0.01"
                                            id="iStatus" name="nStatus"
                                            value="'.$coluna["status"].'">
                                            
                                    </div>

                                </div>

                                <hr>

                                <p class="text-secondary mb-1">
                                    Tem certeza que deseja editar o relatório
                                    <strong class="text-success">'.$coluna["relatorio"].'</strong>?
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







function TotalRelatorios()
{
    include("conexaoBD.php");

    $sql = "SELECT COUNT(*) AS total FROM relatorio";
    $resultado = mysqli_query($conn, $sql);

    $dados = mysqli_fetch_assoc($resultado);

    return $dados["total"];
}

function RelatoriosMes()
{
    include("conexaoBD.php");

    $sql = "SELECT COUNT(*) AS total
            FROM relatorio
            WHERE MONTH(data_relatorio) = MONTH(CURRENT_DATE())
            AND YEAR(data_relatorio) = YEAR(CURRENT_DATE())";

    $resultado = mysqli_query($conn, $sql);

    $dados = mysqli_fetch_assoc($resultado);

    return $dados["total"];
}

function RelatoriosPendentes()
{
    include("conexaoBD.php");

    $sql = "SELECT COUNT(*) AS total
            FROM relatorio
            WHERE status = 'Pendente'";

    $resultado = mysqli_query($conn, $sql);

    $dados = mysqli_fetch_assoc($resultado);

    return $dados["total"];
}

function RelatoriosExportados()
{
    include("conexaoBD.php");

    $sql = "SELECT COUNT(*) AS total
            FROM relatorio
            WHERE exportado = 'Sim'";

    $resultado = mysqli_query($conn, $sql);

    $dados = mysqli_fetch_assoc($resultado);

    return $dados["total"];
}

function ListarFuncionarios()
{
    include("conexao.php");

    $sql = "SELECT idfuncionario, nome_func
            FROM funcionario
            ORDER BY nome_func";

    $resultado = mysqli_query($conn, $sql);

    return $resultado;
}