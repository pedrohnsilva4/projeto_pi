<?php
include 'client_auth.php'; // exige login do cliente
include '_conn/connection.php';
 
// Dados do cliente
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
$stmt->bindValue(':id', $_SESSION['cliente_id']);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
 
if (!$cliente) {
    header('Location: logout-cliente.php');
    exit;
}
 
// Endereço do cliente (pode não existir ainda)
$stmt = $pdo->prepare("SELECT * FROM endereco WHERE cliente_id = :id LIMIT 1");
$stmt->bindValue(':id', $_SESSION['cliente_id']);
$stmt->execute();
$endereco = $stmt->fetch(PDO::FETCH_ASSOC);
 
// Histórico de pedidos
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE cliente_id = :id ORDER BY criado_em DESC");
$stmt->bindValue(':id', $_SESSION['cliente_id']);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
// Itens de cada pedido (para exibir no acordeão)
$itensPorPedido = [];
if ($pedidos) {
    $stmtItens = $pdo->prepare("SELECT * FROM pedido_itens WHERE pedido_id = :pedido_id");
    foreach ($pedidos as $p) {
        $stmtItens->bindValue(':pedido_id', $p['id']);
        $stmtItens->execute();
        $itensPorPedido[$p['id']] = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
    }
}
 
// Estatísticas rápidas
$totalPedidos = count($pedidos);
$totalGasto = array_sum(array_column($pedidos, 'total'));
 
$qtdCarrinho = isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'quantidade')) : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil | Iron Guard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f4f4f4; }
        h1, h2, h3, h4, .nickname, .navbar-brand { font-family: 'Oswald', sans-serif; text-transform: uppercase; }
 
        .profile-header {
            background: linear-gradient(135deg, #121212 0%, #2c2c2c 100%);
            color: white;
            padding: 60px 0;
            border-bottom: 5px solid #d9534f;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border: 5px solid #d9534f;
            border-radius: 50%;
            background-color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            color: white;
        }
 
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .stat-number { font-size: 2rem; font-weight: bold; color: #121212; }
        .stat-label { font-size: 0.85rem; color: #666; text-transform: uppercase; }
 
        .product-price, .text-danger-price { color: #d9534f; font-weight: bold; }
        .btn-danger { background-color: #d9534f; border: none; }
        .btn-danger:hover { background-color: #c9302c; }
 
        .status-badge { text-transform: uppercase; font-size: 0.75rem; }
    </style>
</head>
<body>
 
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="menu.php">Arte suave</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="menu.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="loja.php">Loja</a></li>
                <li class="nav-item"><a class="nav-link active" href="perfil.php">Meu Perfil</a></li>
                <li class="nav-item"><a class="btn btn-danger ms-lg-3" href="carrinho.php">Carrinho (<?= $qtdCarrinho ?>)</a></li>
                <li class="nav-item"><a class="btn btn-outline-light ms-2" href="logout-cliente.php">Sair</a></li>
            </ul>
        </div>
    </div>
</nav>
 
<!-- Cabeçalho do Perfil -->
<section class="profile-header">
    <div class="container">
        <div class="row align-items-center text-center text-md-start">
            <div class="col-md-2 mb-4 mb-md-0">
                <div class="profile-avatar mx-auto mx-md-0">
                    <?= strtoupper(substr($cliente['nome'], 0, 1)) ?>
                </div>
            </div>
            <div class="col-md-10">
                <h1 class="display-5 fw-bold mb-2"><?= htmlspecialchars($cliente['nome']) ?></h1>
                <p class="text-white-50 fs-5 mb-0"><?= htmlspecialchars($cliente['email']) ?></p>
                <?php if (!empty($cliente['telefone'])): ?>
                    <p class="text-white-50 mb-0"><?= htmlspecialchars($cliente['telefone']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
 
<div class="container my-5">
    <div class="row g-4">
 
        <!-- Coluna Esquerda: Dados + Estatísticas -->
        <div class="col-md-4">
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-number"><?= $totalPedidos ?></div>
                        <div class="stat-label">Pedidos</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-number text-danger-price" style="font-size:1.4rem;">
                            R$ <?= number_format($totalGasto, 2, ',', '.') ?>
                        </div>
                        <div class="stat-label">Total Gasto</div>
                    </div>
                </div>
            </div>
 
            <div class="card border-0 shadow-sm p-3 mb-3">
                <h5 class="mb-3">Dados Pessoais</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><strong>Nome:</strong> <?= htmlspecialchars($cliente['nome']) ?></li>
                    <li class="mb-2"><strong>E-mail:</strong> <?= htmlspecialchars($cliente['email']) ?></li>
                    <li class="mb-2"><strong>CPF:</strong> <?= htmlspecialchars($cliente['cpf'] ?: 'Não informado') ?></li>
                    <li class="mb-0"><strong>Telefone:</strong> <?= htmlspecialchars($cliente['telefone'] ?: 'Não informado') ?></li>
                </ul>
            </div>
 
            <div class="card border-0 shadow-sm p-3">
                <h5 class="mb-3">Endereço</h5>
                <?php if ($endereco): ?>
                    <p class="mb-0">
                        <?= htmlspecialchars($endereco['logradouro']) ?>, <?= htmlspecialchars($endereco['numero']) ?>
                        <?php if (!empty($endereco['complemento'])): ?> - <?= htmlspecialchars($endereco['complemento']) ?><?php endif; ?><br>
                        <?= htmlspecialchars($endereco['bairro']) ?> — <?= htmlspecialchars($endereco['cidade']) ?>/<?= htmlspecialchars($endereco['estado']) ?><br>
                        CEP: <?= htmlspecialchars($endereco['cep']) ?>
                    </p>
                <?php else: ?>
                    <p class="text-muted mb-0">Nenhum endereço cadastrado.</p>
                <?php endif; ?>
            </div>
        </div>
 
        <!-- Coluna Direita: Histórico de Pedidos -->
        <div class="col-md-8">
            <h3 class="mb-4">Meus Pedidos</h3>
 
            <?php if (empty($pedidos)): ?>
                <div class="card border-0 shadow-sm p-5 text-center">
                    <p class="lead text-muted mb-3">Você ainda não fez nenhum pedido.</p>
                    <a href="loja.php" class="btn btn-dark">Ir para a Loja</a>
                </div>
            <?php else: ?>
                <div class="accordion" id="pedidosAccordion">
                    <?php foreach ($pedidos as $i => $pedido): ?>
                        <?php
                        $statusCores = [
                            'pendente' => 'bg-warning text-dark',
                            'pago' => 'bg-success',
                            'enviado' => 'bg-primary',
                            'cancelado' => 'bg-secondary',
                        ];
                        $corStatus = $statusCores[$pedido['status']] ?? 'bg-secondary';
                        ?>
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#pedido<?= $pedido['id'] ?>">
                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                        <span>Pedido #<?= $pedido['id'] ?> — <?= date('d/m/Y', strtotime($pedido['criado_em'])) ?></span>
                                        <span class="badge status-badge <?= $corStatus ?> ms-2"><?= htmlspecialchars($pedido['status']) ?></span>
                                    </div>
                                </button>
                            </h2>
                            <div id="pedido<?= $pedido['id'] ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                                data-bs-parent="#pedidosAccordion">
                                <div class="accordion-body">
                                    <?php foreach ($itensPorPedido[$pedido['id']] as $item): ?>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span><?= htmlspecialchars($item['titulo_produto']) ?>
                                                <small class="text-muted">(Tam. <?= htmlspecialchars($item['tamanho']) ?> &times; <?= $item['quantidade'] ?>)</small>
                                            </span>
                                            <span class="product-price">R$ <?= number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.') ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="d-flex justify-content-between pt-3 fw-bold">
                                        <span>Total do Pedido</span>
                                        <span class="product-price">R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
 
    </div>
</div>
 
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; 2026 Iron Guard Academy. Oss!</p>
    </div>
</footer>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
