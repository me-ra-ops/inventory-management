<?php include("../config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shipments</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Shipment Management</h2>

    <!-- 🔹 ADD SHIPMENT -->
    <form method="POST" class="mb-4">

        <!-- Product -->
        <select name="product_id" class="form-control mb-2" required>
            <option value="">Select Product</option>
            <?php
            $products = $conn->query("SELECT * FROM product");
            while ($p = $products->fetch_assoc()) {
                echo "<option value='{$p['product_id']}'>{$p['product_name']}</option>";
            }
            ?>
        </select>

        <!-- Customer -->
        <select name="customer_id" class="form-control mb-2" required>
            <option value="">Select Customer</option>
            <?php
            $customers = $conn->query("SELECT * FROM customer");
            while ($c = $customers->fetch_assoc()) {
                echo "<option value='{$c['customer_id']}'>{$c['customer_name']}</option>";
            }
            ?>
        </select>

        <input type="text" name="tracking" placeholder="Tracking Number" class="form-control mb-2" required>
        <input type="text" name="carrier" placeholder="Carrier (e.g., DHL)" class="form-control mb-2">
        <input type="text" name="status" placeholder="Status (Shipped/Delivered)" class="form-control mb-2">

        <button type="submit" name="add" class="btn btn-primary">Create Shipment</button>
    </form>

    <?php
    // ADD SHIPMENT
    if (isset($_POST['add'])) {

        $product_id = $_POST['product_id'];
        $customer_id = $_POST['customer_id'];
        $tracking = $_POST['tracking'];
        $carrier = $_POST['carrier'];
        $status = $_POST['status'];

        $conn->query("INSERT INTO shipment 
            (product_id, customer_id, shipment_date, tracking_number, carrier, status)
            VALUES ($product_id, $customer_id, NOW(), '$tracking', '$carrier', '$status')");

        // 🔥 OPTIONAL: reduce stock when shipped
        $conn->query("UPDATE inventory 
                      SET stock_level = stock_level - 1 
                      WHERE product_id = $product_id");
    }

    // DELETE
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM shipment WHERE shipment_id = $id");
    }
    ?>

    <!-- 🔹 SHIPMENT TABLE -->
    <h4>Shipment List</h4>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Customer</th>
            <th>Tracking</th>
            <th>Carrier</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("
            SELECT shipment.*, product.product_name, customer.customer_name
            FROM shipment
            JOIN product ON shipment.product_id = product.product_id
            JOIN customer ON shipment.customer_id = customer.customer_id
        ");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['shipment_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['tracking_number']}</td>
                <td>{$row['carrier']}</td>
                <td>{$row['status']}</td>
                <td>{$row['shipment_date']}</td>
                <td>
                    <a href='?delete={$row['shipment_id']}' class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
        }
        ?>

    </table>

</div>

</body>
</html>