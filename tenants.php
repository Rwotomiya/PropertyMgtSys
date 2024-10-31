<!-- tenants.php -->
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tenants</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Tenant Management</h1>
    <form method="POST" action="tenants.php">
        <label>Tenant Name:</label><br>
        <input type="text" name="name" required><br>

        <label>Contact:</label><br>
        <input type="text" name="contact"><br>

        <label>Email:</label><br>
        <input type="text" name="email"><br>

        <input type="submit" name="add_tenant" value="Add Tenant">
    </form>

    <?php
    if (isset($_POST['add_tenant'])) {
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];

        $sql = "INSERT INTO tenants (name, contact, email) VALUES ('$name', '$contact', '$email')";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Tenant added successfully!</p>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }
    ?>

    <h2>Tenant List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Email</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM tenants");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['contact']}</td>
                    <td>{$row['email']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>