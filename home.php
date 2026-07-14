<?php
include '_inc/_header.php';
include '../_conn/connection.php';
?>
<h2>Home</h2>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-primary text-white p-2 m-2">
            <div class="inner">
                <?php
                $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM produtos");
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<h3>" . $row['total'] . "</h3>";
                ?>
                <p>Produtos Cadastrados</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-success text-white p-2 m-2">
            <div class="inner">
                <h3>0</h3>
                <p>Vendas</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-danger text-white p-2 m-2">
            <div class="inner">
                <h3>0</h3>
                <p>Usuários</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-info text-white p-2 m-2">
            <div class="inner">
                <h3>0</h3>
                <p>Pedidos</p>
            </div>
        </div>
    </div>
</div>

<?php
include '_inc/_footer.php';
?>