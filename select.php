<?php
include '../../_conn/connection.php';
$stmt = $pdo->prepare("SELECT * FROM produtos order by id desc");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

var_export($results);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table border="1">
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
                <td><?= $r['preco']; ?></td>
                <td><img src="<? $r['imagem'] ?>" alt="<?= $r['titulo'] ?>" width="100"></td>
                <td><a href="edit.php?id=<?php echo $r["id"] ?>" ;>Editar</a>
                    <a href="delete.php?id=<?php echo $r["id"] ?>" onclick="return confirm('Tem certeza?')" ;>Deletar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>