<?php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['Add_To_Cart'])) {
        if (isset($_SESSION['cart'])) {
            $product_name = $_POST['product_name'];

            // Check if the product is already in the cart
            $existing_item_key = array_search($product_name, array_column($_SESSION['cart'], 'Product_name'));

            if ($existing_item_key !== false) {
                // Product already in the cart, increase quantity by 1
                $_SESSION['cart'][$existing_item_key]['Quantity'] += 1;
                echo "<script>
                    alert('Item Already Added');
                    window.location.href=('cart.php');
                </script>";
            } else {
                // Product not in the cart, add it
                $count = count($_SESSION['cart']);
                $_SESSION['cart'][$count] = array(
                    'Product_id' => $_POST['product_id'],
                    'Product_name' => $_POST['product_name'],
                    'Price' => $_POST['product_price'],
                    'Quantity' => 1
                );

                echo "<script>
                    alert('Item Added');
                    window.location.href=('cart.php');
                </script>";
            }
        } else {
            // Cart is empty, add the product
            $_SESSION['cart'][0] = array(
                'Product_id' => $_POST['product_id'],
                'Product_name' => $_POST['product_name'],
                'Price' => $_POST['product_price'],
                'Quantity' => 1
            );
            echo "<script>
                alert('Item Added');
                window.location.href=('cart.php');
            </script>";
        }
    }
}
