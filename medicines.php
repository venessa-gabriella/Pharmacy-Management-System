
<?php
require 'config.php';

$message = "";

// Medicine categories
$categories = [
    "Painkillers",
    "Antibiotics",
    "Antimalarials",
    "Antihistamines",
    "Cough and Cold",
    "Vitamins",
    "Antacids",
    "Antifungals",
    "Eye Medicines",
    "Skin Medicines",
    "Diabetes Medicines",
    "Blood Pressure Medicines",
    "Rehydration",
    "Other"
];

// ADD OR UPDATE MEDICINE
if (isset($_POST['save'])) {

    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['medicine_name']);
    $category = $_POST['category'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $expiry = $_POST['expiry_date'] ?: null;

    if (
        $name == "" ||
        !in_array($category, $categories, true) ||
        $quantity < 0 ||
        $price < 0
    ) {
        $message = "Please enter valid medicine details.";
    } else {

        if ($id > 0) {

            $stmt = $conn->prepare(
                "UPDATE medicines SET
                medicine_name=?, category=?, quantity=?,
                price=?, expiry_date=? WHERE id=?"
            );

            $stmt->bind_param(
                "ssidsi",
                $name, $category, $quantity,
                $price, $expiry, $id
            );

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO medicines
                (medicine_name, category, quantity, price, expiry_date)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssids",
                $name, $category, $quantity, $price, $expiry
            );
        }

        if ($stmt->execute()) {
            $message = $id > 0
                ? "Medicine updated successfully!"
                : "Medicine added successfully!";
        } else {
            $message = "Error saving medicine: " . $stmt->error;
        }

        $stmt->close();
    }
}

// DELETE MEDICINE
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare(
        "DELETE FROM medicines
         WHERE id=? AND id NOT IN
         (SELECT medicine_id FROM orders)"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = $stmt->affected_rows > 0
            ? "Medicine deleted successfully!"
            : "Medicine cannot be deleted because it has orders.";
    }

    $stmt->close();
}

// EDIT MEDICINE
$edit = null;

if (isset($_GET['edit'])) {

    $id = (int)$_GET['edit'];

    $stmt = $conn->prepare(
        "SELECT * FROM medicines WHERE id=?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $edit = $stmt->get_result()->fetch_assoc();

    $stmt->close();
}

// DISPLAY MEDICINES
$result = $conn->query(
    "SELECT * FROM medicines ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Medicines</title>
    <link rel="stylesheet" href="style.css">

    <style>
        select {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: white;
        }

        .action {
            padding: 7px 12px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }

        .edit {
            background: #078b75;
        }

        .delete {
            background: #dc3545;
        }
    </style>
</head>

<body>

<div class="main" style="margin-left:0">

    <h1>💊 Medicine Management</h1>

    <?php if ($message != "") { ?>
        <script>
            alert(<?php echo json_encode($message); ?>);
        </script>
    <?php } ?>

    <h2>
        <?php echo $edit ? "Update Medicine" : "Add New Medicine"; ?>
    </h2>

    <form method="POST">

        <input type="hidden" name="id"
        value="<?php echo $edit['id'] ?? 0; ?>">

        <label>Medicine Name</label>
        <input type="text" name="medicine_name"
        placeholder="Enter medicine name" required
        value="<?php echo htmlspecialchars($edit['medicine_name'] ?? ''); ?>">

        <label>Medicine Category</label>

        <select name="category" required>
            <option value="">-- Select Category --</option>

            <?php foreach ($categories as $cat) { ?>

                <option value="<?php echo htmlspecialchars($cat); ?>"
                <?php
                echo (($edit['category'] ?? '') == $cat)
                    ? 'selected'
                    : '';
                ?>>

                    <?php echo htmlspecialchars($cat); ?>

                </option>

            <?php } ?>

        </select>

        <label>Quantity in Stock</label>
        <input type="number" name="quantity"
        placeholder="Enter quantity" min="0" required
        value="<?php echo $edit['quantity'] ?? ''; ?>">

        <label>Price (Ksh)</label>
        <input type="number" name="price"
        placeholder="Enter price" step="0.01" min="0" required
        value="<?php echo $edit['price'] ?? ''; ?>">

        <label>Expiry Date</label>
        <input type="date" name="expiry_date"
        value="<?php echo $edit['expiry_date'] ?? ''; ?>">

        <button type="submit" name="save">
            <?php echo $edit ? "Update Medicine" : "Add Medicine"; ?>
        </button>

        <?php if ($edit) { ?>
            <a href="medicines.php">Cancel</a>
        <?php } ?>

    </form>

    <h2>Available Medicines</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Medicine</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Expiry Date</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>

        <tr>
            <td><?php echo $row['id']; ?></td>

            <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>

            <td><?php echo htmlspecialchars($row['category']); ?></td>

            <td><?php echo $row['quantity']; ?></td>

            <td>Ksh <?php echo number_format($row['price'], 2); ?></td>

            <td><?php echo $row['expiry_date'] ?: 'Not set'; ?></td>

            <td>
                <a class="action edit"
                href="?edit=<?php echo $row['id']; ?>">
                    Edit
                </a>

                <a class="action delete"
                href="?delete=<?php echo $row['id']; ?>"
                onclick="return confirm('Are you sure you want to delete this medicine?')">
                    Delete
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

    <br>

    <a href="index.php">Back to Dashboard</a>

</div>

</body>
</html>
