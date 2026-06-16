<?php
include '../../_conn/connection.php';
$id = $_GET['id'];
// echo $id;
$stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
$stmt->bindValue(':id', $id);
$stmt->execute();

header('Location: select.php');
