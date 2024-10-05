<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit();
}

$error = "";
$msg = "";

if (isset($_SESSION['start_date_ch']) && isset($_SESSION['end_date_ch']) && isset($_SESSION['collection_head'])) {
    $start_date = $_SESSION['start_date_ch'];
    $end_date = $_SESSION['end_date_ch'];
    $reason = $_SESSION['collection_head'];

    // Prepare and execute query to get date-wise report data
    $stmt = $con->prepare("SELECT r.*, s.s_name as student_Name FROM receipts r 
                           JOIN student_data s ON r.student_prn = s.prn 
                           WHERE r.receipt_date BETWEEN ? AND ? AND r.reason = ?");
    $stmt->bind_param('sss', $start_date, $end_date, $reason);
    $stmt->execute();
    $query = $stmt->get_result();
} else {
    $error = "Please select a valid date range.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Date Wise Report start_date:<?php echo $start_date."  " ?>End Date:<?php echo $end_date."  " ?>collection:<?php echo $reason."  " ?> </title>
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
                <h2>Report_collection_wise</h2>
                <h5>
                    <label for="">Starting date: <?php echo date('d-m-Y', strtotime($start_date)); ?></label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="">Ending date: <?php echo date('d-m-Y', strtotime($end_date)); ?></label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="">Collection TYPE : <?php echo $reason; ?></label>
                </h5>
                <div class="row card card-body shadow ">
                    <div class="col-12">
                        <div class="data_table">
                            <?php if (!empty($error)) { ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                            <?php } ?>
                            <?php if ($query && $query->num_rows > 0) { ?>
                                <table id="example" class="table table-striped table-responsive table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Receipt No</th>
                                            <th>Receipt Date</th>
                                            <th>Student Name</th>
                                            <?php if ($reason == "Late Charges") { ?>
                                                <th>Late Charges</th>
                                            <?php } ?>
                                            <?php if ($reason == "Book-Bank Fees") { ?>
                                                <th>Book-Bank Fees</th>
                                            <?php } ?>
                                            <?php if ($reason == "Cost Recovered") { ?>
                                                <th>Cost Recovered</th>
                                            <?php } ?>
                                            <?php if ($reason == "Book-Bank Deposit") { ?>
                                                <th>Book-Bank Deposit</th>
                                            <?php } ?>
                                            <?php if ($reason == "Other") { ?>
                                                <th>Other</th>
                                            <?php } ?>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Initialize totals
                                        $total_late_charges = 0;
                                        $total_book_bank_fees = 0;
                                        $total_cost_recovered = 0;
                                        $total_Book_Bank_Deposit = 0;
                                        $total_other = 0;

                                        $cnt = 1;
                                        while ($row = $query->fetch_assoc()) {
                                            // Initialize amounts for each row
                                            $late_charges = 0;
                                            $book_bank_fees = 0;
                                            $cost_recovered = 0;
                                            $Book_Bank_Deposit = 0;
                                            $other = 0;

                                            // Assign amount based on reason
                                            switch ($row['reason']) {
                                                case "Late Charges":
                                                    $late_charges = $row['amount'];
                                                    $total_late_charges += $late_charges;
                                                    break;
                                                case "Book-Bank Fees":
                                                    $book_bank_fees = $row['amount'];
                                                    $total_book_bank_fees += $book_bank_fees;
                                                    break;
                                                case "Cost Recovered":
                                                    $cost_recovered = $row['amount'];
                                                    $total_cost_recovered += $cost_recovered;
                                                    break;
                                                case "Book-Bank Deposit":
                                                    $Book_Bank_Deposit = $row['amount'];
                                                    $total_Book_Bank_Deposit += $Book_Bank_Deposit;
                                                    break;
                                                case "Other":
                                                    $other = $row['amount'];
                                                    $total_other += $other;
                                                    break;
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($cnt); ?></td>
                                                <td><?php echo htmlspecialchars($row['receipt_no']); ?></td>
                                                <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($row['receipt_date']))); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['student_Name']); ?></td>

                                                <?php if ($reason == "Late Charges") { ?>
                                                    <td><?php echo htmlspecialchars(number_format($late_charges, 2)); ?></td>
                                                <?php } ?>
                                                <?php if ($reason == "Book-Bank Fees") { ?>
                                                    <td><?php echo htmlspecialchars(number_format($book_bank_fees, 2)); ?></td>
                                                <?php } ?>
                                                <?php if ($reason == "Cost Recovered") { ?>
                                                    <td><?php echo htmlspecialchars(number_format($cost_recovered, 2)); ?></td>
                                                <?php } ?>
                                                <?php if ($reason == "Book-Bank Deposit") { ?>
                                                    <td><?php echo htmlspecialchars(number_format($Book_Bank_Deposit, 2)); ?></td>
                                                <?php } ?>
                                                <?php if ($reason == "Other") { ?>
                                                    <td><?php echo htmlspecialchars(number_format($other, 2)); ?></td>
                                                <?php } ?>
                                              <td>
                                                    <?php echo '<a href="generate_receipt.php?prn=' . $row['student_prn'] . '&id=' . $row['id'] . '" class="btn  btn-info fa-solid fa-print" style="align-items: center;"> </a> ';
                                                    ?>
                                                </td>

                                            </tr>
                                            <?php
                                            $cnt++;
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-dark">
                                        <td colspan="3"></td>
                                        <td colspan="1">Total</td>
                                        <?php if ($reason == "Late Charges") { ?>
                                                <td><?php echo htmlspecialchars(number_format($total_late_charges, 2)); ?></td>
                                            <?php } ?>
                                            <?php if ($reason == "Book-Bank Fees") { ?>
                                                <td><?php echo htmlspecialchars(number_format($total_book_bank_fees, 2)); ?>
                                                </td>
                                            <?php } ?>
                                            <?php if ($reason == "Cost Recovered") { ?>
                                                <td><?php echo htmlspecialchars(number_format($total_cost_recovered, 2)); ?>
                                                </td>
                                            <?php } ?>
                                            <?php if ($reason == "Book-Bank Deposit") { ?>
                                                <td><?php echo htmlspecialchars(number_format($total_Book_Bank_Deposit, 2)); ?>
                                                </td>
                                            <?php } ?>
                                            <?php if ($reason == "Other") { ?>
                                                <td><?php echo htmlspecialchars(number_format($total_other, 2)); ?></td>
                                            <?php } ?>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php } else { ?>
                                <p>No data found for the selected date range.</p>
                            <?php } ?>
                        </div>
                    </div>
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
    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        footer: true, // Ensure the footer is printed
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column)
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        
                       title: 'start_date:<?php echo $start_date."  " ?>End Date:<?php echo $end_date."  " ?>collection:<?php echo $reason."  " ?> ',

                        footer: true, // Include the footer in export
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column)
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        title: ' start_date:<?php echo $start_date."  " ?>End Date:<?php echo $end_date."  " ?>collection:<?php echo $reason."  " ?> ',
                        footer: true, // Include the footer in export
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column)
                        }
                    }
                ]
            });
        });
    </script>

</body>

</html>