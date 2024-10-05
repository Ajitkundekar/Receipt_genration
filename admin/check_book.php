<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}

$error = "";
$msg = "";


// Check if the insert button was clicked
if (isset($_POST['add'])) {
    $receipt_no = $_POST['recipt_no'];
    $receipt_date = $_POST['rdate'];
    $student_name = $_POST['prn'];
    $amount = $_POST['amount'];
    $amount_in_words = $_POST['rsinworld'];
    $payment_mode = $_POST['payment_mode'];
    $reason = $_POST['resion'];

    // Check which payment mode was selected and get corresponding values
    if ($payment_mode == 'cheque') {
        $cheque_no = $_POST['cheque_no'];
        $cheque_date = $_POST['cheque_date'];
        $bank_name = $_POST['bank_name'];
        $sql = "INSERT INTO receipts (receipt_no, receipt_date, student_prn, amount, amount_in_words, payment_mode, cheque_no, cheque_date, bank_name, reason)
                VALUES ('$receipt_no', '$receipt_date', '$student_name', '$amount', '$amount_in_words', '$payment_mode', '$cheque_no', '$cheque_date', '$bank_name', '$reason')";
    } elseif ($payment_mode == 'online') {
        $transaction_id = $_POST['transaction_id'];
        $transaction_date = $_POST['transaction_date'];
        $sql = "INSERT INTO receipts (receipt_no, receipt_date, student_prn, amount, amount_in_words, payment_mode, transaction_id, transaction_date, reason)
                VALUES ('$receipt_no', '$receipt_date', '$student_name', '$amount', '$amount_in_words', '$payment_mode', '$transaction_id', '$transaction_date', '$reason')";
    } else {
        // Handle cash payment
        $sql = "INSERT INTO receipts (receipt_no, receipt_date, student_prn, amount, amount_in_words, payment_mode, reason)
                VALUES ('$receipt_no', '$receipt_date', '$student_name', '$amount', '$amount_in_words', '$payment_mode', '$reason')";
    }

    if (mysqli_query($con, $sql)) {
        $_SESSION['msg'] = "Receipt created successfully!";
        header("Location: check_book.php?msg=success");
        exit;
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($con);
    }
}

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
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-0" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->
        <div class="container" style="margin-left: 10px;">
            <div class="card card-body">
            </div>
            <div class="card card-body">
                <form method="post" action="">

                    <h5 class="text-center">Kolhapur Institute of Technology's</h5>
                    <h4 class="text-center text-primary">Institute of Management Education & Research (AUTONOMOUS)</h4>
                    <h6 class="text-center">Gokul Shirgaon, Kolhapur. 416 234</h6>
                    <h6 class="text-center">Tel: +91-9158528383 (Director) 0231-2636266 (Office)</h6>
                    <h6 class="text-center">Fax: +91-9158528383, 0231-2638881</h6>

                    <div class="row mb-3">
                        <div class="col-md-2 text-center my-auto">
                            <h5>Receipt No:</h5>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="recipt_no" class="form-control form-control-lg" name="recipt_no"
                                value="" required readonly>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-1 text-center my-auto">
                            <h5>Date:</h5>
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="rdate" class="form-control form-control-lg" name="rdate"
                                placeholder="date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 text-center my-auto">
                            <h5>Student PRN:</h5>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="prn" class="form-control form-control-lg" name="prn" value=""
                                placeholder="Enter PRN">
                        </div>
                        <div class="col-md-3 text-left my-auto">
                            <h5>RECEIVED with thanks from:</h5>
                        </div>
                        <div class="col-md-5">
                            <input type="text" id="sname" class="form-control form-control-lg" name="sname"
                                placeholder="Name of student" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 text-center my-auto">
                            <h5>Amount:</h5>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="amount" class="form-control form-control-lg" name="amount"
                                placeholder="Enter amount">
                        </div>
                        <div class="col-md-3 text-left my-auto">
                            <h5>The Sum of Rupees:</h5>
                        </div>
                        <div class="col-md-5">
                            <input type="text" id="rsinworld" class="form-control form-control-lg" name="rsinworld"
                                placeholder="Rupees in words" required>
                        </div>
                        <br><br><br>
                        <div class="col-md-4 text-center my-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_mode" id="cash_option"
                                    value="cash" checked>
                                <label class="form-check-label" for="cash_option">Cash</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_mode" id="cheque_option"
                                    value="cheque">
                                <label class="form-check-label" for="cheque_option">Cheque</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_mode" id="online_option"
                                    value="online">
                                <label class="form-check-label" for="online_option">Online</label>
                            </div>
                        </div>

                        <div class="col-md-3 text-left my-auto">
                            <h5>An amount of :</h5>
                        </div>
                        <div class="col-md-5">
                            <select class="form-control form-control-lg" name="resion" required>
                                <option value="">--Select --</option>
                                <option value="Late Charges">Late Charges</option>
                                <option value="Book-Bank Fees">Book-Bank Fees</option>
                                <option value="Cost Recovered">Cost Recovered</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div id="cheque_fields">
                        <div class="card card-body">
                            <div class="row mb-3">
                                <div class="col-md-3 text-left my-auto">
                                    <h5>Cheque No.:</h5>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control form-control-lg" name="cheque_no"
                                        placeholder="Cheque number">
                                </div>
                                <div class="col-md-3 text-left my-auto">
                                    <h5>Cheque Date:</h5>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control form-control-lg" name="cheque_date"
                                        placeholder="Cheque Date">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3 text-left my-auto">
                                    <h5>Drawn on:</h5>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-lg" name="bank_name"
                                        placeholder="Bank Name">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Online Payment Fields -->
                    <div id="online_fields" style="display: none;">
                        <div class="card card-body">
                            <div class="row mb-3">
                                <div class="col-md-3 text-left my-auto">
                                    <h5>Transaction ID:</h5>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control form-control-lg" name="transaction_id"
                                        placeholder="Transaction ID">
                                </div>
                                <div class="col-md-3 text-left my-auto">
                                    <h5>Transaction Date:</h5>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control form-control-lg" name="transaction_date"
                                        placeholder="Transaction Date">
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-12 text-center">
                            <button type="submit" name="add" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>

                <!-- Popup Message -->
                <div id="successPopup" class="alert alert-success"
                    style="display:none; position:fixed; top:10px; right:10px; z-index:1000;">
                    <?php echo isset($msg) ? $msg : ''; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Other existing HTML code -->

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo isset($_SESSION['msg']) ? $_SESSION['msg'] : ''; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="generateReceiptBtn" class="btn btn-primary">Generate Receipt</button>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (isset($_SESSION['msg'])): ?>
                var successModal = new bootstrap.Modal(document.getElementById('successModal'), {});
                successModal.show();
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>
        });
    </script>




    <!-- Scripts -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
        function showSuccessPopup() {
            var popup = document.getElementById('successPopup');
            popup.style.display = 'block';
            setTimeout(function () {
                popup.style.display = 'none';
            }, 5000); // Hide after 3 seconds
        }

        // JavaScript to toggle visibility of cheque fields
        document.addEventListener('DOMContentLoaded', function () {
            const cashOption = document.getElementById('cash_option');
            const chequeOption = document.getElementById('cheque_option');
            const onlineOption = document.getElementById('online_option');
            const chequeFields = document.getElementById('cheque_fields');
            const onlineFields = document.getElementById('online_fields');

            function toggleFields() {
                chequeFields.style.display = cashOption.checked ? 'none' : chequeOption.checked ? 'block' : 'none';
                onlineFields.style.display = onlineOption.checked ? 'block' : 'none';
            }

            // Initially hide or show fields based on the selected option
            toggleFields();

            // Add event listeners to radio buttons
            cashOption.addEventListener('change', toggleFields);
            chequeOption.addEventListener('change', toggleFields);
            onlineOption.addEventListener('change', toggleFields);
        });

    </script>
    <script>
        // Function to set today's date in the rdate field and trigger change event
        function setTodayDateAndFetchReceiptNo() {
            // Get today's date in 'YYYY-MM-DD' format
            var today = new Date().toISOString().split('T')[0];

            // Set the value of the rdate field to today's date
            document.getElementById('rdate').value = today;

            // Trigger the change event to fetch receipt number
            $('#rdate').trigger('change');
        }

        // JavaScript to fetch receipt number when the date is changed
        $('#rdate').on('change', function () {
            var rdate1 = this.value;

            $.ajax({
                url: 'assets/ajax_dropdown/recitepno.php',
                type: "POST",
                data: {
                    rdate_data: rdate1
                },
                success: function (result) {
                    // Update the input field with the new receipt number
                    $('#recipt_no').val(result);
                    console.log("New receipt number: " + result);
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:" + status + error);
                }
            });
        });

        // Set today's date and fetch receipt number when the page loads
        $(document).ready(function () {
            setTodayDateAndFetchReceiptNo();
        });
    </script>


    <script>
        $('#prn').on('change', function () {
            var prn = this.value;
            console.log(prn);

            $.ajax({
                url: 'assets/ajax_dropdown/student_name.php',
                type: "POST",
                data: {
                    prn_data: prn
                },
                success: function (result) {
                    // Update the input field with the new receipt number
                    $('#sname').val(result);
                    console.log("New receipt number: " + result);
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error: " + status + error);
                }
            });
        });
    </script>


    <script>
        // JavaScript function to convert numbers to words
        function numberToWords(num) {
            const a = [
                '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
            ];
            const b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
            const g = ['', 'thousand', 'million', 'billion', 'trillion'];

            if (num === 0) return 'zero';

            let str = '';
            let i = 0;

            while (num > 0) {
                let chunk = num % 1000;
                if (chunk) {
                    let chunkStr = '';
                    let hundreds = Math.floor(chunk / 100);
                    let remainder = chunk % 100;

                    if (hundreds) chunkStr += a[hundreds] + ' hundred ';

                    if (remainder < 20) {
                        chunkStr += a[remainder] + ' ';
                    } else {
                        chunkStr += b[Math.floor(remainder / 10)] + ' ' + a[remainder % 10] + ' ';
                    }

                    str = chunkStr + g[i] + ' ' + str;
                }

                num = Math.floor(num / 1000);
                i++;
            }

            return str.trim();
        }

        // Event listener to update rsinworld input with the amount in words
        document.getElementById('amount').addEventListener('change', function () {
            var amount = parseInt(this.value);
            if (!isNaN(amount)) {
                var amountInWords = numberToWords(amount);
                document.getElementById('rsinworld').value = amountInWords + " Only";
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add click event listener to the generate receipt button
            document.getElementById('generateReceiptBtn').addEventListener('click', function () {
                // AJAX request to fetch the last record's id and student_prn
                $.ajax({
                    url: 'assets/ajax_dropdown/get_last_record.php', // This script should return the last record's id and student_prn
                    type: 'POST',
                    success: function (result) {
                        var data = JSON.parse(result); // Assuming result is returned as a JSON object
                        var lastId = data.id;
                        var studentPrn = data.student_prn;

                        // Redirect to generate_receipt.php with id and student_prn as query parameters
                        window.location.href = 'generate_receipt.php?id=' + lastId + '&prn=' + studentPrn;
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: " + status + " " + error);
                    }
                });
            });
        });
    </script>


</body>

</html>