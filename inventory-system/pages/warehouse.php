<?php include("../config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Warehouse</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Warehouse Management</h2>

    <!-- 🔹 ADD WAREHOUSE -->
    <form method="POST" class="mb-4">
        <input type="text" name="name" placeholder="Warehouse Name" class="form-control mb-2" required>
        <input type="text" name="location" placeholder="Location" class="form-control mb-2">
        <input type="number" name="capacity" placeholder="Capacity" class="form-control mb-2">
        <input type="text" name="manager" placeholder="Manager Name" class="form-control mb-2">

        <button type="submit" name="add" class="btn btn-primary">Add Warehouse</button>
    </form>

    <?php
    // ADD
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $capacity = $_POST['capacity'];
        $manager = $_POST['manager'];

        $conn->query("INSERT INTO warehouse (name, location, capacity, manager_name)
                      VALUES ('$name', '$location', '$capacity', '$manager')");
    }

    // DELETE
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM warehouse WHERE warehouse_id = $id");
    }

    // FETCH
    $result = $conn->query("SELECT * FROM warehouse");
    ?>

    <!-- 🔹 WAREHOUSE TABLE -->
    <h4>Warehouse List</h4>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Manager</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['warehouse_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td><?php echo $row['capacity']; ?></td>
            <td><?php echo $row['manager_name']; ?></td>

            <td>
                <a href="?delete=<?php echo $row['warehouse_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                <a href="?edit=<?php echo $row['warehouse_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
            </td>
        </tr>
        <?php } ?>

    </table>

    <!-- 🔹 EDIT FORM -->
    <?php
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $edit = $conn->query("SELECT * FROM warehouse WHERE warehouse_id = $id")->fetch_assoc();
    ?>

    <h4>Edit Warehouse</h4>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $edit['warehouse_id']; ?>">

        <input type="text" name="name" value="<?php echo $edit['name']; ?>" class="form-control mb-2">
        <input type="text" name="location" value="<?php echo $edit['location']; ?>" class="form-control mb-2">
        <input type="number" name="capacity" value="<?php echo $edit['capacity']; ?>" class="form-control mb-2">
        <input type="text" name="manager" value="<?php echo $edit['manager_name']; ?>" class="form-control mb-2">

        <button type="submit" name="update" class="btn btn-success">Update</button>
    </form>

    <?php } ?>

    <?php
    // UPDATE
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $location = $_POST['location'];
        $capacity = $_POST['capacity'];
        $manager = $_POST['manager'];

        $conn->query("UPDATE warehouse 
                      SET name='$name', location='$location', capacity='$capacity', manager_name='$manager'
                      WHERE warehouse_id=$id");
    }
    ?>

</div>

</body>
</html>