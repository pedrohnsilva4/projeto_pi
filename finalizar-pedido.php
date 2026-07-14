<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '_conn/connection.php';
include 'functions.php';

// Sem itens no carrinho, não há o que finalizar
if (empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit;
}

// Cliente/endereço só são buscados se houver um cliente_id na sessão.
// Sem client_auth.php, não é obrigatório estar logado para chegar nesta página.
$cliente = null;
$endereco = null;

if (!empty($_SESSION['cliente_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->bindValue(':id', $_SESSION['cliente_id']);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM endereco WHERE cliente_id = :id LIMIT 1");
    $stmt->bindValue(':id', $_SESSION['cliente_id']);
    $stmt->execute();
    $endereco = $stmt->fetch(PDO::FETCH_ASSOC);
}

$erro = null;

// ===================== PROCESSA A CONFIRMAÇÃO DO PEDIDO =====================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'confirmar') {

    // Nunca confie no preço salvo na sessão: busca o preço atual no banco
    $ids = array_values(array_unique(array_column($_SESSION['carrinho'], 'produto_id')));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    $produtosDb = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $produtosDb[$p['id']] = $p;
    }

    $total = 0;
    $itensFinal = [];
    foreach ($_SESSION['carrinho'] as $item) {
        if (!isset($produtosDb[$item['produto_id']])) {
            continue; // produto pode ter sido removido do catálogo
        }
        $p = $produtosDb[$item['produto_id']];
        $subtotal = $p['preco'] * $item['quantidade'];
        $total += $subtotal;

        $itensFinal[] = [
            'titulo_produto' => $p['titulo'],
            'tamanho'        => $item['tamanho'],
            'quantidade'     => $item['quantidade'],
            'preco_unitario' => $p['preco'],
        ];
    }

    if (empty($itensFinal)) {
        $erro = "Não foi possível processar seu pedido. Tente novamente.";
    } else {
        try {
            $pdo->beginTransaction();

            $stmtPedido = $pdo->prepare(
                "INSERT INTO pedidos (cliente_id, total, status, criado_em)
                 VALUES (:cliente_id, :total, 'pendente', NOW())"
            );
            $stmtPedido->execute([
                ':cliente_id' => $_SESSION['cliente_id'] ?? null,
                ':total'      => $total,
            ]);
            $pedidoId = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare(
                "INSERT INTO pedido_itens (pedido_id, titulo_produto, tamanho, quantidade, preco_unitario)
                 VALUES (:pedido_id, :titulo_produto, :tamanho, :quantidade, :preco_unitario)"
            );
            foreach ($itensFinal as $item) {
                $stmtItem->execute([
                    ':pedido_id'      => $pedidoId,
                    ':titulo_produto' => $item['titulo_produto'],
                    ':tamanho'        => $item['tamanho'],
                    ':quantidade'     => $item['quantidade'],
                    ':preco_unitario' => $item['preco_unitario'],
                ]);
            }

            $pdo->commit();

            // Carrinho esvaziado após o pedido ser criado com sucesso
            $_SESSION['carrinho'] = [];

            header('Location: perfil.php?pedido_ok=1');
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao processar o pedido. Tente novamente.";
        }
    }
}

// ===================== MONTA DADOS PARA EXIBIÇÃO =====================

$itensCarrinho = [];
$totalCarrinho = 0;

$ids = array_values(array_unique(array_column($_SESSION['carrinho'], 'produto_id')));
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
$stmt->execute($ids);

$produtosDb = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
    $produtosDb[$p['id']] = $p;
}

foreach ($_SESSION['carrinho'] as $item) {
    if (!isset($produtosDb[$item['produto_id']])) {
        continue;
    }
    $p = $produtosDb[$item['produto_id']];
    $subtotal = $p['preco'] * $item['quantidade'];
    $totalCarrinho += $subtotal;

    $itensCarrinho[] = [
        'produto'    => $p,
        'tamanho'    => $item['tamanho'],
        'quantidade' => $item['quantidade'],
        'subtotal'   => $subtotal,
    ];
}

$qtdCarrinho = totalItensCar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido | Iron Guard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f4f4f4; }
        h1, h2, h3, h4, .navbar-brand { font-family: 'Oswald', sans-serif; text-transform: uppercase; }

        .checkout-header {
            background: linear-gradient(135deg, #121212 0%, #2c2c2c 100%);
            color: white;
            padding: 40px 0;
            border-bottom: 5px solid #d9534f;
        }

        .card-section {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .product-price { color: #d9534f; font-weight: bold; }
        .btn-danger { background-color: #d9534f; border: none; }
        .btn-danger:hover { background-color: #c9302c; }

        .pay-option {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .pay-option:hover { border-color: #343a40; }
        .pay-check:checked + .pay-option {
            border-color: #d9534f;
            background-color: #fdf1f0;
        }
        .pay-check { display: none; }

        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 90px;
        }

        .item-row img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 4px;
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

<section class="checkout-header">
    <div class="container">
        <h1 class="display-5 fw-bold mb-1">Finalizar Pedido</h1>
        <p class="text-white-50 mb-0">Confirme seus dados e conclua a compra.</p>
    </div>
</section>

<div class="container my-5">

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="finalizar-pedido.php" method="POST">
    <input type="hidden" name="acao" value="confirmar">

    <div class="row g-4">

        <div class="col-lg-8">

            <!-- Endereço de entrega -->
            <div class="card-section mb-4">
                <h4 class="mb-3">Endereço de Entrega</h4>
                <?php if ($endereco): ?>
                    <p class="mb-1 fw-bold"><?= htmlspecialchars($cliente['nome']) ?></p>
                    <p class="mb-0 text-muted">
                        <?= htmlspecialchars($endereco['logradouro']) ?>, <?= htmlspecialchars($endereco['numero']) ?>
                        <?php if (!empty($endereco['complemento'])): ?> - <?= htmlspecialchars($endereco['complemento']) ?><?php endif; ?><br>
                        <?= htmlspecialchars($endereco['bairro']) ?> — <?= htmlspecialchars($endereco['cidade']) ?>/<?= htmlspecialchars($endereco['estado']) ?><br>
                        CEP: <?= htmlspecialchars($endereco['cep']) ?>
                    </p>
                <?php else: ?>
                    <p class="text-muted mb-0">Nenhum endereço cadastrado no momento.</p>
                <?php endif; ?>
            </div>

            <!-- Forma de pagamento -->
            <div class="card-section">
                <h4 class="mb-3">Forma de Pagamento</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="w-100">
                            <input type="radio" name="pagamento" value="pix" class="pay-check" checked>
                            <div class="pay-option text-center">
                                <div class="fs-4">💠</div>
                                <div class="fw-bold">Pix</div>
                                <small class="text-muted">Aprovação imediata</small>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="w-100">
                            <input type="radio" name="pagamento" value="cartao" class="pay-check">
                            <div class="pay-option text-center">
                                <div class="fs-4">💳</div>
                                <div class="fw-bold">Cartão de Crédito</div>
                                <small class="text-muted">em até 10x sem juros</small>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="w-100">
                            <input type="radio" name="pagamento" value="boleto" class="pay-check">
                            <div class="pay-option text-center">
                                <div class="fs-4">🧾</div>
                                <div class="fw-bold">Boleto</div>
                                <small class="text-muted">Compensação em 1-2 dias úteis</small>
                            </div>
                        </label>
                    </div>
                </div>
                <small class="text-muted d-block mt-3">
                    * Ambiente de demonstração — nenhum pagamento real é processado.
                </small>
            </div>

        </div>

        <!-- Resumo do pedido -->
        <div class="col-lg-4">
            <div class="summary-card">
                <h4 class="mb-3">Resumo do Pedido</h4>

                <?php foreach ($itensCarrinho as $item): ?>
                    <div class="d-flex align-items-center gap-2 item-row mb-3">
                        <img src="images/products/<?= htmlspecialchars($item['produto']['imagem']) ?>" alt="<?= htmlspecialchars($item['produto']['titulo']) ?>">
                        <div class="flex-grow-1">
                            <div class="small fw-bold"><?= htmlspecialchars($item['produto']['titulo']) ?></div>
                            <div class="small text-muted">Tam. <?= htmlspecialchars($item['tamanho']) ?> &times; <?= $item['quantidade'] ?></div>
                        </div>
                        <div class="small product-price">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></div>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex justify-content-between mb-3 fs-5 fw-bold border-top pt-3">
                    <span>Total</span>
                    <span class="product-price">R$ <?= number_format($totalCarrinho, 2, ',', '.') ?></span>
                </div>

                <button type="submit" class="btn btn-danger w-100 fw-bold py-2">
                    ✔ Confirmar Pedido
                </button>

                <a href="carrinho.php" class="btn btn-outline-dark w-100 mt-2">← Voltar ao Carrinho</a>
            </div>
        </div>

    </div>
    </form>

</div>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; 2026 Iron Guard Academy Shop. Oss!</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>