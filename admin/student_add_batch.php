<?php
session_start();
require("config.php");
////code




if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Information </title>
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">



</head>

<body>
    <!-- Side-Nav -->

    <?php include("header.php"); ?>

    <!-- Main Wrapper -->
    <div class="p-3 my-container  active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-2" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->
        <div class="container" style="margin-left: 10px;">
            <br>
            <h3 class="page-title"> Add Student </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> add Batch </li>
                </ol>
            </nav>

            <h5> Note:-</h5>
            <p> <label style="color: red;" for="">*</label>1.Once You Add the batch you can not change the <strong>
                     <strong>prn of Student </strong>
                <br>




                <?php if (isset($_SESSION['status'])) {
                    # code...
                    echo $_SESSION['status'];
                    unset($_SESSION['status']);

                }

                ?>

            <div>


                <form action="student_add_batch01.php" method="POST" enctype="multipart/form-data">
                    <div class="container  card  card-body shadow">
                        <div class="row">
                            <label for="formFileLg" class="form-label">upload Exel File to add batch in
                                system</label>
                            <div class="col-md-2 my-auto">
                                <h5>Select File</h5>

                            </div>
                            <div class="col-md-5">

                                <input class="form-control form-control-lg" id="formFileLg" name="import_file"
                                    type="file">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="import_file_btn" class="btn btn-primary"> Upload/Import
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <br>



            <div class="container">
                <br>
                <h3 class="page-title"> Student List </h3>
                <br>




                <div class="row card  card-body shadow ">
                    <div class="col-12">
                        <div class="data_table">
                            <table id="example" class="table table-striped responsive table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Prn No</th>
                                        <th>Student Name </th>
                                        <th>Class</th>
                                        <th>Phone</th>
                                        <th>email</th>
                                        <th>Action</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $query = mysqli_query($con, "select * from student_data");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_row($query)) {
                                        ?>


                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row['1']; ?></td>
                                            <td><?php echo $row['2']; ?></td>
                                            <td><?php echo $row['3']; ?></td>
                                            <td><?php echo $row['4']; ?></td>
                                            <td><?php echo $row['5']; ?></td>
                                            <!-- <td><a href="subject_add_edit.php?id=<?php echo $row['0']; ?>"><button
                                                        class="btn btn-info"><i
                                                            class="fa-solid fa-pen-to-square"></i></button></a>
                                                </td> -->


                                        </tr>
                                        <?php
                                        $cnt = $cnt + 1;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

    <!-- bootstrap js -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/style.js"></script>


</body>

</html>