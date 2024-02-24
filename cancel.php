<?php

include_once 'dbConnect.php';
foreach ($_SESSION['cart'] as $key => $value) {
    $product_id = $value['Product_id'];
}
session_destroy();
?>

<h1 class="error">Your PayPal Transaction has been Canceled</h1>
<a href="index.php" class="btn-link">Back to Home</a>