<?php
include 'header.php';
include_once 'db.php';


// Check if PayerID is set in the URL
if (isset($_GET['PayerID'])) {
    $Payer_ID = $_GET['PayerID'];

    // Retrieve customer details from the PayPal data
    $first_name = $_GET['first_name1'];
    $last_name = $_GET['last_name1'];
    $email = $_GET['email'];
    $mobile_num = $_GET['contact_no'];
    $address = $_GET['address_line_1'];
    $city = $_GET['city'];
    $state = $_GET['state'];
    $country = $_GET['country'];
    $payment_method = $_GET['payment_method'];
    date_default_timezone_set('Asia/Kolkata');

    // Start a transaction
    mysqli_begin_transaction($con);

    $success = true;

    foreach ($_SESSION['cart'] as $key => $value) {
        $product_id = $value['Product_id'];
        $product_name = $value['Product_name'];
        $product_quantity = $value['Quantity'];
        $product_price_per_item = $value['Price'];
        $amt = $product_quantity * $product_price_per_item;

        // Insert order details into the orders table
        $order_query = "INSERT INTO orders (product_id, order_amount, first_name, last_name, mobile_no, email, address, city, state, country) 
                        VALUES ('$product_id', '$amt', '$first_name', '$last_name', '$mobile_num', '$email', '$address', '$city', '$state', '$country')";

        if (!mysqli_query($con, $order_query)) {
            $success = false;
            break; // Exit the loop if order query fails
        }

        $order_id = mysqli_insert_id($con); // Get the last inserted order ID

        // Insert order product details
        $order_product_query = "INSERT INTO order_product (product_id, product_name, product_quantity, total_amount, order_id, email, first_name, last_name) 
                                VALUES ('$product_id', '$product_name', '$product_quantity', '$amt', '$order_id', '$email', '$first_name', '$last_name')";

        if (!mysqli_query($con, $order_product_query)) {
            $success = false;
            break; // Exit the loop if order product query fails
        }

        // Insert payment details into payment_details table
        $payment_id = generatePaymentId();
        $payee_id = $Payer_ID;
        $payer_fname = $first_name;
        $payer_lname = $last_name;
        $payer_email = $email;
        $txn_id = $_GET['txn_id'];
        $mc_currency = $_GET['mc_currency'];
        $mc_fee = $_GET['mc_fee'];
        $mc_gross = $_GET['mc_gross'];
        $payment_status = $_GET['payment_status'];
        $timestamp = date('Y-m-d H:i:s');

        $payment_details_query = "INSERT INTO payment_details(payment_id, payee_id, payer_fname, payer_lname, payer_email, order_id, product_id, product_name, amount, status, payment_method, created_at) 
                 VALUES ('$payment_id', '$payee_id', '$payer_fname', '$payer_lname', '$payer_email', '$order_id', '$product_id', '$product_name', '$amt', '$payment_status', '$payment_method', '$timestamp')";

        if (!mysqli_query($con, $payment_details_query)) {
            $success = false;
            break; // Exit the loop if payment details query fails
        }
    }

    if ($success) {
        // Commit the transaction
        mysqli_commit($con);
        $_SESSION['user_details'] = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'address' => $address,
            'payment_method' => $payment_method,
            'Total_amount' => $mc_gross
        );

        // Display success message
        echo "<h1>Your Payment has been successful</h1><br>";
        echo "<h2>Order details, order product details, and payment details inserted successfully.</h2>";
    } else {
        // Roll back the transaction if any query failed
        mysqli_rollback($con);
        echo "<h1>Your Payment has been failed</h1>";
    }

    // Display payment details in a Bootstrap table
    echo '<div class="container mt-5">';
    echo '<h2>Payment Details</h2>';
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col">Product Name</th>';
    echo '<th scope="col">Quantity</th>';
    echo '<th scope="col">Amount</th>';
    echo '<th scope="col">Order ID</th>';
    echo '<th scope="col">Transaction ID</th>';
    echo '<th scope="col">Payment Status</th>';
    echo '<th scope="col">Payee ID</th>';
    echo '<th scope="col">Payer ID</th>';
    echo '<th scope="col">Total Amount</th>';
    echo '<th scope="col">Payment Method</th>';
    echo '<th scope="col">Timestamp</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($_SESSION['cart'] as $key => $value) {
        $product_name = $value['Product_name'];
        $product_quantity = $value['Quantity'];
        $product_price_per_item = $value['Price'];
        $amt = $product_quantity * $product_price_per_item;

        echo '<tr>';
        echo '<td>' . $product_name . '</td>';
        echo '<td>' . $product_quantity . '</td>';
        echo '<td>' . $product_price_per_item . '</td>';
        echo '<td>' . $order_id . '</td>';
        echo '<td>' . $txn_id . '</td>';
        echo '<td>' . $payment_status . '</td>';
        echo '<td>' . $payee_id . '</td>';
        echo '<td>' . $Payer_ID . '</td>';
        echo '<td>' . $amt . '</td>';
        echo '<td>' . $payment_method  . '</td>';
        echo '<td>' . $timestamp  . '</td>';
        echo '</tr>';
    }
    echo '<tr>';
    echo '<td colspan="8"></td>';
    echo '<td>Total:</td>';
    echo '<td>' . $mc_gross . '</td>';
    echo '<td></td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo "<h1>Your Payment has been failed</h1>";
}




function generatePaymentId()
{
    return uniqid('PAY');
}
?>
<a href="index.php" class="btn btn-primary">Back to Home</a>
<a href="send_mail.php?order_id=<?php echo $order_id; ?>&action=email" name=" Email" class="btn btn-info">Email</a>

<a href="invoice.php?order_id=<?php echo $order_id; ?>&action=download" class=" btn btn-success">Download Invoice</a>