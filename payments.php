<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Payment Management</h1>

    <!-- Payment Form -->
    <form method="POST" action="payments.php">
        <label>Select Lease:</label><br>
        <select name="lease_id" required>
            <option value="">Choose a Lease</option>
            <?php
            // Fetch leases from the database
            $lease_result = $conn->query("SELECT leases.id, tenants.name AS tenant_name, properties.name AS property_name 
                                          FROM leases 
                                          JOIN tenants ON leases.tenant_id = tenants.id 
                                          JOIN properties ON leases.property_id = properties.id");
            while ($lease = $lease_result->fetch_assoc()) {
                echo "<option value='{$lease['id']}'>Tenant: {$lease['tenant_name']} | Property: {$lease['property_name']}</option>";
            }
            ?>
        </select><br>

        <label>Payment Date:</label><br>
        <input type="date" name="payment_date" required><br>

        <label>Amount Paid:</label><br>
        <input type="number" name="amount" step="0.01" required><br>

        <input type="submit" name="add_payment" value="Record Payment">
    </form>

    <?php
    // Handle payment form submission
    if (isset($_POST['add_payment'])) {
        $lease_id = $_POST['lease_id'];
        $payment_date = $_POST['payment_date'];
        $amount = $_POST['amount'];

        // Insert payment data into the database
        $sql = "INSERT INTO payments (lease_id, date_paid, amount) 
                VALUES ('$lease_id', '$payment_date', '$amount')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p>Payment recorded successfully!</p>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }
    ?>

    <h2>Payment History</h2>
    <table border="1">
        <tr>
            <th>Payment ID</th>
            <th>Tenant Name</th>
            <th>Property Name</th>
            <th>Payment Date</th>
            <th>Amount Paid</th>
        </tr>
        <?php
        // Fetch payments and join with lease, tenant, and property tables for display
        $payment_result = $conn->query("SELECT payments.id, tenants.name AS tenant_name, properties.name AS property_name, 
                                        payments.date_paid, payments.amount 
                                        FROM payments 
                                        JOIN leases ON payments.lease_id = leases.id 
                                        JOIN tenants ON leases.tenant_id = tenants.id 
                                        JOIN properties ON leases.property_id = properties.id");
        while ($payment = $payment_result->fetch_assoc()) {
            echo "<tr>
                    <td>{$payment['id']}</td>
                    <td>{$payment['tenant_name']}</td>
                    <td>{$payment['property_name']}</td>
                    <td>{$payment['date_paid']}</td>
                    <td>{$payment['amount']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>