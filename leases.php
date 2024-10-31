<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Leases</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Lease Management</h1>

    <!-- Lease Form -->
    <form method="POST" action="leases.php">
        <label>Select Tenant:</label><br>
        <select name="tenant_id" required>
            <option value="">Choose a Tenant</option>
            <?php
            // Fetch tenants from the database
            $tenant_result = $conn->query("SELECT id, name FROM tenants");
            while ($tenant = $tenant_result->fetch_assoc()) {
                echo "<option value='{$tenant['id']}'>{$tenant['name']}</option>";
            }
            ?>
        </select><br>

        <label>Select Property:</label><br>
        <select name="property_id" required>
            <option value="">Choose a Property</option>
            <?php
            // Fetch available properties from the database
            $property_result = $conn->query("SELECT id, name FROM properties WHERE status='available'");
            while ($property = $property_result->fetch_assoc()) {
                echo "<option value='{$property['id']}'>{$property['name']}</option>";
            }
            ?>
        </select><br>

        <label>Start Date:</label><br>
        <input type="date" name="start_date" required><br>

        <label>End Date:</label><br>
        <input type="date" name="end_date" required><br>

        <label>Monthly Rent:</label><br>
        <input type="number" name="monthly_rent" step="0.01" required><br>

        <input type="submit" name="add_lease" value="Add Lease">
    </form>

    <?php
    // Handle lease form submission
    if (isset($_POST['add_lease'])) {
        $tenant_id = $_POST['tenant_id'];
        $property_id = $_POST['property_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $monthly_rent = $_POST['monthly_rent'];

        // Insert lease data into database
        $sql = "INSERT INTO leases (tenant_id, property_id, start_date, end_date, monthly_rent) 
                VALUES ('$tenant_id', '$property_id', '$start_date', '$end_date', '$monthly_rent')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p>Lease added successfully!</p>";

            // Update property status to 'occupied'
            $conn->query("UPDATE properties SET status='occupied' WHERE id='$property_id'");
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }
    ?>

    <h2>Lease List</h2>
    <table border="1">
        <tr>
            <th>Lease ID</th>
            <th>Tenant Name</th>
            <th>Property Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Monthly Rent</th>
        </tr>
        <?php
        // Fetch leases and join with tenant and property tables for display
        $lease_result = $conn->query("SELECT leases.id, tenants.name AS tenant_name, properties.name AS property_name, 
                                      leases.start_date, leases.end_date, leases.monthly_rent 
                                      FROM leases 
                                      JOIN tenants ON leases.tenant_id = tenants.id 
                                      JOIN properties ON leases.property_id = properties.id");
        while ($lease = $lease_result->fetch_assoc()) {
            echo "<tr>
                    <td>{$lease['id']}</td>
                    <td>{$lease['tenant_name']}</td>
                    <td>{$lease['property_name']}</td>
                    <td>{$lease['start_date']}</td>
                    <td>{$lease['end_date']}</td>
                    <td>{$lease['monthly_rent']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>