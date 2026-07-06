<?php
include("conexao.php");

if (!isset($_GET['id'])) {
    header("Location: orcamentos.php?erro=1");
    exit;
}

$id = (int) $_GET['id'];

$clientes = $conn->query("SELECT idcliente, nome_clien FROM cliente ORDER BY nome_clien ASC");
$funcionarios = $conn->query("SELECT idfuncionario, nome_func FROM funcionario ORDER BY nome_func ASC");

$stmt = $conn->prepare("SELECT * FROM orcamento WHERE idorcamento = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: orcamentos.php?erro=1");
    exit;
}

$orcamento = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Orcamento</title>
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
            <li><a href="#" class="active">Orcamento</a></li>
            <li><a href="#">Ordem de Servico</a></li>
            <li><a href="#">Relatorio</a></li>
        </ul>
    </aside>

    <main class="content">
        <div class="topbar">
            <div>
                <h1>Editar Orcamento</h1>
                <p>Home > Orcamentos > Editar Orcamento</p>
            </div>
        </div>

        <div class="card">
            <form action="atualizar_orcamento.php" method="POST">
                <input type="hidden" name="idorcamento" value="<?php echo $orcamento['idorcamento']; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select name="cliente_idcliente" required>
                            <option value="">Selecione</option>
                            <?php while($c = $clientes->fetch_assoc()): ?>
                                <option value="<?php echo $c['idcliente']; ?>" <?php echo ($c['idcliente'] == $orcamento['cliente_idcliente']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c['nome_clien']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Funcionario responsavel</label>
                        <select name="funcionario_idfuncionario" required>
                            <option value="">Selecione</option>
                            <?php while($f = $funcionarios->fetch_assoc()): ?>
                                <option value="<?php echo $f['idfuncionario']; ?>" <?php echo ($f['idfuncionario'] == $orcamento['funcionario_idfuncionario']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($f['nome_func']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" value="<?php echo htmlspecialchars($orcamento['marca']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" value="<?php echo htmlspecialchars($orcamento['modelo']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>IMEI</label>
                        <input type="number" name="imei" value="<?php echo htmlspecialchars($orcamento['imei']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Valor total</label>
                        <input type="number" step="0.01" name="valor_total" value="<?php echo htmlspecialchars($orcamento['valor_total']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Data</label>
                        <input type="date" name="data_dia" value="<?php echo htmlspecialchars($orcamento['data_dia']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="Aguardando" <?php echo ($orcamento['status'] == 'Aguardando') ? 'selected' : ''; ?>>Aguardando</option>
                            <option value="Aprovado" <?php echo ($orcamento['status'] == 'Aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                            <option value="Reprovado" <?php echo ($orcamento['status'] == 'Reprovado') ? 'selected' : ''; ?>>Reprovado</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Defeito</label>
                        <textarea name="defeito"><?php echo htmlspecialchars($orcamento['defeito']); ?></textarea>
                    </div>

                    <div class="form-group full">
                        <label>Observacoes</label>
                        <textarea name="observacoes"><?php echo htmlspecialchars($orcamento['observacoes']); ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Atualizar Orcamento</button>
                    <a href="orcamentos.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
