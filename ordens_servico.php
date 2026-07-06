<?php
include("conexao.php");

$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : "";

$sql = "
    SELECT 
        os.idos,
        os.data_abertura,
        os.status_os,
        os.laudo_tecnico,
        os.observacoes_os,
        os.valor_final,
        o.marca,
        o.modelo,
        o.imei,
        c.nome_clien,
        f.nome_func
    FROM ordem_servico os
    LEFT JOIN orcamento o ON os.orcamento_idorcamento = o.idorcamento
    LEFT JOIN cliente c ON os.cliente_idcliente = c.idcliente
    LEFT JOIN funcionario f ON os.funcionario_idfuncionario = f.idfuncionario
";

if ($pesquisa != "") {
    $sql .= "
        WHERE
            c.nome_clien LIKE ? OR
            f.nome_func LIKE ? OR
            o.marca LIKE ? OR
            o.modelo LIKE ? OR
            os.status_os LIKE ? OR
            os.laudo_tecnico LIKE ?
        ORDER BY os.idos DESC
    ";
    $stmt = $conn->prepare($sql);
    $like = "%{$pesquisa}%";
    $stmt->bind_param("ssssss", $like, $like, $like, $like, $like, $like);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $sql .= " ORDER BY os.idos DESC";
    $resultado = $conn->query($sql);
}

function classeStatusOS($status) {
    $status = strtolower(trim($status));

    if ($status == "finalizada") {
        return "status status-aprovado";
    } elseif ($status == "cancelada") {
        return "status status-reprovado";
    } elseif ($status == "em andamento") {
        return "status status-info";
    } else {
        return "status status-aguardando";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ordens de Servico</title>
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
            <li><a href="orcamentos.php">Orcamento</a></li>
            <li><a href="ordens_servico.php" class="active">Ordem de Servico</a></li>
            <li><a href="#">Relatorio</a></li>
        </ul>
    </aside>

    <main class="content">
        <div class="topbar">
            <div>
                <h1>Ordens de Servico</h1>
                <p>Home > Ordem de Servico</p>
            </div>
        </div>

        <div class="card">
            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success">
                    Operacao realizada com sucesso.
                </div>
            <?php endif; ?>

            <form method="GET" class="filtros">
                <input type="text" name="pesquisa" placeholder="Pesquisar por cliente, tecnico, marca, modelo..." value="<?php echo htmlspecialchars($pesquisa); ?>">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <a href="ordens_servico.php" class="btn btn-secondary">Limpar</a>
            </form>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>OS</th>
                            <th>Cliente</th>
                            <th>Tecnico</th>
                            <th>Aparelho</th>
                            <th>IMEI</th>
                            <th>Laudo</th>
                            <th>Valor Final</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['idos']; ?></td>
                                    <td><?php echo htmlspecialchars($row['nome_clien'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nome_func'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars(($row['marca'] ?? '-') . ' ' . ($row['modelo'] ?? '')); ?></td>
                                    <td><?php echo htmlspecialchars($row['imei'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['laudo_tecnico'] ?? '-'); ?></td>
                                    <td>R$ <?php echo number_format((float)$row['valor_final'], 2, ',', '.'); ?></td>
                                    <td>
                                        <?php 
                                        echo !empty($row['data_abertura']) 
                                            ? date('d/m/Y', strtotime($row['data_abertura'])) 
                                            : '-'; 
                                        ?>
                                    </td>
                                    <td>
                                        <span class="<?php echo classeStatusOS($row['status_os']); ?>">
                                            <?php echo htmlspecialchars($row['status_os'] ?: 'Aberta'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="acoes">
                                            <a href="visualizar_os.php?id=<?php echo $row['idos']; ?>" class="btn btn-info">Visualizar</a>
                                            <a href="editar_os.php?id=<?php echo $row['idos']; ?>" class="btn btn-warning">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10">Nenhuma ordem de servico encontrada.</td>
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
