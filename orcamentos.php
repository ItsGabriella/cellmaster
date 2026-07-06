<?php
include("conexao.php");

$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : "";

$sql = "
    SELECT 
        o.idorcamento,
        o.cliente_idcliente,
        o.funcionario_idfuncionario,
        o.defeito,
        o.observacoes,
        o.marca,
        o.modelo,
        o.imei,
        o.aprovado,
        o.valor_total,
        o.data_dia,
        o.status,
        c.nome_clien,
        f.nome_func,
        os.idos
    FROM orcamento o
    LEFT JOIN cliente c ON o.cliente_idcliente = c.idcliente
    LEFT JOIN funcionario f ON o.funcionario_idfuncionario = f.idfuncionario
    LEFT JOIN ordem_servico os ON os.orcamento_idorcamento = o.idorcamento
";

if ($pesquisa != "") {
    $sql .= " 
        WHERE 
            c.nome_clien LIKE ? OR
            f.nome_func LIKE ? OR
            o.marca LIKE ? OR
            o.modelo LIKE ? OR
            o.status LIKE ? OR
            o.defeito LIKE ?
        ORDER BY o.idorcamento DESC
    ";
    $stmt = $conn->prepare($sql);
    $like = "%{$pesquisa}%";
    $stmt->bind_param("ssssss", $like, $like, $like, $like, $like, $like);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $sql .= " ORDER BY o.idorcamento DESC";
    $resultado = $conn->query($sql);
}

function classeStatus($status) {
    $status = strtolower(trim($status));

    if ($status == "aprovado") {
        return "status status-aprovado";
    } elseif ($status == "reprovado") {
        return "status status-reprovado";
    } else {
        return "status status-aguardando";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Orcamentos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="user">
            <h3>Fulano de tal</h3>
            <span>Gerente</span>
        </div>

        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Funcionarios</a></li>
            <li><a href="#">Clientes</a></li>
            <li><a href="#">Estoque</a></li>
            <li><a href="#">Servicos</a></li>
            <li><a href="orcamentos.php" class="active">Orcamento</a></li>
            <li><a href="ordens_servico.php">Ordem de Servico</a></li>
            <li><a href="#">Relatorio</a></li>
        </ul>
    </aside>

    <main class="content">
        <div class="topbar">
            <div>
                <h1>Orcamentos</h1>
                <p>Home > Orcamentos</p>
            </div>

            <a href="novo_orcamento.php" class="btn btn-primary">+ Novo Orcamento</a>
        </div>

        <div class="card">
            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success">
                    Operacao realizada com sucesso.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['erro'])): ?>
                <div class="alert alert-error">
                    Ocorreu um erro ao processar a operacao.
                </div>
            <?php endif; ?>

            <form method="GET" class="filtros">
                <input type="text" name="pesquisa" placeholder="Pesquisar por cliente, marca, modelo, status..." value="<?php echo htmlspecialchars($pesquisa); ?>">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <a href="orcamentos.php" class="btn btn-secondary">Limpar</a>
            </form>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Funcionario</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>IMEI</th>
                            <th>Defeito</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['idorcamento']; ?></td>
                                    <td><?php echo htmlspecialchars($row['nome_clien'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nome_func'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['marca']); ?></td>
                                    <td><?php echo htmlspecialchars($row['modelo']); ?></td>
                                    <td><?php echo htmlspecialchars($row['imei']); ?></td>
                                    <td><?php echo htmlspecialchars($row['defeito']); ?></td>
                                    <td>R$ <?php echo number_format((float)$row['valor_total'], 2, ',', '.'); ?></td>
                                    <td>
                                        <?php 
                                        echo !empty($row['data_dia']) 
                                            ? date('d/m/Y', strtotime($row['data_dia'])) 
                                            : '-'; 
                                        ?>
                                    </td>
                                    <td>
                                        <span class="<?php echo classeStatus($row['status']); ?>">
                                            <?php echo htmlspecialchars($row['status'] ?: 'Aguardando'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="acoes">
                                            <a href="editar_orcamento.php?id=<?php echo $row['idorcamento']; ?>" class="btn btn-warning">Editar</a>

                                            <a href="excluir_orcamento.php?id=<?php echo $row['idorcamento']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este orcamento?');">Excluir</a>

                                            <?php if (strtolower(trim($row['status'])) == 'aprovado'): ?>
                                                <?php if (!empty($row['idos'])): ?>
                                                    <a href="visualizar_os.php?id=<?php echo $row['idos']; ?>" class="btn btn-info">Ver OS</a>
                                                <?php else: ?>
                                                    <a href="gerar_os.php?id=<?php echo $row['idorcamento']; ?>" class="btn btn-success">Gerar OS</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">Nenhum orcamento encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
