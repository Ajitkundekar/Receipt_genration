
<?php
// Include database configuration
require("config.php");

if (isset($_POST['prn_data'])) {
    $prn = mysqli_real_escape_string($con, $_POST['prn_data']);
    
    // Query to fetch student name based on PRN
    $query = "SELECT s_name FROM student_data WHERE prn = '$prn'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['s_name']; // Output the student's name
    } else {
        echo "Student not found"; // Handle case where PRN does not match any record
    }
} else {
    echo "Invalid request";
}
?>
