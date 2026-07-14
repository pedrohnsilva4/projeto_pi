<?php
include '_conn/connection.php';
// Captura o ID da URL e garante que seja um número inteiro
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Busca os dados do produto específico
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o produto não existir, redireciona para a loja
if (!$produto) {
    header("Location: loja.php"); 
    exit;
}

// LÓGICA DA PROMOÇÃO (Cálculo automático de 20% de desconto baseado no preço atual)
$porcentagem_desconto = 20; 
$preco_antigo = $produto["preco"] / (1 - ($porcentagem_desconto / 100));
$parcela = $produto["preco"] / 10;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto["titulo"]) ?> | PROMOÇÃO | Loja Iron Guard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Base */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        h1, h2, h3, h4, .navbar-brand {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        /* Estilos da Imagem */
        .product-image-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            text-align: center;
        }

        .product-image-container img {
            max-height: 500px;
            object-fit: contain;
            width: 100%;
        }

        /* Breadcrumb */
        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: #d9534f;
        }

        /* Elementos da Promoção */
        .promo-badge {
            font-family: 'Oswald', sans-serif;
            background-color: #d9534f;
            color: white;
            padding: 5px 12px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
            display: inline-block;
        }

        .price-old {
            font-size: 1.1rem;
            color: #6c757d;
            text-decoration: line-through;
        }

        .product-price {
            color: #d9534f;
            font-size: 2.5rem; 
            font-weight: 700;
        }

        .installment-text {
            color: #198754;
            font-weight: bold;
            font-size: 1rem;
        }

        /* Caixa de Urgência / Cronômetro */
        .timer-box {
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
            color: #664d03;
            padding: 12px;
            border-radius: 4px;
            font-weight: 500;
        }

        /* Botão de Compra */
        .btn-buy {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 15px;
            font-family: 'Oswald', sans-serif;
            font-size: 1.4rem;
            text-transform: uppercase;
            transition: all 0.3s ease;
            letter-spacing: 1px;
        }

        .btn-buy:hover {
            background-color: #c9302c;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(217, 83, 79, 0.4);
        }
        
        /* Seleção de Tamanho */
        .size-badge {
            cursor: pointer;
            border: 2px solid #dee2e6;
            padding: 10px 20px;
            margin-right: 8px;
            display: inline-block;
            font-weight: bold;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .size-badge:hover, .size-check:checked + .size-badge {
            border-color: #343a40;
            background-color: #343a40;
            color: white;
        }

        .size-check {
            display: none;
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
                    <li class="nav-item"><a class="nav-link active" href="loja.php">Loja</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.html#contato">Contato</a></li>
                    <li class="nav-item"><a class="btn btn-danger ms-lg-3" href="#">Carrinho (0)</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="loja.php">Loja</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($produto["titulo"]) ?></li>
            </ol>
        </nav>
    </div>

    <main class="container py-4">
        <div class="row g-5">
            <div class="col-md-6">
                <div class="product-image-container">
                    <img src="images/products/<?= $produto["imagem"] ?>" alt="<?= htmlspecialchars($produto["titulo"]) ?>" class="img-fluid">
                </div>
            </div>

            <div class="col-md-6 d-flex flex-column justify-content-center">
                
                <div class="mb-2">
                    <span class="promo-badge"> OFERTA POR TEMPO LIMITADO</span>
                </div>

                <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($produto["titulo"]) ?></h1>
                
                <div class="mb-3">
                    <span class="price-old">De: R$ <?= number_format($preco_antigo, 2, ',', '.') ?></span>
                    <div class="d-flex align-items-center gap-3">
                        <p class="product-price mb-0">Por: R$ <?= number_format($produto["preco"], 2, ',', '.') ?></p>
                        <span class="badge bg-success text-uppercase"><?= $porcentagem_desconto ?>% OFF</span>
                    </div>
                    <p class="installment-text mb-0">ou 10x de R$ <?= number_format($parcela, 2, ',', '.') ?> sem juros no cartão</p>
                </div>

                <div class="timer-box mb-4 shadow-sm d-flex align-items-center gap-2">
                    <span>⏳</span>
                    <span>Aproveite! Esta promoção expira em: <strong id="countdown" class="text-danger">15:00</strong> minutos.</span>
                </div>
                
                <hr class="mt-0">
                
                <h4 class="text-muted">Descrição do Equipamento</h4>
                <p class="lead text-secondary mb-4" style="font-size: 1.1rem; line-height: 1.6;"><?= htmlspecialchars($produto["descricao"]) ?></p>

                <div class="mb-4">
                    <h4 class="text-muted mb-3">Selecione o Tamanho:</h4>
                    <form action="carrinho.php" method="POST">
                        <input type="hidden" name="produto_id" value="<?= $produto["id"] ?>">
                        
                        <div class="d-flex flex-wrap gap-2">
                            <label>
                                <input type="radio" name="tamanho" value="A1" class="size-check" checked>
                                <span class="size-badge">A1</span>
                            </label>
                            <label>
                                <input type="radio" name="tamanho" value="A2" class="size-check">
                                <span class="size-badge">A2</span>
                            </label>
                            <label>
                                <input type="radio" name="tamanho" value="A3" class="size-check">
                                <span class="size-badge">A3</span>
                            </label>
                            <label>
                                <input type="radio" name="tamanho" value="A4" class="size-check">
                                <span class="size-badge">A4</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-buy w-100 mt-4 shadow-sm fw-bold rounded-3">
                            ⚡ COMPRAR AGORA COM DESCONTO
                        </button>
                        
                        <div class="text-center mt-2">
                            <small class="text-muted">🔒 Compra 100% Segura | Estoque Limitado para o Lote Atual</small>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 Iron Guard Academy Shop. Oss!</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function iniciarCronometro(duracao, display) {
            let timer = duracao, minutos, segundos;
            setInterval(function () {
                minutos = parseInt(timer / 60, 10);
                segundos = parseInt(timer % 60, 10);

                minutos = minutos < 10 ? "0" + minutos : minutos;
                segundos = segundos < 10 ? "0" + segundos : segundos;

                display.textContent = minutos + ":" + segundos;

                if (--timer < 0) {
                    timer = duracao; // Reinicia o contador para fins puramente estéticos (gatilho contínuo)
                }
            }, 1000);
        }

        window.onload = function () {
            const quinzeMinutos = 60 * 15, // Altere os minutos alterando o '15'
                display = document.querySelector('#countdown');
            iniciarCronometro(quinzeMinutos, display);
        };
    </script>
</body>

</html>