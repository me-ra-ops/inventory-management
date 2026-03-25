<?php include("config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- 🔹 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Inventory System</a>

        <div>
            <a href="index.php" class="btn btn-light btn-sm">Dashboard</a>
            <a href="pages/products.php" class="btn btn-light btn-sm">Products</a>
            <a href="pages/inventory.php" class="btn btn-light btn-sm">Inventory</a>
            <a href="pages/suppliers.php" class="btn btn-light btn-sm">Suppliers</a>
            <a href="pages/customers.php" class="btn btn-light btn-sm">Customers</a>
            <a href="pages/orders.php" class="btn btn-light btn-sm">Orders</a>
            <a href="pages/shipments.php" class="btn btn-light btn-sm">Shipments</a>
            <a href="pages/warehouse.php" class="btn btn-light btn-sm">Warehouse</a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <h2 class="mb-4">Dashboard</h2>

    <?php
    // COUNTS
    $p = $conn->query("SELECT COUNT(*) as total FROM product")->fetch_assoc()['total'];
    $c = $conn->query("SELECT COUNT(*) as total FROM customer")->fetch_assoc()['total'];
    $s = $conn->query("SELECT COUNT(*) as total FROM supplier")->fetch_assoc()['total'];
    $o = $conn->query("SELECT COUNT(*) as total FROM purchase_order")->fetch_assoc()['total'];
    $w = $conn->query("SELECT COUNT(*) as total FROM warehouse")->fetch_assoc()['total'];
    $l = $conn->query("SELECT COUNT(*) as total FROM inventory WHERE stock_level < reorder_level")->fetch_assoc()['total'];
    ?>

    <!-- 🔹 CARDS -->
    <div class="row text-center">

        <div class="col-md-2">
            <div class="card p-3 bg-light">
                <h6>Products</h6>
                <h4><?php echo $p; ?></h4>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 bg-light">
                <h6>Customers</h6>
                <h4><?php echo $c; ?></h4>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 bg-light">
                <h6>Suppliers</h6>
                <h4><?php echo $s; ?></h4>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 bg-light">
                <h6>Orders</h6>
                <h4><?php echo $o; ?></h4>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 bg-light">
                <h6>Warehouses</h6>
                <h4><?php echo $w; ?></h4>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 bg-danger text-white">
                <h6>Low Stock</h6>
                <h4><?php echo $l; ?></h4>
            </div>
        </div>

    </div>

    <!-- 🔹 LOW STOCK TABLE -->
    <div class="mt-5">
        <h4>Low Stock Products</h4>

        <table class="table table-bordered">
            <tr>
                <th>Product</th>
                <th>Stock</th>
                <th>Reorder Level</th>
            </tr>

            <?php
            $result = $conn->query("
                SELECT product.product_name, inventory.stock_level, inventory.reorder_level
                FROM inventory
                JOIN product ON inventory.product_id = product.product_id
                WHERE stock_level < reorder_level
            ");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['product_name']}</td>
                    <td>{$row['stock_level']}</td>
                    <td>{$row['reorder_level']}</td>
                </tr>";
            }
            ?>
        </table>
    </div>

</div>

</body>
</html>