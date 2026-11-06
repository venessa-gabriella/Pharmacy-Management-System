
<?php
require 'config.php';

// Dashboard statistics
$medicines = $conn->query(
    "SELECT COUNT(*) AS total FROM medicines"
)->fetch_assoc()['total'];

$orders = $conn->query(
    "SELECT COUNT(*) AS total FROM orders"
)->fetch_assoc()['total'];

$stock = $conn->query(
    "SELECT COALESCE(SUM(quantity),0) AS total FROM medicines"
)->fetch_assoc()['total'];

$sales = $conn->query(
    "SELECT COALESCE(SUM(total_amount),0) AS total FROM orders"
)->fetch_assoc()['total'];

$low = $conn->query(
    "SELECT COUNT(*) AS total FROM medicines WHERE quantity <= 5"
)->fetch_assoc()['total'];

// Search medicines
$search = trim($_GET['search'] ?? '');
$searchResults = null;

if ($search !== '') {
    $stmt = $conn->prepare(
        "SELECT * FROM medicines
         WHERE medicine_name LIKE ? OR category LIKE ?
         ORDER BY medicine_name"
    );

    $term = "%".$search."%";
    $stmt->bind_param("ss", $term, $term);
    $stmt->execute();
    $searchResults = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>MEDINOVA Pharmacy Dashboard</title>

    <link rel="stylesheet" href="style.css">

    <style>

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .search-box {
            display: flex;
            gap: 8px;
            max-width: 450px;
            flex: 1;
        }

        .search-box input {
            margin: 0;
            background: white;
        }

        .search-box button {
            white-space: nowrap;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 10px 18px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(120,145,180,0.12);
        }

        .profile-icon {
            width: 45px;
            height: 45px;
            background: #e5def5;
            color: #7463a8;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
        }

        .profile-info h4 {
            color: #7463a8;
            margin-bottom: 4px;
        }

        .profile-info p {
            font-size: 12px;
        }

        .brand {
            text-align: center;
            margin-bottom: 40px;
            padding: 15px 5px;
        }

        .brand h1 {
            font-size: 27px;
            color: #7463a8;
            letter-spacing: 2px;
        }

        .brand p {
            font-size: 10px;
            color: #77708e;
            letter-spacing: 1px;
        }

        .search-results {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .clear-search {
            display: inline-block;
            margin-top: 12px;
        }

        @media(max-width: 600px) {
            .topbar {
                align-items: stretch;
            }

            .search-box {
                max-width: 100%;
            }
        }

    </style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="brand">

        <h1>💊 MEDINOVA</h1>

        <p>PHARMACY & WELLNESS CENTRE</p>

    </div>

    <a href="index.php">🏠 Dashboard</a>

    <a href="medicines.php">💊 Medicines</a>

    <a href="sell.php">🛒 Sell Medicine</a>

    <a href="orders.php">📋 Customer Orders</a>

</div>


<!-- MAIN CONTENT -->

<div class="main">

    <!-- TOP BAR -->

    <div class="topbar">

        <form method="GET" class="search-box">

            <input
                type="text"
                name="search"
                placeholder="🔍 Search medicine or category..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">Search</button>

        </form>


        <!-- ADMIN PROFILE -->

        <div class="profile">

            <div class="profile-icon">
                👤
            </div>

            <div class="profile-info">

                <h4>Pharmacy Admin</h4>

                <p>Administrator</p>

            </div>

        </div>

    </div>


    <!-- DASHBOARD HEADING -->

    <h1>Dashboard Overview</h1>

    <p>Welcome to MEDINOVA Pharmacy Management System.</p>


    <!-- SEARCH RESULTS -->

    <?php if ($search !== '') { ?>

        <div class="search-results">

            <h2>Medicine Search Results</h2>

            <p>
                Results for:
                <strong><?php echo htmlspecialchars($search); ?></strong>
            </p>

            <table>

                <tr>
                    <th>Medicine</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>

                <?php if ($searchResults->num_rows > 0) { ?>

                    <?php while($row = $searchResults->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($row['medicine_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['category']); ?>
                            </td>

                            <td>
                                <?php echo $row['quantity']; ?>
                            </td>

                            <td>
                                Ksh <?php echo number_format($row['price'],2); ?>
                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="4">
                            No matching medicines found.
                        </td>
                    </tr>

                <?php } ?>

            </table>

            <a href="index.php" class="clear-search">
                Clear Search
            </a>

        </div>

    <?php } ?>


    <!-- DASHBOARD CARDS -->

    <div class="cards">

        <div class="card">
            <h3>💊 Total Medicines</h3>
            <h2><?php echo $medicines; ?></h2>
        </div>


        <div class="card">
            <h3>📦 Total Stock Units</h3>
            <h2><?php echo $stock; ?></h2>
        </div>


        <div class="card">
            <h3>📋 Total Orders</h3>
            <h2><?php echo $orders; ?></h2>
        </div>


        <div class="card">
            <h3>💰 Total Sales</h3>
            <h2>
                Ksh <?php echo number_format($sales,2); ?>
            </h2>
        </div>


        <div class="card warning">
            <h3>⚠️ Low Stock Medicines</h3>
            <h2><?php echo $low; ?></h2>
        </div>

    </div>


    <!-- RECENT ORDERS -->

    <h2>📋 Recent Customer Orders</h2>

    <table>

        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Medicine ID</th>
            <th>Quantity</th>
            <th>Total Amount</th>
            <th>Date</th>
        </tr>

        <?php

        $result = $conn->query(
            "SELECT * FROM orders
             ORDER BY id DESC LIMIT 10"
        );

        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td><?php echo $row['id']; ?></td>

            <td>
                <?php echo htmlspecialchars($row['customer_name']); ?>
            </td>

            <td><?php echo $row['medicine_id']; ?></td>

            <td><?php echo $row['quantity']; ?></td>

            <td>
                Ksh <?php echo number_format($row['total_amount'],2); ?>
            </td>

            <td><?php echo $row['order_date']; ?></td>

        </tr>

        <?php

            }

        } else {

        ?>

        <tr>
            <td colspan="6">No customer orders available yet.</td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
