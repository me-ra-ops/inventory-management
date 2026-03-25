<?php include("../config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Suppliers</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Supplier Management</h2>

    <!-- 🔹 ADD SUPPLIER -->
    <form method="POST" class="mb-4">
        <input type="text" name="name" placeholder="Supplier Name" class="form-control mb-2" required>
        <input type="text" name="contact" placeholder="Contact" class="form-control mb-2">
        <textarea name="address" placeholder="Address" class="form-control mb-2"></textarea>

        <button type="submit" name="add" class="btn btn-primary">Add Supplier</button>
    </form>

    <?php
    // ADD
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];

        $conn->query("INSERT INTO supplier (supplier_name, contact, address)
                      VALUES ('$name', '$contact', '$address')");
    }

    // DELETE
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM supplier WHERE supplier_id = $id");
    }

    // FETCH
    $result = $conn->query("SELECT * FROM supplier");
    ?>

    <!-- 🔹 SUPPLIER TABLE -->
    <h4>Supplier List</h4>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['supplier_id']; ?></td>
            <td><?php echo $row['supplier_name']; ?></td>
            <td><?php echo $row['contact']; ?></td>
            <td><?php echo $row['address']; ?></td>

            <td>
                <a href="?delete=<?php echo $row['supplier_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                <a href="?edit=<?php echo $row['supplier_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
            </td>
        </tr>
        <?php } ?>

    </table>

    <!-- 🔹 EDIT FORM -->
    <?php
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $edit = $conn->query("SELECT * FROM supplier WHERE supplier_id = $id")->fetch_assoc();
    ?>

    <h4>Edit Supplier</h4>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $edit['supplier_id']; ?>">

        <input type="text" name="name" value="<?php echo $edit['supplier_name']; ?>" class="form-control mb-2">
        <input type="text" name="contact" value="<?php echo $edit['contact']; ?>" class="form-control mb-2">
        <textarea name="address" class="form-control mb-2"><?php echo $edit['address']; ?></textarea>

        <button type="submit" name="update" class="btn btn-success">Update</button>
    </form>

    <?php } ?>

    <?php
    // UPDATE
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];

        $conn->query("UPDATE supplier 
                      SET supplier_name='$name', contact='$contact', address='$address'
                      WHERE supplier_id=$id");
    }
    ?>

</div>

</body>
</html>