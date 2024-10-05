<?php
session_start();  // Start session to access session variables
$_SESSION['student_prn'] = $_GET['prn'];
$_SESSION['id'] = $_GET['id'];

require("config.php");
require './assets/fpdf/fpdf.php';
require './assets/PHPMailer/vendor/autoload.php';  // Include PHPMailer library

// Check if student PRN is set in session
if (isset($_SESSION['student_prn']) && isset($_SESSION['id'])) {
    // Handle Student Wise Report logic
    $student_prn = $_SESSION['student_prn'];
    $id = $_SESSION['id'];

    // Prepare and execute query to get receipt data based on PRN
    $stmt = $con->prepare("SELECT r.*,s.email, s.s_name as student_name FROM receipts r 
                           JOIN student_data s ON r.student_prn = s.prn 
                           WHERE r.student_prn=? AND r.id=?");
    $stmt->bind_param('ss', $student_prn, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the latest receipt details
        $row = $result->fetch_assoc();

        $student_name = $row['student_name'];
        $amount = $row['amount'];
        $amount_in_words = $row['amount_in_words'];
        $receipt_no = $row['receipt_no'];
        $payment_method = $row['payment_mode'];
        $date = $row['receipt_date'];
        $cheque_no = $row['cheque_no'];
        $cheque_date = $row['cheque_date'];
        $bank_name = $row['bank_name'];
        $reason = $row['reason'];
        $transaction_id = $row['transaction_id'];
        $transaction_date = $row['transaction_date'];
        $email = $row['email'];
    } else {
        $error = "No receipt data found for the provided PRN.";
    }
} else {
    $error = "Student PRN not found.";
}

// Default values if session or DB values are not available
$student_name = isset($student_name) ? $student_name : 'Unknown';
$amount = isset($amount) ? $amount : '0.00';
$amount_in_words = isset($amount_in_words) ? $amount_in_words : 'Null';
$receipt_no = isset($receipt_no) ? $receipt_no : 'N/A';
$payment_method = isset($payment_method) ? $payment_method : 'cash';
$date = isset($date) ? date('d-m-Y', strtotime($date)) : date('d-m-Y');
$cheque_no = isset($cheque_no) ? $cheque_no : 'N/A';
$cheque_date = isset($cheque_date) ? date('d-m-Y', strtotime($cheque_date)) : date('d-m-Y');
$bank_name = isset($bank_name) ? $bank_name : 'N/A';
$reason = isset($reason) ? $reason : 'N/A';
$transaction_date = isset($transaction_date) ? $transaction_date : 'N/A';
$transaction_id = isset($transaction_id) ? $transaction_id : 'N/A';

// Create instance of FPDF class
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Function to generate the receipt (as per your existing logic)
function generateReceipt($pdf, $receipt_no, $student_name, $amount, $amount_in_words, $date, $payment_method, $cheque_no, $cheque_date, $bank_name, $transaction_date, $transaction_id, $reason, $offsetY = 0)
{
    // Starting Y position
    $currentY = 10 + $offsetY;

    // Set the font for the header
    $pdf->SetFont('Arial', 'B', 12);

    // Add the header logo or image
    $pdf->Image('assets/img/kitlogo.png', 10, $currentY + 10, 40);
    $currentY += 15;

    // Heading Section
    $pdf->SetXY(88, $currentY);
    $pdf->Cell(0, 10, "Kolhapur Institute of Technology's", 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->SetXY(53, $currentY + 7);
    $pdf->SetTextColor(0, 102, 204);  // Set color for text (optional)
    $pdf->Cell(0, 10, 'Institute of Management Education & Research (AUTONOMOUS)', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(0);  // Reset text color
    $pdf->SetXY(93, $currentY + 15);
    $pdf->Cell(0, 7, 'Gokul Shirgaon, Kolhapur. 416 234', 0, 1, 'L');
    $pdf->SetXY(75, $currentY + 20);
    $pdf->Cell(0, 7, 'Tel: +91-9158528383 (Director) 0231-2636266 (Office)', 0, 1, 'L');
    $pdf->SetXY(91, $currentY + 25);
    $pdf->Cell(0, 7, 'Fax: +91-9158528383, 0231-2638881', 0, 1, 'L');
    $currentY += 30;

    // Draw border for the receipt information
    $pdf->Rect(10, $currentY - 35, 190, 120);  // Draw a rectangle
    $pdf->Rect(10, $currentY - -2, 190, 83);  // Draw a rectangle

    // Receipt Number and Date
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetXY(20, $currentY);
    $pdf->Cell(40, 12, 'Receipt No.: ' . $receipt_no, 0, 1, 'L');

    $pdf->SetXY(150, $currentY);
    $pdf->Cell(40, 10, 'Date: ' . $date, 0, 1, 'L');
    $currentY += 10;

    // Received with thanks from:
    $pdf->SetFont('Arial', '', 12);  // Regular font
    $pdf->SetXY(15, $currentY);
    $pdf->Cell(0, 10, 'RECEIVED with thanks from:', 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 12);  // Bold for student name
    $pdf->SetXY(75, $currentY);
    $pdf->Cell(0, 10, $student_name, 0, 1, 'L');
    $currentY += 10;

    $pdf->SetFont('Arial', '', 12);  // Regular font
    $pdf->SetXY(15, $currentY);
    $pdf->Cell(0, 10, 'The sum of rupees:', 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 12);  // Bold for amount in words
    $pdf->SetXY(58, $currentY);
    $pdf->Cell(0, 10, $amount_in_words.'( By'.$payment_method.')', 0, 1, 'L');
    $currentY += 10;

    // Check payment method and display relevant details
    if ($payment_method === 'cheque') {
        // Display Cheque Information
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(15, $currentY);
        $pdf->Cell(50, 10, 'Cheque No.: ');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(45, $currentY);
        $pdf->Cell(0, 10, $cheque_no);
        $currentY += 10;

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(100, $currentY - 10);
        $pdf->Cell(50, 10, 'Date:');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(120, $currentY - 10);
        $pdf->Cell(50, 10, $cheque_date);
        $currentY += 8;

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(15, $currentY - 8);
        $pdf->Cell(50, 10, 'Drawn on:');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(45, $currentY - 8);
        $pdf->Cell(50, 10, $bank_name);
    } elseif ($payment_method === 'online') {
        // Display Online Transaction Information
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(15, $currentY);
        $pdf->Cell(50, 10, 'Transaction ID: ');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(45, $currentY);
        $pdf->Cell(0, 10, $transaction_id);
        $currentY += 10;

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(100, $currentY - 10);
        $pdf->Cell(50, 10, 'Date:');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(120, $currentY - 10);
        $pdf->Cell(50, 10, $transaction_date);
    }

   

    // Additional description
    $pdf->SetFont('Arial', '', 12);  // Regular font
    $pdf->SetXY(15, $currentY);
    $pdf->Cell(50, 10, 'an Amount of:', 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 12);  // Bold for amount in words
    $pdf->SetXY(50, $currentY);
    $pdf->Cell(50, 10, $reason, 0, 1, 'L');
    $currentY += 18;

    // Amount in Rs. Section
    $pdf->SetXY(15, $currentY - 2);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, 'Rs.', 1, 0, 'C');
    $pdf->Cell(30, 10, $amount, 1, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $currentY += 12;

    // Signatures Section
    $pdf->SetXY(125, $currentY - 16);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(60, 10, 'For KIT\'s Institute of Management Education ', 0, 1, 'C');
    $pdf->SetXY(130, $currentY - 12);
    $pdf->Cell(60, 10, '& Research', 0, 1, 'C');
    $currentY += 10;

    // Librarian Signature
    $pdf->SetXY(130, $currentY - 12);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Librarian', 0, 1, 'C');
}

// Generate receipts
generateReceipt($pdf, $receipt_no, $student_name, $amount, $amount_in_words, $date, $payment_method, $cheque_no, $cheque_date, $bank_name, $transaction_date, $transaction_id, $reason);
generateReceipt($pdf, $receipt_no, $student_name, $amount, $amount_in_words, $date, $payment_method, $cheque_no, $cheque_date, $bank_name, $transaction_date, $transaction_id, $reason, 135);

// Output PDF to a string
$pdf_output = $pdf->Output('S');
$pdf_base64 = base64_encode($pdf_output);

// Save the PDF file to the server
$pdf_file = 'receipt.pdf';
$pdf->Output('F', $pdf_file);


// Output PDF as an iframe
echo '<h2>Receipt</h2>';
echo '<iframe src="data:application/pdf;base64,' . $pdf_base64 . '" width="100%" height="100%"></iframe>';
?>
