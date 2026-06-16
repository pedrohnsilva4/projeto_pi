
<?php
include '../_inc/_header.php';
include '../../_conn/connection.php';
$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = :id');
$stmt->bindValue(':id', $id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form action="update.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <input type="text" name="titulo" placeholder="titulo" value="<?php echo $product['titulo']; ?>"> <br/>
    <input type="text" name="descricao" placeholder="Descricao" value="<?php echo $product['descricao']; ?>"> <br/>
    <input type="number" name="preco" placeholder="Preco" step="0.01" value="<?php echo $product['preco']; ?>"> <br/>
    <input type="file" name="imagem">
    <input type="submit" value="Cadastrar">
</form>

<?php
include '../_inc/_footer.php';
?>