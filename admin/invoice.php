<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bootstrap 5 Side Bar Navigation</title>

    <!-- Include your existing stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.1/css/buttons.dataTables.min.css">

    <!-- Optional print-specific stylesheet -->
    <link rel="stylesheet" href="assets/css/print.css" media="print">
</head>

<body>
    <!-- Main Wrapper -->
    <div class="container" style="width: 50%;">
        <br>
        <form method="post" action="generate_receipt.php">
            <!-- Other form content -->
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Generate PDF</button>
            </div>
        </form>

    </div>

    <!-- JS Scripts -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/style.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <!-- DataTables Scripts -->

    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        footer: true, // Ensure the footer is printed
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column if needed
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Table Data',
                        footer: true, // Include the footer in export
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column if needed
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        title: 'Table Data',
                        footer: true, // Include the footer in export
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column if needed
                        }
                    }
                ]
            });
        });
    </script>
</body>

</html>