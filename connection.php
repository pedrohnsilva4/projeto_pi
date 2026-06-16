<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=projeto99', 'root', '');
} catch (Exception $e) {
    echo 'Erro ao conectar com o banco de dados: ' . $e->getMessage();
}



?>