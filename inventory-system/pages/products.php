<?php include("../config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Product Management</h2>

    <!-- 🔹 ADD PRODUCT -->
    <form method="POST" class="mb-4">
        <input type="text" name="name" placeholder="Product Name" class="form-control mb-2" required>
        <textarea name="desc" placeholder="Description" class="form-control mb-2"></textarea>
        <input type="number" step="0.01" name="price" placeholder="Unit Price" class="form-control mb-2" required>

        <button type="submit" name="add" class="btn btn-primary">Add Product</button>
    </form>

    <?php
    // ADD
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $price = $_POST['price'];

        $conn->query("INSERT INTO product (product_name, description, unit_price)
                      VALUES ('$name', '$desc', '$price')");
    }

    // DELETE
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM product WHERE product_id = $id");
    }

    // FETCH
    $result = $conn->query("SELECT * FROM product");
    ?>

    <!-- 🔹 PRODUCT TABLE -->
    <h4>Product List</h4>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['unit_price']; ?></td>

            <td>
                <a href="?delete=<?php echo $row['product_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                <a href="?edit=<?php echo $row['product_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
            </td>
        </tr>
        <?php } ?>

    </table>

    <!-- 🔹 EDIT FORM -->
    <?php
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $edit = $conn->query("SELECT * FROM product WHERE product_id = $id")->fetch_assoc();
    ?>

    <h4>Edit Product</h4>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $edit['product_id']; ?>">

        <input type="text" name="name" value="<?php echo $edit['product_name']; ?>" class="form-control mb-2">
        <textarea name="desc" class="form-control mb-2"><?php echo $edit['description']; ?></textarea>
        <input type="number" step="0.01" name="price" value="<?php echo $edit['unit_price']; ?>" class="form-control mb-2">

        <button type="submit" name="update" class="btn btn-success">Update</button>
    </form>

    <?php } ?>

    <?php
    // UPDATE
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $price = $_POST['price'];

        $conn->query("UPDATE product 
                      SET product_name='$name', description='$desc', unit_price='$price'
                      WHERE product_id=$id");
    }
    ?>

</div>

</body>
</html>