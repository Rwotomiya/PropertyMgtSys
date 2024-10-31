<!-- properties.php -->
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Properties</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Property Management</h1>
    <form method="POST" action="properties.php">
        <label>Property Name:</label><br>
        <input type="text" name="name" required><br>

        <label>Location:</label><br>
        <input type="text" name="location"><br>

        <label>Type:</label><br>
        <input type="text" name="type"><br>

        <input type="submit" name="add_property" value="Add Property">
    </form>

    <?php
    if (isset($_POST['add_property'])) {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $type = $_POST['type'];

        $sql = "INSERT INTO properties (name, location, type, status) VALUES ('$name', '$location', '$type', 'available')";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Property added successfully!</p>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }
    ?>

    <h2>Property List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Location</th>
            <th>Type</th>
            <th>Status</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM properties");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['location']}</td>
                    <td>{$row['type']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>