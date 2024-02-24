<?php
session_start();
$order_id = $_GET['order_id'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
date_default_timezone_set('Asia/Kolkata');
$date = date("F j, Y"); // to get the full month name, month without leading 0, year
if (isset($_SESSION['user_details']) && !empty($_SESSION['user_details'])) {

    require("fpdf/fpdf.php");
    ob_start();
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $first_name = $_SESSION['user_details']['first_name'];
    $last_name = $_SESSION['user_details']['last_name'];
    $address = $_SESSION['user_details']['address'];
    $total_amount = $_SESSION['user_details']['Total_amount'];
    $email = $_SESSION['user_details']['email'];
    $payment_method = $_SESSION['user_details']['payment_method'];

    // Display customer details on the right side
    $pdf->Image('logo.png', 10, 5, 35, 25);

    $pdf->Cell(185, 10, "Invoice #" . $order_id, 0, 1, 'R');
    $pdf->Cell(185, 10, "Created: " . $date, 0, 1, 'R');
    $pdf->Cell(0, 10, "", 0, 1); // Add some spacing
    $pdf->Cell(20, 10, "E 25, Sector 75", 0, 0, 'L');
    $pdf->Cell(0, 10, $first_name . " " . $last_name, 0, 1, 'R');
    $pdf->Cell(10, 10, "Noida", 0, 0, 'L');
    $pdf->Cell(0, 10, $address, 0, 1, 'R');
    $pdf->Cell(30, 10, "UP, 201301, India", 0, 0, 'L');
    $pdf->Cell(0, 10, $email, 0, 1, 'R');

    $pdf->Cell(0, 10, "", 0, 1); // Add some spacing
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200, 220, 255);
    $pdf->Rect(10, $pdf->GetY(), 190, 10, 'F');
    $pdf->Cell(50, 10, "Payment Method", 0, 0, 'L');
    $pdf->Cell(0, 10,  $payment_method, 0, 1, 'R');
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Cell(0, 10, "", 0, 1);

    $pdf->SetFillColor(200, 220, 255); // Light blue background
    $pdf->Rect(10, $pdf->GetY(), 190, 10, 'F');
    // Display product details header
    $pdf->Cell(110, 10, "Product", 0, 0, 'C',);
    $pdf->Cell(30, 10, "Price", 0, 0, 'C',);
    $pdf->Cell(15, 10, "Quantity", 0, 0, 'C',);
    $pdf->Cell(35, 10, "Total", 0, 1, 'R',);

    // Reset background color and font for the rest of the content
    $pdf->SetFillColor(255, 255, 255); // White background
    $pdf->SetFont('Arial', '', 12); // Regular font, size 12

    foreach ($_SESSION['cart'] as $key => $value) {
        $product_name = $value['Product_name'];
        $product_quantity = $value['Quantity'];
        $product_price_per_item = $value['Price'];
        $amt = $product_quantity * $product_price_per_item;

        // Display product details
        $pdf->Cell(110, 10, $product_name, 0, 0, 'L');
        $pdf->Cell(30, 10, "$" . number_format($product_price_per_item, 2), 0, 0, 'C');
        $pdf->Cell(15, 10, $product_quantity, 0, 0, 'C');
        $pdf->Cell(35, 10, "$" . number_format($amt, 2), 0, 1, 'R');
    }

    $pdf->Cell(140, 10, '', 0, 0);
    $pdf->Cell(15, 10, 'Total:', 0, 0, 'R');
    $pdf->Cell(35, 10, "$" . number_format($total_amount, 2), 0, 1, 'R');
    ob_clean();
    $pdf->Output('D', 'invoice.pdf');
}
