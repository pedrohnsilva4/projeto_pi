<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '_conn/connection.php';
include 'functions.php';
 
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}
 
$erro = null;
$sucesso = null;
 
// ===================== AÇÕES =====================
 
// 1) Adicionar produto ao carrinho (formulário enviado a partir de single.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto_id']) && !isset($_POST['acao'])) {
    $produtoId = (int) $_POST['produto_id'];
    $tamanho   = isset($_POST['tamanho']) ? trim($_POST['tamanho']) : 'ÚNICO';
    $qtd       = isset($_POST['quantidade']) ? (int) $_POST['quantidade'] : 1;
 
    $stmt = $pdo->prepare("SELECT id FROM produtos WHERE id = ?");
    $stmt->execute([$produtoId]);
 
    if ($stmt->fetch()) {
        addCar($produtoId, $tamanho, $qtd);
        $sucesso = "Produto adicionado ao carrinho!";
    } else {
        $erro = "Produto não encontrado.";
    }
}
 
// 2) Atualizar quantidade de um item existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
    $chave = $_POST['chave'] ?? '';
    $qtd   = (int) ($_POST['quantidade'] ?? 1);
    atualizarQtdCar($chave, $qtd);
    header('Location: carrinho.php');
    exit;
}
 
// 3) Remover item do carrinho
if (isset($_GET['remover'])) {
    removeItemCar($_GET['remover']);
    header('Location: carrinho.php');
    exit;
}
 
// 4) Esvaziar carrinho
if (isset($_GET['esvaziar'])) {
    $_SESSION['carrinho'] = [];
    header('Location: carrinho.php');
    exit;
}
 
// ===================== MONTA DADOS PARA EXIBIÇÃO =====================
 
$itensCarrinho = [];
$totalCarrinho = 0;
 
if (!empty($_SESSION['carrinho'])) {
    $ids = array_values(array_unique(array_column($_SESSION['carrinho'], 'produto_id')));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
 
    $produtosDb = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $produtosDb[$p['id']] = $p;
    }
 
    foreach ($_SESSION['carrinho'] as $chave => $item) {
        if (!isset($produtosDb[$item['produto_id']])) {
            continue;
        }
        $p = $produtosDb[$item['produto_id']];
        $subtotal = $p['preco'] * $item['quantidade'];
        $totalCarrinho += $subtotal;
 
        $itensCarrinho[] = [
            'chave'      => $chave,
            'produto'    => $p,
            'tamanho'    => $item['tamanho'],
            'quantidade' => $item['quantidade'],
            'subtotal'   => $subtotal,
        ];
    }
}
 
$qtdCarrinho = totalItensCar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho | Iron Guard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f4f4f4; }
        h1, h2, h3, h4, .navbar-brand { font-family: 'Oswald', sans-serif; text-transform: uppercase; }
 
        .cart-header {
            background: linear-gradient(135deg, #121212 0%, #2c2c2c 100%);
            color: white;
            padding: 40px 0;
            border-bottom: 5px solid #d9534f;
        }
 
        .cart-item-img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            background: #fff;
            border-radius: 6px;
            padding: 6px;
            border: 1px solid #eee;
        }
 
        .product-price { color: #d9534f; font-weight: bold; }
        .btn-danger { background-color: #d9534f; border: none; }
        .btn-danger:hover { background-color: #c9302c; }
 
        .qtd-input { width: 70px; }
 
        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 90px;
        }
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
                <li class="nav-item"><a class="nav-link" href="perfil.php">Meu Perfil</a></li>
                <li class="nav-item"><a class="btn btn-danger ms-lg-3 active" href="carrinho.php">Carrinho (<?= $qtdCarrinho ?>)</a></li>
            </ul>
        </div>
    </div>
</nav>
 
<section class="cart-header">
    <div class="container">
        <h1 class="display-5 fw-bold mb-1">Meu Carrinho</h1>
        <p class="text-white-50 mb-0">Confira seus itens antes de finalizar o pedido.</p>
    </div>
</section>
 
<div class="container my-5">
 
    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
 
    <?php if ($sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>
 
    <?php if (empty($itensCarrinho)): ?>
 
        <div class="card border-0 shadow-sm p-5 text-center">
            <p class="lead text-muted mb-3">Seu carrinho está vazio.</p>
            <a href="loja.php" class="btn btn-dark">Ir para a Loja</a>
        </div>
 
    <?php else: ?>
 
        <div class="row g-4">
 
            <!-- Itens do carrinho -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th>Tamanho</th>
                                    <th>Qtd.</th>
                                    <th class="text-end">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($itensCarrinho as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="images/products/<?= htmlspecialchars($item['produto']['imagem']) ?>"
                                                     class="cart-item-img" alt="<?= htmlspecialchars($item['produto']['titulo']) ?>">
                                                <div>
                                                    <div class="fw-bold"><?= htmlspecialchars($item['produto']['titulo']) ?></div>
                                                    <small class="text-muted">R$ <?= number_format($item['produto']['preco'], 2, ',', '.') ?> / un.</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($item['tamanho']) ?></span></td>
                                        <td>
                                            <form action="carrinho.php" method="POST" class="d-flex align-items-center gap-2">
                                                <input type="hidden" name="acao" value="atualizar">
                                                <input type="hidden" name="chave" value="<?= htmlspecialchars($item['chave']) ?>">
                                                <input type="number" name="quantidade" min="1" value="<?= $item['quantidade'] ?>" class="form-control form-control-sm qtd-input">
                                                <button type="submit" class="btn btn-sm btn-outline-dark">Atualizar</button>
                                            </form>
                                        </td>
                                        <td class="text-end product-price">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                        <td class="text-end">
                                            <a href="carrinho.php?remover=<?= urlencode($item['chave']) ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Remover este item do carrinho?');">Remover</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
 
                <div class="d-flex justify-content-between mt-3">
                    <a href="loja.php" class="btn btn-outline-dark">← Continuar Comprando</a>
                    <a href="carrinho.php?esvaziar=1" class="btn btn-outline-danger"
                       onclick="return confirm('Esvaziar todo o carrinho?');">Esvaziar Carrinho</a>
                </div>
            </div>
 
            <!-- Resumo / Checkout -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <h4 class="mb-4">Resumo do Pedido</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Itens</span>
                        <span><?= $qtdCarrinho ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 fs-5 fw-bold border-top pt-3">
                        <span>Total</span>
                        <span class="product-price">R$ <?= number_format($totalCarrinho, 2, ',', '.') ?></span>
                    </div>
 
                    <a href="finalizar-pedido.php" class="btn btn-danger w-100 fw-bold py-2 d-block text-center text-decoration-none">
                        ⚡ Finalizar Pedido
                    </a>
                    <small class="text-muted d-block mt-2 text-center">
                        Você precisa estar logado para concluir a compra.
                    </small>
                </div>
            </div>
 
        </div>
 
    <?php endif; ?>
 
</div>
 
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; 2026 Iron Guard Academy Shop. Oss!</p>
    </div>
</footer>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
