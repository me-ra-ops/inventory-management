<?php include("../config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2 class="mb-4">Inventory Dashboard</h2>

    <?php
    // Total Products
    $p = $conn->query("SELECT COUNT(*) as total FROM product")->fetch_assoc()['total'];

    // Total Customers
    $c = $conn->query("SELECT COUNT(*) as total FROM customer")->fetch_assoc()['total'];

    // Total Orders
    $o = $conn->query("SELECT COUNT(*) as total FROM purchase_order")->fetch_assoc()['total'];

    // Low Stock
    $l = $conn->query("SELECT COUNT(*) as total FROM inventory WHERE stock_level < reorder_level")->fetch_assoc()['total'];
    ?>

    <!-- 🔹 SUMMARY CARDS -->
    <div class="row text-center">

        <div class="col-md-3">
            <div class="card p-3 bg-light">
                <h5>Total Products</h5>
                <h3><?php echo $p; ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 bg-light">
                <h5>Customers</h5>
                <h3><?php echo $c; ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 bg-light">
                <h5>Orders</h5>
                <h3><?php echo $o; ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 bg-danger text-white">
                <h5>Low Stock</h5>
                <h3><?php echo $l; ?></h3>
            </div>
        </div>

    </div>

    <!-- 🔹 LOW STOCK TABLE -->
    <div class="mt-5">
        <h4>Low Stock Products</h4>

        <table class="table table-bordered">
            <tr>
                <th>Product ID</th>
                <th>Stock</th>
                <th>Reorder Level</th>
            </tr>

            <?php
            $result = $conn->query("SELECT * FROM inventory WHERE stock_level < reorder_level");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['product_id']}</td>
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