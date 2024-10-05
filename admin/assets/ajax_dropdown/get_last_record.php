<?php
require("config.php");

// Fetch the last record from the receipts table
$sql = "SELECT id, student_prn FROM receipts ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Return the id and student_prn as a JSON object
    echo json_encode(['id' => $row['id'], 'student_prn' => $row['student_prn']]);
} else {
    echo json_encode(['id' => null, 'student_prn' => null]); // Handle if no records are found
}

mysqli_close($con);
?>
