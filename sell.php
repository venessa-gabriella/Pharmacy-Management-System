
<?php
require 'config.php';

$message = "";

if (isset($_POST['sell'])) {

    $customer = trim($_POST['customer_name']);
    $medicine_id = (int)$_POST['medicine_id'];
    $quantity = (int)$_POST['quantity'];

    if ($customer == "" || $quantity <= 0) {
        $message = "Please enter valid customer and quantity details.";
    } else {
        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare(
                "SELECT medicine_name, quantity, price, expiry_date
                 FROM medicines WHERE id=? FOR UPDATE"
            );
            $stmt->bind_param("i", $medicine_id);
            $stmt->execute();
            $medicine = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$medicine) {
                throw new Exception("Medicine not found.");
            }

            if ($medicine['expiry_date'] &&
                $medicine['expiry_date'] < date('Y-m-d')) {
                throw new Exception("This medicine has expired.");
            }

            if ($medicine['quantity'] < $quantity) {
                throw new Exception("Not enough medicine in stock.");
            }

            $total = $medicine['price'] * $quantity;

            $stmt = $conn->prepare(
                "INSERT INTO orders
                (customer_name, medicine_id, quantity, total_amount)
                VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("siid", $customer, $medicine_id, $quantity, $total);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare(
                "UPDATE medicines SET quantity = quantity - ? WHERE id=?"
            );
            $stmt->bind_param("ii", $quantity, $medicine_id);
            $stmt->execute();
            $stmt->close();

            $conn->commit();

            $message = "Sale completed successfully! Total: Ksh " .
                       number_format($total, 2);

        } catch (Throwable $e) {
            $conn->rollback();
            $message = $e->getMessage();
        }
    }
}

$medicines = $conn->query(
    "SELECT * FROM medicines WHERE quantity > 0 ORDER BY medicine_name"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Sell Medicine</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main" style="margin-left:0">

<h1>Sell Medicine to Customer</h1>

<?php if($message != "") { ?>
<script>
alert(<?php echo json_encode($message); ?>);
</script>
<?php } ?>

<form method="POST">

<label>Customer Name</label>
<input type="text" name="customer_name"
placeholder="Enter customer name" required>

<label>Select Medicine</label>
<select name="medicine_id" required>
<option value="">Choose Medicine</option>

<?php while($row = $medicines->fetch_assoc()) { ?>
<option value="<?php echo $row['id']; ?>">
<?php echo htmlspecialchars($row['medicine_name']); ?>
- Ksh <?php echo number_format($row['price'],2); ?>
(Stock: <?php echo $row['quantity']; ?>)
</option>
<?php } ?>

</select>

<label>Quantity</label>
<input type="number" name="quantity"
min="1" placeholder="Enter quantity" required>

<button type="submit" name="sell">Complete Sale</button>

</form>

<br>
<a href="index.php">Back to Dashboard</a>

</div>
</body>
</html>
