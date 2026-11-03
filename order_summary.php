
<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user'])) {
    header("Location: index.php?Message=Login To Continue");
    exit();
}

include "dbconnect.php";

$customer = $_SESSION['user'];

// Get order ID from URL
if (!isset($_GET['order_id'])) {
    die("Invalid Order ID");
}

$order_id = intval($_GET['order_id']);

if ($order_id <= 0) {
    die("Invalid Order ID");
}

// Fetch order details
$query = "SELECT * FROM book_orders
          WHERE order_id = ? AND customer = ?";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "is", $order_id, $customer);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    die("Order not found!");
}

// Fetch individual book details
$query = "SELECT * FROM book_order_items
          WHERE order_id = ?";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Order Summary</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">

    <style>
        body {
            background: #f5f5f5;
            padding: 40px 0;
        }

        .summary-box {
            background: white;
            padding: 30px;
            margin-top: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .heading {
            color: #D67B22;
            text-align: center;
            margin-bottom: 25px;
        }

        .success {
            color: green;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .table th {
            background: #D67B22;
            color: white;
        }

        .total {
            color: #D67B22;
            font-size: 22px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="summary-box">

        <h2 class="heading">ORDER SUMMARY</h2>

        <p class="success">
            Order Placed Successfully!
        </p>

        <hr>

        <h4>Order Details</h4>

        <p>
            <strong>Order ID:</strong>
            <?php echo $order['order_id']; ?>
        </p>

        <p>
            <strong>Customer:</strong>
            <?php echo htmlspecialchars($order['customer']); ?>
        </p>

        <p>
            <strong>Order Date:</strong>
            <?php echo htmlspecialchars($order['order_date'] ?? 'Not Available'); ?>
        </p>

        <hr>

        <h4>Books Purchased</h4>

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Book Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($items as $item) { ?>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($item['book_title']); ?>
                        </td>

                        <td>
                            ₹<?php echo number_format($item['price'], 2); ?>
                        </td>

                        <td>
                            <?php echo $item['quantity']; ?>
                        </td>

                        <td>
                            ₹<?php echo number_format($item['subtotal'], 2); ?>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

        <h3 class="total">
            Total Amount:
            ₹<?php echo number_format($order['total_amount'], 2); ?>
        </h3>

        <hr>

        <div class="text-center">

            <a href="index.php"
               class="btn btn-lg"
               style="background:#D67B22;color:white;">
                Continue Shopping
            </a>

        </div>

    </div>

</div>

</body>
</html>
