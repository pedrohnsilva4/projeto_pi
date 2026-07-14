<?php
include '../_inc/_header.php';
include '../../_conn/connection.php';
$stmt = $pdo->prepare("SELECT * FROM produtos order by id desc");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="index.php" class="btn btn-primary m-2">Adicionar</a>

<body>
    <table class="table table-striped">
        <tr>
            <th>titulo</th>
            <th>descrição</th>
            <th>preco</th>
            <th>imagem</th>

        </tr>
        <?php foreach ($results as $r) : ?>
            <tr>
                <td><?= $r['titulo']; ?></td>
                <td><?= $r['descricao']; ?></td>
                <td> $ <?= number_format($r['preco'], 2, ",", "."); ?></td>
                <td><img src="../../images/products/<?= $r['imagem'] ?>" alt="<?= $r['titulo'] ?>" width="100"></td>
                <td><a class="btn btn-info m-2"href="edit.php?id=<?php echo $r["id"] ?>";>Editar</a>
                    <a class="btn btn-danger m-2"href="delete.php?id=<?php echo $r["id"] ?>" onclick="return confirm('Tem certeza?')" ;>Deletar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php

            include '../_inc/_footer.php';
    ?>