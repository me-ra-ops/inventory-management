<?php include("../config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Purchase Orders</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Purchase Orders</h2>

    <!-- 🔹 ADD ORDER -->
    <form method="POST" class="mb-4">

        <!-- Product -->
        <select name="product_id" class="form-control mb-2" required>
            <option value="">Select Product</option>
            <?php
            $products = $conn->query("SELECT * FROM product");
            while ($p = $products->fetch_assoc()) {
                echo "<option value='{$p['product_id']}' data-price='{$p['unit_price']}'>{$p['product_name']}</option>";
            }
            ?>
        </select>

        <!-- Supplier -->
        <select name="supplier_id" class="form-control mb-2" required>
            <option value="">Select Supplier</option>
            <?php
            $suppliers = $conn->query("SELECT * FROM supplier");
            while ($s = $suppliers->fetch_assoc()) {
                echo "<option value='{$s['supplier_id']}'>{$s['supplier_name']}</option>";
            }
            ?>
        </select>

        <input type="number" name="quantity" placeholder="Quantity" class="form-control mb-2" required>

        <button type="submit" name="add" class="btn btn-primary">Create Order</button>
    </form>

    <?php
    // ADD ORDER
    if (isset($_POST['add'])) {

        $product_id = $_POST['product_id'];
        $supplier_id = $_POST['supplier_id'];
        $quantity = $_POST['quantity'];

        // GET PRODUCT PRICE
        $price_row = $conn->query("SELECT unit_price FROM product WHERE product_id = $product_id")->fetch_assoc();
        $price = $price_row['unit_price'];

        $total = $price * $quantity;

        // INSERT ORDER
        $conn->query("INSERT INTO purchase_order (product_id, supplier_id, order_date, quantity, total_amount)
                      VALUES ($product_id, $supplier_id, NOW(), $quantity, $total)");

        // 🔥 UPDATE INVENTORY (IMPORTANT)
        $conn->query("UPDATE inventory 
                      SET stock_level = stock_level + $quantity 
                      WHERE product_id = $product_id");
    }

    // DELETE ORDER
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM purchase_order WHERE po_id = $id");
    }
    ?>

    <!-- 🔹 ORDER TABLE -->
    <h4>Order List</h4>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Supplier</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("
            SELECT purchase_order.*, product.product_name, supplier.supplier_name
            FROM purchase_order
            JOIN product ON purchase_order.product_id = product.product_id
            JOIN supplier ON purchase_order.supplier_id = supplier.supplier_id
        ");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['po_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['supplier_name']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['total_amount']}</td>
                <td>{$row['order_date']}</td>
                <td>
                    <a href='?delete={$row['po_id']}' class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
        }
        ?>

    </table>

</div>

</body>
</html>