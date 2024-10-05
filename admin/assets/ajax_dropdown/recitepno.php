<?php
session_start();
require("config.php");

// Initialize variables
$branch = $_POST['rdate_data'];
$new_receipt_no = 0;

// Fetch the last receipt number from the database  
$sql = "SELECT receipt_no, DATE(receipt_date) as receipt_date FROM receipts ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_receipt_no = (int) $row['receipt_no']; // Cast to integer
        $last_receipt_date = $row['receipt_date'];  // Get the date of the last receipt

        // Determine the current year and start date for the fiscal year
        $currentYear = date('Y');
        $startDate = "$currentYear-04-01";  // Fiscal year start date

        // Check if the last receipt date matches the start date
        if ($branch == $startDate && $last_receipt_date != $startDate) {
            // Reset the receipt number to 1 if it's the first receipt of the fiscal year
            $new_receipt_no = 1;
        } else {
            // Continue incrementing the receipt number
            $new_receipt_no = $last_receipt_no + 1;
        }
    } else {
        // Start with receipt number 1 if no receipts exist in the database
        $new_receipt_no = 1;
    }
} else {
    // Handle query error
    echo 'Database query failed.';
    exit;
}

// Output the new receipt number
echo $new_receipt_no;
?>