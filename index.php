<?php
session_start();

// Estrutura de memória para simular o banco de dados
if (!isset($_SESSION['produtos'])) $_SESSION['produtos'] = [];
if (!isset($_SESSION['enderecos'])) $_SESSION['enderecos'] = [];
if (!isset($_SESSION['transportadores'])) $_SESSION['transportadores'] = [];
if (!isset($_SESSION['fretes'])) $_SESSION['fretes'] = [];

// Processamento dos Formulários (Backend PHP)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'cadastrar_produto') {
        $_SESSION['produtos'][] = [
            'id' => count($_SESSION['produtos']) + 1,
            'nome' => filter_var($_POST['nome'], FILTER_SANITIZE_SPECIAL_CHARS),
            'peso' => floatval($_POST['peso']),
            'valor' => floatval($_POST['valor'])
        ];
    } elseif ($acao === 'cadastrar_endereco') {
        $_SESSION['enderecos'][] = [
            'id' => count($_SESSION['enderecos']) + 1,
            'logradouro' => filter_var($_POST['logradouro'], FILTER_SANITIZE_SPECIAL_CHARS),
            'cidade' => filter_var($_POST['cidade'], FILTER_SANITIZE_SPECIAL_CHARS),
            'cep' => filter_var($_POST['cep'], FILTER_SANITIZE_SPECIAL_CHARS)
        ];
    } elseif ($acao === 'cadastrar_transportador') {
        $_SESSION['transportadores'][] = [
            'id' => count($_SESSION['transportadores']) + 1,
            'nome' => filter_var($_POST['nome'], FILTER_SANITIZE_SPECIAL_CHARS),
            'documento' => filter_var($_POST['documento'], FILTER_SANITIZE_SPECIAL_CHARS),
            'veiculo' => filter_var($_POST['veiculo'], FILTER_SANITIZE_SPECIAL_CHARS)
        ];
    } elseif ($acao === 'gerar_frete') {
        $_SESSION['fretes'][] = [
            'id' => count($_SESSION['fretes']) + 1,
            'data' => date('Y-m-d'),
            'transportador_id' => intval($_POST['transportador_id']),
            'origem_id' => intval($_POST['origem_id']),
            'destino_id' => intval($_POST['destino_id']),
            'produtos' => $_POST['produtos_ids'] ?? []
        ];
    }
}

// Download do Relatório Diário em Formato CSV
if (isset($_GET['exportar_relatorio'])) {
    $hoje = date('Y-m-d');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=relatorio_fretes_'.$hoje.'.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID Frete', 'Data Emissao', 'ID Transportador', 'ID Origem', 'ID Destino', 'Qtd Produtos']);
    foreach ($_SESSION['fretes'] as $f) {
        if ($f['data'] === $hoje) {
            fputcsv($output, [$f['id'], $f['data'], $f['transportador_id'], $f['origem_id'], $f['destino_id'], count($f['produtos'])]);
        }
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Logístico - APO</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🚚 Gerenciador Logístico EAD</a>
        <a href="?exportar_relatorio=1" class="btn btn-success ms-auto fw-bold">
            📥 Baixar Relatório do Dia (CSV)
        </a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row g-4">
        
        <!-- Formulários de Cadastros -->
        <div class="col-lg-4">
            <!-- Cadastrar Produto -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-primary text-white fw-bold">1. Cadastrar Produto</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="acao" value="cadastrar_produto">
                        <div class="mb-2">
                            <input type="text" name="nome" class="form-control" placeholder="Nome do Produto" required>
                        </div>
                        <div class="mb-2">
                            <input type="number" step="0.01" name="peso" class="form-control" placeholder="Peso (kg)" required>
                        </div>
                        <div class="mb-2">
                            <input type="number" step="0.01" name="valor" class="form-control" placeholder="Valor (R$)" required>
                        </div>
                        <button class="btn btn-primary w-100">Salvar Produto</button>
                    </form>
                </div>
            </div>

            <!-- Cadastrar Endereço -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-primary text-white fw-bold">2. Cadastrar Endereço</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="acao" value="cadastrar_endereco">
                        <div class="mb-2">
                            <input type="text" name="logradouro" class="form-control" placeholder="Rua / Número" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="cidade" class="form-control" placeholder="Cidade" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="cep" class="form-control" placeholder="CEP" required>
                        </div>
                        <button class="btn btn-primary w-100">Salvar Endereço</button>
                    </form>
                </div>
            </div>

            <!-- Cadastrar Transportador -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">3. Cadastrar Transportador</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="acao" value="cadastrar_transportador">
                        <div class="mb-2">
                            <input type="text" name="nome" class="form-control" placeholder="Razão Social / Nome" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="documento" class="form-control" placeholder="CPF / CNPJ" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="veiculo" class="form-control" placeholder="Veículo / Placa" required>
                        </div>
                        <button class="btn btn-primary w-100">Salvar Transportador</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Módulo de Emissão de Frete (Relacionamento 1 : N) -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white fw-bold">Gerar Novo Frete (1 Transportador : N Produtos)</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="acao" value="gerar_frete">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Transportador (1):</label>
                                <select name="transportador_id" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($_SESSION['transportadores'] as $t): ?>
                                        <option value="<?= $t['id'] ?>"><?= $t['nome'] ?> (<?= $t['veiculo'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Origem:</label>
                                <select name="origem_id" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($_SESSION['enderecos'] as $e): ?>
                                        <option value="<?= $e['id'] ?>"><?= $e['logradouro'] ?> - <?= $e['cidade'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Destino:</label>
                                <select name="destino_id" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($_SESSION['enderecos'] as $e): ?>
                                        <option value="<?= $e['id'] ?>"><?= $e['logradouro'] ?> - <?= $e['cidade'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Produtos Selecionados (N) - (Segure Ctrl para vários):</label>
                            <select name="produtos_ids[]" class="form-select" multiple required style="height: 110px;">
                                <?php foreach ($_SESSION['produtos'] as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['nome'] ?> - R$ <?= number_format($p['valor'], 2, ',', '.') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button class="btn btn-success w-100 fw-bold">Emitir Frete Logístico</button>
                    </form>
                </div>
            </div>

            <!-- Tabela de Fretes Emitidos -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white fw-bold">Fretes Registrados no Sistema</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th># ID</th>
                                    <th>Data</th>
                                    <th>Transportador ID</th>
                                    <th>Origem / Destino</th>
                                    <th>Qtd. Produtos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($_SESSION['fretes'])): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Nenhum frete gerado até o momento.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($_SESSION['fretes'] as $f): ?>
                                        <tr>
                                            <td><strong>#<?= $f['id'] ?></strong></td>
                                            <td><?= date('d/m/Y', strtotime($f['data'])) ?></td>
                                            <td>Transportador #<?= $f['transportador_id'] ?></td>
                                            <td>Origem #<?= $f['origem_id'] ?> ➔ Destino #<?= $f['destino_id'] ?></td>
                                            <td><span class="badge bg-info text-dark"><?= count($f['produtos']) ?> produto(s)</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>