<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit;
}

// Function to download the database
function downloadDatabase($con)
{
    $tables = [];
    $result = $con->query("SHOW TABLES");
    
    // Fetch all table names
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    
    $sqlScript = "";
    
    foreach ($tables as $table) {
        // Prepare SQL for table structure
        $result = $con->query("SHOW CREATE TABLE $table");
        $row = $result->fetch_row();
        $sqlScript .= "\n\n" . $row[1] . ";\n\n";
        
        // Prepare SQL for table data
        $result = $con->query("SELECT * FROM $table");
        $columnCount = $result->field_count;

        while ($row = $result->fetch_row()) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($i = 0; $i < $columnCount; $i++) {
                $row[$i] = $row[$i] ?? 'NULL'; // If NULL, insert 'NULL'
                $row[$i] = $con->real_escape_string($row[$i]);
                $sqlScript .= isset($row[$i]) ? "'" . $row[$i] . "'" : "''";
                if ($i < ($columnCount - 1)) {
                    $sqlScript .= ', ';
                }
            }
            $sqlScript .= ");\n";
        }
        
        $sqlScript .= "\n";
    }

    // Generate SQL file for download
    $backup_file_name = "database_backup_" . date("Y-m-d_H-i-s") . ".sql";
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename=' . $backup_file_name);
    header('Content-Length: ' . strlen($sqlScript));

    echo $sqlScript;
    exit;
}

// Check if download button is clicked
if (isset($_POST['download'])) {
    downloadDatabase($con);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Download Database</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Include your navigation/header if necessary -->
    <?php include("header.php"); ?>

    <div class="p-3 my-container active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-0" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->

        <!-- Content Section -->
        <div class="container" style="margin-left: 10px;">
            <!-- Add the Backup Button -->
            <!-- Export all tables to Excel -->
            <div class="container mt-5">
        <h3>Download Entire Database</h3>
        <p>Click the button below to download the entire database in SQL format.</p>
        <form method="POST">
            <button type="submit" name="download" class="btn btn-primary">Download Database</button>
        </form>
    </div>

            <!-- Collection data display -->

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
     <!-- bootstrap js -->
     <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/style.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>