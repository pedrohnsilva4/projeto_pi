
<?php
include '../_inc/_header.php';
include '../../_conn/connection.php';
$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = :id');
$stmt->bindValue(':id', $id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form class="form-control" action="update.php" method="post" enctype="multipart/form-data" >
    <input class="form-control" type="hidden" name="id" value="<?php echo $product['id']; ?>" />
    <input class="form-control" type="text" name="titulo" placeholder="titulo" value="<?php echo $product['titulo']; ?>" /> <br/>
    <textarea class="form-control" type="text" name="descricao" placeholder="Descricao"><?php echo $product['descricao']; ?></textarea> <br/>
    <input class="form-control" type="number" name="preco" placeholder="Preco" step="0.01" value="<?php echo $product['preco']; ?>" /> <br/>
    <input class="form-control" type="file" name="imagem">
    <input class="form-control" type="submit" value="Cadastrar">
</form>

<?php
include '../_inc/_footer.php';
?>