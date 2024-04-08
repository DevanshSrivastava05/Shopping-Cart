<?php
// Include the database connection file
include 'db.php';
session_start();

// Retrieve form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$Email = $_POST['email'];
$Mobile_num = $_POST['contact_no'];
$Address = $_POST['address_line_1'];
$City = $_POST['city'];
$Zip = $_POST['pincode'];
$State = $_POST['state'];
$Country = $_POST['country'];
$payment_method = $_POST['payment_method'];

// Retrieve product-related form data (assuming arrays are posted)
$product_ids = $_POST['product_id'];
$product_names = $_POST['product_name'];
$product_quantities = $_POST['product_quantity'];
$product_price_per_item = $_POST['product_price_per_item'];


// Function to handle PayPal payment
function processPayPalPayment($con, $first_name, $last_name, $Email, $Mobile_num, $Address, $City, $Zip, $State, $Country, $product_ids, $product_names, $product_quantities, $product_price_per_item, $payment_method)
{
    // Define PayPal configuration constants
    define('PAYPAL_ID', 'sb-mcfem26006310@business.example.com');
    define('PAYPAL_SANDBOX', true); // Test Mode
    define('PAYPAL_RETURN_URL', 'http://localhost/Shopping-cart/success.php');
    define('PAYPAL_CANCEL_URL', 'http://localhost/Shopping-cart/cancel.php');
    define('PAYPAL_CURRENCY', 'USD');
    define('PAYPAL_URL', (PAYPAL_SANDBOX == true) ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr");

    // Prepare common data for PayPal
    $paypal_data = array(
        'cmd' => '_cart',
        'upload' => '1',
        'business' => PAYPAL_ID,
        'lc' => 'US',
        'currency_code' => PAYPAL_CURRENCY,
        'return' => PAYPAL_RETURN_URL . '?' . http_build_query(array(
            'first_name1' => $first_name,
            'last_name1' => $last_name,
            'email' => $Email,
            'contact_no' => $Mobile_num,
            'address_line_1' => $Address,
            'city' => $City,
            'pincode' => $Zip,
            'state' => $State,
            'country' => $Country,
            'payment_method' => $payment_method,
        )),
        'cancel_return' => PAYPAL_CANCEL_URL,
    );

    // Loop through the products
    foreach ($product_ids as $key => $current_product_id) {
        $current_product_name = $product_names[$key];
        $current_product_quantity = $product_quantities[$key];
        $current_product_price = $product_price_per_item[$key];

        // Include product details in the PayPal data
        $paypal_data["item_name_" . ($key + 1)] = $current_product_name;
        $paypal_data["item_number_" . ($key + 1)] = $current_product_id;
        $paypal_data["quantity_" . ($key + 1)] = $current_product_quantity;
        $paypal_data["amount_" . ($key + 1)] = $current_product_price;
    }

    $redirect_url = PAYPAL_URL . '?' . http_build_query($paypal_data);
    header('Location: ' . $redirect_url);
    exit();
}

function calculateTotalAmount($productDetails)
{
    // Initialize the total amount
    $totalAmount = 0;

    // Loop through each product and calculate the total amount
    foreach ($productDetails['ids'] as $key => $current_product_id) {
        $quantity = $productDetails['quantities'][$key];
        $price = $productDetails['prices'][$key];

        // Calculate the total amount for the current product
        $productTotal = $quantity * $price;

        // Add the product total to the overall total amount
        $totalAmount += $productTotal;
    }

    return $totalAmount;
}

// Loop through each product and process payment
foreach ($product_ids as $key => $current_product_id) {
    $current_product_name = $product_names[$key];
    $current_product_quantity = $product_quantities[$key];
    $current_product_price = $product_price_per_item[$key];
    $amt = $current_product_price * $current_product_quantity;


    // Call the payment processing function for each product
    if ($payment_method === 'paypal') {
        // Pass arrays of product details to the function
        processPayPalPayment($con, $first_name, $last_name, $Email, $Mobile_num, $Address, $City, $Zip, $State, $Country, $product_ids, $product_names, $product_quantities, $product_price_per_item, $payment_method);
    }
}
