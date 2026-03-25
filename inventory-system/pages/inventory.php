<?php include("../config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Inventory Management</h2>

    <!-- 🔹 ADD INVENTORY -->
    <form method="POST" class="mb-4">

        <!-- Product Dropdown -->
        <select name="product_id" class="form-control mb-2" required>
            <option value="">Select Product</option>
            <?php
            $products = $conn->query("SELECT * FROM product");
            while ($p = $products->fetch_assoc()) {
                echo "<option value='{$p['product_id']}'>{$p['product_name']}</option>";
            }
            ?>
        </select>

        <input type="number" name="stock" placeholder="Stock Level" class="form-control mb-2" required>
        <input type="number" name="reorder" placeholder="Reorder Level" class="form-control mb-2" required>

        <button type="submit" name="add" class="btn btn-primary">Add Inventory</button>
    </form>

    <?php
    // ADD INVENTORY
    if (isset($_POST['add'])) {
        $product_id = $_POST['product_id'];
        $stock = $_POST['stock'];
        $reorder = $_POST['reorder'];

        $conn->query("INSERT INTO inventory (product_id, stock_level, reorder_level)
                      VALUES ($product_id, $stock, $reorder)");
    }

    // DELETE
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM inventory WHERE inventory_id = $id");
    }
    ?>

    <!-- 🔹 INVENTORY TABLE -->
    <h4>Inventory List</h4>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Stock</th>
            <th>Reorder Level</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("
            SELECT inventory.*, product.product_name 
            FROM inventory 
            JOIN product ON inventory.product_id = product.product_id
        ");

        while ($row = $result->fetch_assoc()) {

            // LOW STOCK CHECK
            $status = ($row['stock_level'] < $row['reorder_level']) 
                      ? "<span class='text-danger'>Low Stock</span>" 
                      : "<span class='text-success'>OK</span>";

            echo "<tr>
                <td>{$row['inventory_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['stock_level']}</td>
                <td>{$row['reorder_level']}</td>
                <td>$status</td>
                <td>
                    <a href='?delete={$row['inventory_id']}' class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
        }
        ?>

    </table>

</div>

</body>
</html>