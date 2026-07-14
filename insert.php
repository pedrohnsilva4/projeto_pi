<?php
//include '../../_conn/connection.php';
include __DIR__ . '/../../_conn/connection.php';

$post = filter_input_array(INPUT_POST);

$dir = '../../images/products/';
if (!file_exists($dir)):
    mkdir($dir, 0755, true);
endif;
if (isset($_FILES['imagem'])) {
    $image = $_FILES['imagem'];
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    $new_name = uniqid('prod_', true) . '.' . $ext;
    $destination = $dir . $new_name;
    if (move_uploaded_file($image['tmp_name'], $destination)) {
        echo "Imagem enviada com sucesso! Salva como: " . $new_name;
    } else {
        echo "Erro ao mover o arquivo.";
    }
    $post['imagem'] = $new_name;
}

if (empty($post['titulo']) || empty($post['descricao']) || empty($post['preco']) || empty($post['imagem'])) {
    echo "Todos os campos são obrigatórios.";
    header('location: index.php?erro=empty');
    exit;
}
$stmt = $pdo->prepare("INSERT INTO produtos (titulo, descricao, preco, imagem) VALUES (:titulo, :descricao, :preco, :imagem)");
$stmt->bindValue(':titulo', $post['titulo']);
$stmt->bindValue(':descricao', $post['descricao']);
$stmt->bindValue(':preco', $post['preco']);
$stmt->bindValue(':imagem', $post['imagem']);
$stmt->execute();

header('Location: select.php');