<?php
include '../_inc/_header.php';
include '../../_conn/connection.php';
?>

    <form action="insert.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="titulo">
        <input type="text" name="descricao" placeholder="descricao">
        <input type="number" name="preco" placeholder="preco" step="0.01">
        <input type="file" name="imagem">
        <input type="submit" name="cadastrar">

    </form>
</body>

</html>