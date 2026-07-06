<?php
include("conexao.php");

$clientes = $conn->query("SELECT idcliente, nome_clien FROM cliente ORDER BY nome_clien ASC");
$funcionarios = $conn->query("SELECT idfuncionario, nome_func FROM funcionario ORDER BY nome_func ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Orcamento</title>
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
                <h1>Novo Orcamento</h1>
                <p>Home > Orcamentos > Novo Orcamento</p>
            </div>
        </div>

        <div class="card">
            <form action="salvar_orcamento.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select name="cliente_idcliente" required>
                            <option value="">Selecione</option>
                            <?php while($c = $clientes->fetch_assoc()): ?>
                                <option value="<?php echo $c['idcliente']; ?>">
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
                                <option value="<?php echo $f['idfuncionario']; ?>">
                                    <?php echo htmlspecialchars($f['nome_func']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" required>
                    </div>

                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" required>
                    </div>

                    <div class="form-group">
                        <label>IMEI</label>
                        <input type="number" name="imei" required>
                    </div>

                    <div class="form-group">
                        <label>Valor total</label>
                        <input type="number" step="0.01" name="valor_total" required>
                    </div>

                    <div class="form-group">
                        <label>Data</label>
                        <input type="date" name="data_dia" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="Aguardando">Aguardando</option>
                            <option value="Aprovado">Aprovado</option>
                            <option value="Reprovado">Reprovado</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Defeito</label>
                        <textarea name="defeito"></textarea>
                    </div>

                    <div class="form-group full">
                        <label>Observacoes</label>
                        <textarea name="observacoes"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Salvar Orcamento</button>
                    <a href="orcamentos.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
