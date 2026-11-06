
<?php
require 'config.php';

$result = $conn->query("
    SELECT orders.*, medicines.medicine_name
    FROM orders
    INNER JOIN medicines ON orders.medicine_id = medicines.id
    ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Orders</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main" style="margin-left:0">

<h1>Customer Orders</h1>
<p>View all medicine purchases recorded in the pharmacy.</p>

<table>
<tr>
<th>Order ID</th>
<th>Customer Name</th>
<th>Medicine</th>
<th>Quantity</th>
<th>Total Amount</th>
<th>Order Date</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['customer_name']); ?></td>
<td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
<td><?php echo $row['quantity']; ?></td>
<td>Ksh <?php echo number_format($row['total_amount'],2); ?></td>
<td><?php echo $row['order_date']; ?></td>
</tr>
<?php } ?>

</table>

<br>
<a href="index.php">Back to Dashboard</a>

</div>
</body>
</html>
