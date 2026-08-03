<?php
include("conexao.php");

// Buscar clientes e funcionários para os seletores
$clientes = $conn->query("SELECT idcliente, nome_clien FROM cliente ORDER BY nome_clien ASC");
$funcionarios = $conn->query("SELECT idfuncionario, nome_func FROM funcionario ORDER BY nome_func ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Orçamento - CellMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Novo Orçamento</h2>
        <a href="orcamentos.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a>
    </div>

    <div class="card p-4 shadow-sm border-0">
        <form action="salvar_orcamento.php" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Cliente <span class="text-danger">*</span></label>
                    <select name="cliente_idcliente" class="form-select" required>
                        <option value="">Selecione o Cliente...</option>
                        <?php if ($clientes): ?>
                            <?php while($c = $clientes->fetch_assoc()): ?>
                                <option value="<?php echo $c['idcliente']; ?>"><?php echo htmlspecialchars($c['nome_clien']); ?></option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Atendente / Técnico <span class="text-danger">*</span></label>
                    <select name="funcionario_idfuncionario" class="form-select" required>
                        <option value="">Selecione o Funcionário...</option>
                        <?php if ($funcionarios): ?>
                            <?php while($f = $funcionarios->fetch_assoc()): ?>
                                <option value="<?php echo $f['idfuncionario']; ?>"><?php echo htmlspecialchars($f['nome_func']); ?></option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Marca</label>
                    <input type="text" name="marca" class="form-control" placeholder="Ex: Apple, Samsung">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Modelo</label>
                    <input type="text" name="modelo" class="form-control" placeholder="Ex: iPhone 12, Galaxy S21">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">IMEI / Nº Série</label>
                    <input type="text" name="imei" class="form-control" placeholder="358293049128394">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Defeito Informado <span class="text-danger">*</span></label>
                    <textarea name="defeito" class="form-control" rows="3" required placeholder="Relato do cliente sobre o problema..."></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Observações</label>
                    <textarea name="observacoes" class="form-control" rows="2" placeholder="Avarias visíveis, riscos, estado da bateria..."></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Valor do Orçamento (R$)</label>
                    <input type="number" step="0.01" min="0" name="valor_total" class="form-control" value="0.00" required>
                </div>

                <!-- CAMPO DE DATA ALTERADO PARA DD/MM/AAAA -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Data do Orçamento</label>
                    <input type="text" 
                           id="data_dia" 
                           name="data_dia" 
                           class="form-control" 
                           placeholder="DD/MM/AAAA" 
                           maxlength="10" 
                           value="<?php echo date('d/m/Y'); ?>" 
                           onkeyup="mascaraData(this)" 
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Status do Orçamento</label>
                    <select name="status" class="form-select" required>
                        <option value="Aguardando" selected>Aguardando</option>
                        <option value="Aprovado">Aprovado</option>
                        <option value="Reprovado">Reprovado</option>
                    </select>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                    <a href="orcamentos.php" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Orçamento</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- MÁSCARA AUTOMÁTICA DA DATA -->
<script>
function mascaraData(input) {
    let v = input.value.replace(/\D/g, ""); // Remove caracteres não numéricos
    if (v.length > 8) v = v.substring(0, 8);
    
    if (v.length >= 5) {
        input.value = v.replace(/^(\d{2})(\d{2})(\d{0,4})/, "$1/$2/$3");
    } else if (v.length >= 3) {
        input.value = v.replace(/^(\d{2})(\d{0,2})/, "$1/$2");
    } else {
        input.value = v;
    }
}
</script>
</body>
</html>