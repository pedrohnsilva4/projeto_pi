
<?php
include '../../_conn/connection.php';
$post = filter_input_array(INPUT_POST);
$id = $post['id'];
$title = $post['titulo'];
$description = $post['descricao'];
$price = $post['preco'];
$image = $_FILES['imagem'];

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $dir = '../../images/products/';
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    $new_name = uniqid('prod_', true) . '.' . $ext;
    $destination = $dir . $new_name;
    if (move_uploaded_file($image['tmp_name'], $destination)) {
        echo "Imagem enviada com sucesso! Salva como: " . $new_name;
    } else {
        echo "Erro ao mover o arquivo.";
    }
    $stmt = $pdo->prepare("UPDATE produtos SET titulo = :titulo, descricao = :descricao, preco = :preco, imagem = :imagem WHERE id = :id");
    $stmt->bindValue(':imagem', $new_name);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':titulo', $title);
    $stmt->bindValue(':descricao', $description);
    $stmt->bindValue(':preco', $price);
    $stmt->execute();
} else {
    $dir = null;
    $stmt = $pdo->prepare("UPDATE produtos SET titulo = :titulo, descricao = :descricao, preco = :preco WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':titulo', $title);
    $stmt->bindValue(':descricao', $description);
    $stmt->bindValue(':preco', $price);
    $stmt->execute();
}