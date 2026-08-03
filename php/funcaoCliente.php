<?php

//Função para listar todos os produtos
function listaCliente(){
    include("conexaoBD.php");
    $sql = "SELECT * FROM cliente;";
            
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    $lista = '';

    if ($result && mysqli_num_rows($result) > 0) {        
        foreach ($result as $coluna) {

            $lista .= 
            '<tr>
                <td>'.$coluna["idcliente"].'</td>
                <td>'.htmlspecialchars($coluna["nome_clien"]).'</td>
                <td>'.htmlspecialchars($coluna["endereco_clien"]).'</td>
                <td>'.htmlspecialchars($coluna["cpf_clien"]).'</td>
                <td>'.htmlspecialchars($coluna["tel_clien"]).'</td>
                <td>'.htmlspecialchars($coluna["email_clien"]).'</td>

                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar'.$coluna["idcliente"].'">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalExcluir'.$coluna["idcliente"].'">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="modalEditar'.$coluna["idcliente"].'" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fa-solid fa-user-pen me-2"></i>Editar Cliente #'.$coluna["idcliente"].'</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="php/salvarCliente.php?IDClien='.$coluna["idcliente"].'&funcao=U" method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nome</label>
                                    <input type="text" class="form-control" name="nCliente" value="'.htmlspecialchars($coluna["nome_clien"]).'" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">E-mail</label>
                                    <input type="email" class="form-control" name="nmail" value="'.htmlspecialchars($coluna["email_clien"]).'" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">CPF</label>
                                    <input type="text" class="form-control cpf" name="nCPF" value="'.htmlspecialchars($coluna["cpf_clien"]).'" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Telefone</label>
                                    <input type="text" class="form-control telefone" name="nTelefone" value="'.htmlspecialchars($coluna["tel_clien"]).'" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Endereço</label>
                                    <input type="text" class="form-control" name="nEndereco" value="'.htmlspecialchars($coluna["endereco_clien"]).'" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nova Senha</label>
                                    <input type="password" class="form-control" name="nSenha" placeholder="Deixe em branco para manter">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Salvar Alterações</button>
                        </div>
                    </form>
                    </div>
                </div>
                </div>

            <div class="modal fade" id="modalExcluir'.$coluna["idcliente"].'" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Confirmar Exclusão</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body text-center py-4">
                    <i class="fa-solid fa-trash text-danger fa-3x mb-3"></i>
                    <p class="fs-5 fw-semibold mb-1">Tem certeza que deseja excluir?</p>
                    <p class="text-muted small mb-0">Você está prestes a remover o cliente <strong>'.htmlspecialchars($coluna["nome_clien"]).'</strong>. Esta ação não poderá ser desfeita.</p>
                  </div>
                  <div class="modal-footer bg-light border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <a href="php/salvarCliente.php?IDClien='.$coluna["idcliente"].'&funcao=D" class="btn btn-danger px-4 fw-semibold">Sim, Excluir</a>
                  </div>
                </div>
              </div>
            </div>';
        }
    } else {
        $lista = '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum cliente encontrado.</td></tr>';
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
           OR email_clien LIKE '%$busca%'
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



// 1. Função para contar o total geral de clientes
function TotalClientes() {
    include("conexaoBD.php");

    $sql = "SELECT COUNT(*) AS total FROM cliente";
    $result = mysqli_query($conn, $sql);

    $total = 0;
    if ($result && $linha = mysqli_fetch_assoc($result)) {
        $total = $linha['total'];
    }

    mysqli_close($conn);
    return $total;
}

// 2. Função para contar clientes cadastrados no mês atual
// Nota: Requer que a tabela 'cliente' tenha uma coluna de data (ex: data_cadastro ou data_cad)
function NovosClientesMês() {
    include("conexaoBD.php");

    // Ajuste o nome do campo 'data_cadastro' conforme o nome da coluna no seu Banco de Dados
    $sql = "SELECT COUNT(*) AS total 
            FROM cliente 
            WHERE MONTH(data_cadastro) = MONTH(CURRENT_DATE()) 
              AND YEAR(data_cadastro) = YEAR(CURRENT_DATE())";

    $result = mysqli_query($conn, $sql);

    $total = 0;
    if ($result && $linha = mysqli_fetch_assoc($result)) {
        $total = $linha['total'];
    }

    mysqli_close($conn);
    return $total;
}

// 3. Função para contar clientes com ordens de serviço / vendas ativas/recentes
function ClientesAtivos() {
    include("conexaoBD.php");

    // Exemplo: Conta clientes distintos que possuem ordens de serviço ou compras
    // Ajuste o nome da tabela 'ordem_servico' ou 'vendas' e a chave 'idcliente' conforme o seu BD
    $sql = "SELECT COUNT(DISTINCT idcliente) AS total FROM ordem_servico";

    $result = mysqli_query($conn, $sql);

    $total = 0;
    if ($result && $linha = mysqli_fetch_assoc($result)) {
        $total = $linha['total'];
    }

    mysqli_close($conn);
    return $total;
}


?>