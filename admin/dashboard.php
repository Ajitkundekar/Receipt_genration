<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
$error = "";
$msg = "";

// Fetch all tables from the database
$tables_result = $con->query("SHOW TABLES");
$tables = [];
if ($tables_result) {
    while ($row = $tables_result->fetch_array()) {
        $table_name = $row[0];
        // Skip tables that start with admin, students, subjects, or teacher
        if (preg_match('/^(admin|students|subjects|teacher)/i', $table_name) === 0) {
            $tables[] = $table_name;
        }
    }
}

// Prepare and execute the statement to get total amount collected for each reason
$stmt = $con->prepare("SELECT reason, SUM(amount) AS total_collected 
                       FROM receipts 
                       GROUP BY reason");
$stmt->execute();
$collections_result = $stmt->get_result();
$collections = [];
if ($collections_result) {
    while ($row = $collections_result->fetch_assoc()) {
        $collections[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Amount Received by Reason</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Side-Nav -->
    <?php include("header.php"); ?>

    <!-- Main Wrapper -->
    <div class="p-3 my-container active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-0" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->
        <div class="container" style="margin-left: 10px;">
            <br>
            <div class="card shadow card-body">
                <h2>Amount Received by collections</h2>
                <div class="row">
                    <?php if (count($collections) > 0): ?>
                        <?php foreach ($collections as $collection): ?>
                            <div class="col-xl-3 col-sm-6 col-lg-3" style="margin-top: 20px;">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dash-widget-header ">
                                            <span class="dash-widget-icon">
                                                <i class="fa-solid fa-money-bill-wave" style='font-size:20px;'></i>
                                            </span>
                                        </div>
                                        <div class="dash-widget-info">
                                            <h3><?php echo number_format($collection['total_collected'], 2); ?></h3>
                                            <h6 class="text-muted"><?php echo htmlspecialchars($collection['reason']); ?></h6>
                                            <br><br>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-success w-75"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No data found in the receipts table.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

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
