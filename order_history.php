
<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

include "dbconnect.php";

$customer = $_SESSION['user'];

$query = "SELECT * FROM book_orders
          WHERE customer = ?
          ORDER BY order_id DESC";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $customer);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">

    <style>
        body {
            background: #f5f5f5;
            padding-top: 100px;
        }

        .heading {
            color: #D67B22;
            text-align: center;
        }

        .panel-heading {
            background: #D67B22 !important;
            color: white !important;
        }

        .order-box {
            background: white;
            padding: 20px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2 class="heading">MY ORDER SUMMARY</h2>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <?php while ($order = mysqli_fetch_assoc($result)) { ?>

            <div class="order-box">

                <h4>Order #<?php echo $order['order_id']; ?></h4>

                <p>
                    <b>Customer:</b>
                    <?php echo htmlspecialchars($order['customer']); ?>
                </p>

                <p>
                    <b>Order Date:</b>
                    <?php echo htmlspecialchars($order['order_date']); ?>
                </p>

                <p>
                    <b>Total Amount:</b>
                    ₹<?php echo number_format($order['total_amount'], 2); ?>
                </p>

                <a href="order_summary.php?order_id=<?php echo $order['order_id']; ?>"
                   class="btn"
                   style="background:#D67B22;color:white;">
                    View Order Details
                </a>

            </div>

        <?php } ?>

    <?php } else { ?>

        <h4 class="text-center">
            You have not placed any orders yet.
        </h4>

    <?php } ?>

    <div class="text-center">
        <a href="index.php" class="btn btn-warning">
            Continue Shopping
        </a>
    </div>

</div>

</body>
</html>
