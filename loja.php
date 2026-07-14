<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Iron Guard | Equipamentos de Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        h1,
        h2,
        h3,
        .navbar-brand {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        .shop-header {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1552072092-7f9b8d63efcb?q=80&w=2070') center/cover;
            padding: 60px 0;
            color: white;
            text-align: center;
        }

        .card-product {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-product:hover {
            transform: translateY(-10px);
        }

        .product-price {
            color: #d9534f;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .category-badge {
            font-size: 0.8rem;
            background-color: #e9ecef;
            padding: 5px 10px;
            border-radius: 20px;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">Arte suave</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="menu.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="loja.php">Loja</a></li>
                    <li class="nav-item"><a class="nav-link" href="menu.php#contato">Contato</a></li>
                    <li class="nav-item"><a class="btn btn-danger ms-lg-3" href="carrinho.php">Carrinho (0)</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="shop-header">
        <div class="container">
            <h1 class="display-4 fw-bold">Armaria Iron Guard</h1>
            <p class="lead">Equipamentos testados por quem vive o tatame.</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="row g-4">
            <div class="container my-5">
                <div class="row g-4">
                    <?php
                    include '_conn/connection.php';
                    $stmt = $pdo->query("SELECT * FROM produtos order by id desc ");
                    $stmt->execute();
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($products as $r):
                    ?>

                        <div class="col-md-4">
                            <a style ="text-decoration: none;" href="single.php?id=<?=$r["id"]?>">
                            <div class="card h-100 card-product">
                                <div style="height: 400px; width: 300; overflow: hidden;">
                                    <img src="images/products/<?= $r["imagem"] ?>" class="card-img-top" alt="Kimono">
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="card-title mt-2"> <?= $r["titulo"] ?></h3>
                                    <p class="card-text text-muted"><?= $r["descricao"] ?></p>
                                    <p class="product-price"><?= $r["preco"] ?></p>
                                    <button class="btn btn-dark w-100">Adicionar ao Carrinho</button>

                                </div>
                            </div>
                            </a>
                        </div>
                    <?php
                    endforeach;
                    ?>


                </div>
            </div>


    </main>



    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2026 Iron Guard Academy Shop. Oss!</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>