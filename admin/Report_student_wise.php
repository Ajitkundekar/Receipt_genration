<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit();
}

$error = "";
$msg = "";

if (isset($_SESSION['student_prn'])) {

    // Handle Student Wise Report logic
    $student_prn = $_SESSION['student_prn'];

    // Prepare and execute query to get date-wise report data
    $stmt = $con->prepare("SELECT r.*, s.s_name as student_Name FROM receipts r JOIN student_data s ON r.student_prn = s.prn WHERE r.student_prn=?");
    $stmt->bind_param('s', $student_prn);
    $stmt->execute();
    $query = $stmt->get_result();
} else {
    $error = "Student not found.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Wise Report</title>
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
                <h2>student-wise Report</h2>

                <div class="row card card-body shadow ">
                    <div class="col-12">
                        <div class="data_table">
                            <?php if (!empty($error)) { ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                            <?php } ?>
                            <?php if ($query && $query->num_rows > 0) { ?>
                                <table id="example" class="table table-striped table-responsive responsive table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Receipt No</th>
                                            <th>Receipt Date</th>
                                            <th>Student Name</th>
                                            <th>Late Charges</th>
                                            <th>Book-Bank Fees</th>
                                            <th>Cost Recovered</th>
                                            <th>Book-Bank Deposit</th>
                                            <th>Other</th>

                                            <th> Grand Total</th>
                                            <th> Action</th>
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
                                            // Initialize amounts
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
                                                case "Other":

                                                    $other = $row['amount'];
                                                    $total_other += $other;
                                                    break;
                                                case "Book-Bank Deposit":

                                                    $Book_Bank_Deposit = $row['amount'];
                                                    $total_Book_Bank_Deposit += $Book_Bank_Deposit;
                                                    break;
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($cnt); ?></td>
                                                <td><?php echo htmlspecialchars($row['receipt_no']); ?></td>
                                                <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($row['receipt_date']))); ?>
                                                </td> <!-- Format the date -->
                                                <td><?php echo htmlspecialchars($row['student_Name']); ?></td>
                                                <td><?php echo htmlspecialchars(number_format($late_charges, 2)); ?></td>
                                                <td><?php echo htmlspecialchars(number_format($book_bank_fees, 2)); ?></td>
                                                <td><?php echo htmlspecialchars(number_format($cost_recovered, 2)); ?></td>
                                                <td><?php echo htmlspecialchars(number_format($Book_Bank_Deposit, 2)); ?></td>

                                                <td><?php echo htmlspecialchars(number_format($other, 2)); ?></td>
                                                <td></td>
                                                <td>
                                                    <?php echo '<p><a href="generate_receipt.php?prn=' . $row['student_prn'] . '&id=' . $row['id'] . '" class="btn  btn-info  fa-solid fa-print" style="align-items: center;"></a> </p> ';
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        } ?>


                                    </tbody>
                                    <tfoot>
                                        <tr class="table-dark">
                                            <td colspan=""></td>
                                            <td colspan=""></td>
                                            <td colspan=""></td>
                                            <td>Total</td>
                                            <td><?php echo htmlspecialchars(number_format($total_late_charges, 2)); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($total_book_bank_fees, 2)); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(number_format($total_cost_recovered, 2)); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(number_format($total_Book_Bank_Deposit, 2)); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(number_format($total_other, 2)); ?></td>
                                            <td><?php echo htmlspecialchars(number_format(
                                                $total_late_charges +
                                                $total_book_bank_fees +
                                                $total_cost_recovered +
                                                $total_Book_Bank_Deposit +
                                                $total_other,
                                                2
                                            ));
                                            ?></td> <!-- Grand Total -->
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
    <!-- <script src="assets/js/custom.js"></script> -->
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
                        title: 'Studnet Wise Report',

                        footer: true, // Include the footer in export
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column)
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        title: 'Studnet Wise Report',
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