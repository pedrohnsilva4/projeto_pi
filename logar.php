<?php
session_start();
include_once '../_conn/connection.php';
$post = filter_input_array(INPUT_POST);
// var_dump($post);
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
$stmt->bindValue(':email', $post['email']);
$stmt->bindValue(':password', $post['password']);
$stmt->execute();
if($stmt->rowCount() > 0){
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['email'] = $result['email'];
    // echo 'Existe o Usuário ' . $_SESSION['email'];
    header('Location: home.php');
} else {
    // echo 'Não existe o usuário';
    header('Location: ../login.php?erroruser=true');
}