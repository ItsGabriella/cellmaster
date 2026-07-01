<?php
//Função para listar todos os produtos
function listaProduto(){

    include("conexaoBD.php");
    $sql = "SELECT * FROM peca;";
            
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    $lista = '';

    //Validar se tem retorno do BD
    if (mysqli_num_rows($result) > 0) {        
        
        foreach ($result as $coluna) {

            //***Verificar os dados da consulta SQL

            $lista .= 
            '<tr>
                <td>'.$coluna["idpeca"].'</td>
                <td>'.$coluna["nome_peca"].'</td>
                <td>'.$coluna["categoria"].'</td>
                <td>'.$coluna["qtdade_peca"].'</td>
                <td>'.$coluna["estoque_min"].'</td>
                <td>'.$coluna["valor_unit"].'</td>

                <td>'.funcaoStatus($coluna["qtdade_peca"],$coluna["estoque_min"]).'</td>

                <td>
                    <button class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditar'.$coluna["idpeca"].'">
                    <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalExcluir'.$coluna["idpeca"].'">
                    <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="modalExcluir'.$coluna["idpeca"].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">

                        <div class="modal-body text-center p-4">

                            <div class="mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width:80px;height:80px;">
                                    <i class="bi bi-trash text-danger fs-1"></i>
                                </div>
                            </div>

                            <h3 class="fw-bold">Excluir Produto</h3>

                            <p class="text-secondary">
                                Tem certeza que deseja excluir o produto
                                <strong style="color: red;">'.$coluna["nome_peca"].'</strong>?
                            </p>

                            <p class="text-muted">
                                Esta ação não poderá ser desfeita.
                            </p>

                            <div class="d-flex gap-2 justify-content-center mt-4">
                                <button class="btn btn-outline-success px-4"
                                        data-bs-dismiss="modal">
                                    Cancelar
                                </button>

                                <button class="btn btn-danger px-4">
                                    Excluir
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="modalEditar'.$coluna["idpeca"].'" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow">

                        <div class="modal-header border-0">
                            <h4 class="modal-title fw-bold text-success">
                                <i class="fa-solid fa-pen me-2"></i>Editar Produto
                            </h4>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <form method="POST"
                                action="php/salvarEstoque.php?funcao=U&codigo='.$coluna["idpeca"].'"
                                enctype="multipart/form-data">

                                <div class="row g-3">

                                    <!-- Nome -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Nome do Produto
                                        </label>

                                        <input type="text"
                                            name="nome_peca"
                                            class="form-control"
                                            id="iPeca" name="nPeca"
                                            value="'.$coluna["nome_peca"].'">
                                            
                                    </div>

                                    <!-- Categoria -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Categoria
                                        </label>

                                        <select name="categoria" class="form-select">
                                            <option>Tela</option>
                                            <option selected>Botões</option>
                                            <option>Bateria</option>
                                            <option>Conector</option>
                                            <option>Outros</option>
                                        </select>
                                    </div>

                                    <!-- Quantidade -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Quantidade
                                        </label>

                                        <input type="number"
                                            name="qtdade_peca"
                                            class="form-control"
                                            id="iQuantidade" name="nQuantidade"
                                            value="'.$coluna["qtdade_peca"].'">
                                            
                                    </div>

                                    <!-- Estoque mínimo -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Estoque Mínimo
                                        </label>

                                        <input type="number"
                                            name="estoque_min"
                                            class="form-control"
                                            id="iEstoqueMin"
                                            name="nEstoqueMin"
                                            value="'.$coluna["estoque_min"].'">
                                            
                                    </div>

                                    <!-- Valor -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Valor Unitário
                                        </label>

                                        <input type="number"
                                            name="valor_unit"
                                            class="form-control"
                                            step="0.01"
                                            id="iValor" name="nValor"
                                            value="'.$coluna["valor_unit"].'">
                                            
                                    </div>

                                </div>

                                <hr>

                                <p class="text-secondary mb-1">
                                    Tem certeza que deseja editar o produto
                                    <strong class="text-success">'.$coluna["nome_peca"].'</strong>?
                                </p>

                                <p class="text-muted">
                                    As alterações serão salvas no estoque.
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

function funcaoStatus($qtd,$qtdmin){
    $status = '';
    if ($qtd > $qtdmin){
        $status = '<span class="badge text-bg-success">Em estoque</span>';
    }else{
        $status = '<span class="badge text-bg-danger">Baixo Estoque</span>';
    }
    return $status;
}

//Próximo ID do produto
function proxIdProduto(){

    $id = "";

    include("conexao.php");
    $sql = "SELECT MAX(idProduto) AS Maior FROM produto;";        
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

//Função para buscar a categoria de um produto
function categoriaProduto($id){

    $resp = "";

    include("conexao.php");
    $sql = "SELECT idTipoUsuario FROM usuarios WHERE idUsuario = $id;";        
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
            if($coluna["idTipoUsuario"] == 1){
                //Admin
                $resp = '<option value="1">Admin</option>'
                        .'<option value="2">Empresa</option>'
                        .'<option value="3">Comum</option>';
            }else if($coluna["idTipoUsuario"] == 2){
                //Empresa
                $resp = '<option value="2">Empresa</option>'
                        .'<option value="1">Admin</option>'
                        .'<option value="3">Comum</option>';
            }else{
                //Comum
                $resp = '<option value="3">Comum</option>'
                        .'<option value="1">Admin</option>'
                        .'<option value="2">Empresa</option>';
            }
        }        
    } 

    return $resp;
}

//Função para buscar a descrição do produto
function descrProduto($id){

    $resp = "";

    include("conexao.php");
    $sql = "SELECT Nome FROM usuarios WHERE idUsuario = $id;";        
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
            $resp = $coluna["Nome"];
        }        
    } 

    return $resp;
}

//Função para quantidade de produtos
function qtdProduto(){

    $qtd = 0;

    include("conexao.php");
    $sql = "SELECT COUNT(*) AS Qtd FROM produto;";        
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    //Validar se tem retorno do BD
    if (mysqli_num_rows($result) > 0) {
        
        foreach ($result as $coluna) {            
            //***Verificar os dados da consulta SQL
            $qtd = $coluna['Qtd'];
        }        
    } 

    return $qtd;

}

//Função para quantidade de produtos sem estoque
function qtdProdutoSemEstoque(){

    $qtd = 0;

    include("conexao.php");
    $sql = "SELECT COUNT(*) AS Qtd FROM produto WHERE Quantidade = 0;";        
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    //Validar se tem retorno do BD
    if (mysqli_num_rows($result) > 0) {
        
        foreach ($result as $coluna) {            
            //***Verificar os dados da consulta SQL
            $qtd = $coluna['Qtd'];
        }        
    } 

    return $qtd;

}

//Labels do gráfico de barras
function labelTresProdutos(){

    $lista = '';
    $cont  = 0;

    include("conexao.php");
    $sql = "SELECT * "
            ." FROM produto "
            ." ORDER BY Quantidade DESC, Descricao "
            ." LIMIT 3;";        
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    //Validar se tem retorno do BD
    if (mysqli_num_rows($result) > 0) {
        
        foreach ($result as $coluna) {            
            //***Verificar os dados da consulta SQL
            if($cont == 0){
                $lista .= "'".$coluna['Descricao']."'";
            }else{
                $lista .= ",'".$coluna['Descricao']."'";
            }

            $cont++;
        }        
    } 

    return $lista;

}

//Quantidades do gráfico de barras
function qtdTresProdutos(){

    $lista = '';
    $cont  = 0;

    include("conexao.php");
    $sql = "SELECT * "
            ." FROM produto "
            ." ORDER BY Quantidade DESC, Descricao "
            ." LIMIT 3;";        
    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    //Validar se tem retorno do BD
    if (mysqli_num_rows($result) > 0) {
        
        foreach ($result as $coluna) {            
            //***Verificar os dados da consulta SQL
            if($cont == 0){
                $lista .= $coluna['Quantidade'];
            }else{
                $lista .= ",".$coluna['Quantidade'];
            }

            $cont++;
        }        
    } 

    return $lista;

}

?>