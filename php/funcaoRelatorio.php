<?php

function listaRelatorio(){


    $funcionarios = ListarFuncionarios();

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
                <td>
                    <input type="checkbox" 
                    class="form-check-input"
                    name="relatorios[]"
                    value="'.$coluna["idrelatorio"].'">
                </td>
                <td>'.$coluna["idrelatorio"].'</td>
                <td>'.$coluna["nome_relatorio"].'</td>
                <td>'.$coluna["tipo"].'</td>
                <td>'.$coluna["data"].'</td>
                <td>'.$coluna["responsavel"].'</td>
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
                                    <strong style="color: red;">'.$coluna["nome_relatorio"].'</strong>?
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

                        <div class="modal-header bg-success text-white">

                            <h5 class="modal-title">
                                <i class="fa-solid fa-box-archive me-2"></i>
                                Editar Relatório
                            </h5>

                            <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal">
                            </button>

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
                                            value="'.$coluna["nome_relatorio"].'">
                                            
                                    </div>


                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">
                                            Tipo
                                        </label>

                                        <select id="iTipo" name="nTipo" class="form-select" value="'.$coluna["tipo"].'">
                                            <option>Clientes</option> 
                                            <option>Funcionários</option> 
                                            <option>Serviços</option> 
                                            <option>Estoque</option> 
                                            <option>Orçamento</option> 
                                            <option>Ordem de Serviço</option> 
                                        </select>
                                    </div>

                                    <!-- Quantidade -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Data
                                        </label>

                                        <input type="date"
                                            class="form-control"
                                            id="iData" name="nData"
                                            value="'.$coluna["data"].'">
                                            
                                    </div>

                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Responsável</label>

                                        <select name="nResponsavel" id="iResponsavel" class="form-select w-200" required>

                                            <option value="">Selecione um funcionário</option>';

                                            while($funcionario = mysqli_fetch_assoc($funcionarios)){
                                                $lista .= '
                                                    <option>
                                                        '.$funcionario["nome_func"].'
                                                    </option>';
                                            }

                                            $lista .= '

                                        </select>
                                    </div>

                                    <!-- Valor -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Status
                                    </label>

                                    <select id="iStatus" name="nStatus" class="form-select">
                                        <option>Pendente</option> <!--Relatório ainda não foi iniciado. -->
                                        <option>Em andamento</option> <!--Está sendo elaborado. -->
                                        <option>Concluído</option> <!--Foi finalizado. -->
                                        <option>Enviado</option> <!--Foi enviado ao cliente ou gerente. -->
                                        <option>Arquivado</option> <!--Não será mais alterado. -->
                                        <option>Cancelado</option> <!--Relatório foi cancelado. -->
                                    </select>
                                </div>

                                </div>

                                <hr>

                                <p class="text-secondary mb-1">
                                    Tem certeza que deseja editar o relatório
                                    <strong class="text-success">'.$coluna["nome_relatorio"].'</strong>?
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
            WHERE MONTH(data) = MONTH(CURRENT_DATE())
            AND YEAR(data) = YEAR(CURRENT_DATE())";

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




function BuscarRelatorio($buscaR)
{
    include("conexaoBD.php");

    $buscaS = mysqli_real_escape_string($conn, $buscaR);

    $sql = "SELECT *
            FROM relatorio
            WHERE nome_relatorio LIKE '%$buscaR%'
            ORDER BY nome_relatorio";

    $result = mysqli_query($conn, $sql);

    $lista = "";

    if(mysqli_num_rows($result) > 0)
    {
        foreach($result as $coluna)
        {
            $lista .= '
            <tr>
                <td>'.$coluna["idrelatorio"].'</td>
                <td>'.$coluna["nome_relatorio"].'</td>
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
            </tr>';
        }
    }

    mysqli_close($conn);

    return $lista;
}

function proxIdRelatorio(){

    $id = "";

    include("conexaoBD.php");
    $sql = "SELECT MAX(idrelatorio) AS Maior FROM relatorio;";        
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


function graficoRelatoriosMes(){

    include("conexaoBD.php");

    $sql = "
        SELECT MONTH(data) AS mes,
               COUNT(*) AS total
        FROM relatorio
        GROUP BY MONTH(data)
        ORDER BY MONTH(data)
    ";

    $resultado = mysqli_query($conn, $sql);

    $meses = [];
    $totais = [];

    $nomeMes = [
        1=>"Jan",2=>"Fev",3=>"Mar",4=>"Abr",
        5=>"Mai",6=>"Jun",7=>"Jul",8=>"Ago",
        9=>"Set",10=>"Out",11=>"Nov",12=>"Dez"
    ];

    while($linha = mysqli_fetch_assoc($resultado)){

        $meses[] = $nomeMes[$linha["mes"]];
        $totais[] = $linha["total"];

    }

    return [
        "meses" => $meses,
        "totais" => $totais
    ];
}


function graficoRelatoriosTipo(){

    include("conexaoBD.php");

    $sql = "
        SELECT tipo, COUNT(*) AS total
        FROM relatorio
        GROUP BY tipo
    ";

    $resultado = mysqli_query($conn, $sql);

    $tipos = [];
    $quantidades = [];

    while($linha = mysqli_fetch_assoc($resultado)){

        $tipos[] = $linha["tipo"];
        $quantidades[] = $linha["total"];

    }

    return [
        "tipos" => $tipos,
        "quantidades" => $quantidades
    ];
}



?>