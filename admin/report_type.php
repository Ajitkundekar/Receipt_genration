<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit();
}

$error = "";
$msg = "";

if (isset($_POST['submit_date_report'])) {
    // Handle Date Wise Report logic
    $_SESSION['start_date'] = $_POST['start_date'];
    $_SESSION['end_date'] = $_POST['end_date'];
    header("Location: Report_date_wise.php");
    exit();
} elseif (isset($_POST['submit_student_report'])) {
    // Handle Student Wise Report logic
    $_SESSION['student_prn'] = $_POST['student_prn'];
    header("Location: Report_student_wise.php");
    exit();
} elseif (isset($_POST['submit_collection_report'])) {
    // Handle Collection Head-wise Report logic
    $_SESSION['start_date_ch'] = $_POST['start_date_ch'];
    $_SESSION['end_date_ch'] = $_POST['end_date_ch'];
    $_SESSION['collection_head'] = $_POST['collection_head'];
    header("Location: Report_collection_wise.php");
    exit();
}elseif (isset($_POST['submit_major_report'])) {
    // Handle Date Wise Report logic
    $_SESSION['start_date'] = $_POST['start_date_'];
    $_SESSION['end_date'] = $_POST['end_date_'];
    header("Location: report_major.php");
    exit();
}

// Fetch all tables from the database
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bootstrap 5 Side Bar Navigation</title>
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
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-0" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>

        <div class="container" style="margin-left: 10px;">
            <br>
            <div class="card shadow card-body">
                <h2>Select the report type </h2>

                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Date wise Report </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                            aria-selected="false">Student-Wise Report </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                            aria-selected="false">Collection Head-wise Report</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-major-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-major" type="button" role="tab" aria-controls="pills-major"
                            aria-selected="false">Major report</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <!-- Date Wise Report -->
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="card card-body">
                            <form action="" method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-2 text-center my-auto">
                                        <h5>Start Date:</h5>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" id="start_date" class="form-control form-control-lg" name="start_date" required>
                                    </div>

                                    <div class="col-md-2 text-center my-auto">
                                        <h5>End Date:</h5>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" id="end_date" class="form-control form-control-lg" name="end_date" required>
                                    </div>

                                    <div class="col-md-4 my-auto">
                                        <button type="submit" name="submit_date_report" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Student-Wise Report -->
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="card card-body">
                            <form action="" method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-2 text-center my-auto">
                                        <h5>Student PRN:</h5>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" id="student_prn" class="form-control form-control-lg" name="student_prn" placeholder="PRN" required>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <button type="submit" name="submit_student_report" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Collection Head-wise Report -->
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <div class="card card-body">
                            <form action="" method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-2 text-center my-auto">
                                        <h5>Start Date:</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" id="start_date_ch" class="form-control form-control-lg" name="start_date_ch" required>
                                    </div>

                                    <div class="col-md-2 text-center my-auto">
                                        <h5>End Date:</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" id="end_date_ch" class="form-control form-control-lg" name="end_date_ch" required>
                                    </div>
                                    <div class="col-md-2 text-left my-auto">
                                    </div>
                                    <br><br><br>
                                    <div class="col-md-2 text-left my-auto">
                                        <h5>Collection Head:</h5>
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-control form-control-lg" name="collection_head" required>
                                            <option value="">--Select--</option>
                                            <option value="Late Charges">Late Charges</option>
                                            <option value="Book-Bank Fees">Book-Bank Fees</option>
                                            <option value="Cost Recovered">Cost Recovered</option>
                                            <option value="Book-Bank Deposit">Book-Bank Deposit</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 my-auto">
                                        <button type="submit" name="submit_collection_report" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- //major report -->
                    <div class="tab-pane fade" id="pills-major" role="tabpanel" aria-labelledby="pills-major-tab">
                        <div class="card card-body">
                            <form action="" method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-2 text-center my-auto">
                                        <h5>Start Date:</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" id="start_date_" class="form-control form-control-lg" name="start_date_" required>
                                    </div>

                                    <div class="col-md-2 text-center my-auto">
                                        <h5>End Date:</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" id="end_date_" class="form-control form-control-lg" name="end_date_" required>
                                    </div>
                                    
                                    

                                    <div class="col-md-2 my-auto">
                                        <button type="submit" name="submit_major_report" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JS Files -->
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
