<?php
session_start();
require ("config.php");
////code

if (!isset($_SESSION['auser'])) {
    header("location:t_login.php");

}

require 'assets/plugins/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['import_file_btn'])) {
    # code...
    $allowed_ext = ['xls', 'csv', 'xlsx'];
    $fileName = $_FILES['import_file']['name'];
    $checking = explode(".", $fileName);
    $file__ext = end($checking);
    if (in_array($file__ext, $allowed_ext)) {
        # code...
        $targetPath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($targetPath);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $count = "0";
        foreach ($data as $row) {
            if ($count > 0) {
                # code...
                // $s_id = $row['0'];
                $prn = $row['1'];
                $s_name = $row['2'];
                $class = $row['3'];
                $phone = $row['4'];
                $email=$row['5'];
                
                $checkStudent = "SELECT * FROM student_data WHERE  prn = '$prn' ";
                $checkStudnet_result = mysqli_query($con, $checkStudent);
                if (mysqli_num_rows($checkStudnet_result) > 0) {
                    //alradyexist 
                    $up_query = "UPDATE student_data set  s_name='$s_name' , class='$class' ,phone='$phone' ,email='$email' WHERE prn = '$prn'";
                    $up_result = mysqli_query($con, $up_query);
                    $msg1 = 1;
                } else {
                    //new record  insert 
                    $in_query = "insert into student_data (prn,s_name,class,phone ,email)values('$prn' ,'$s_name','$class','$phone','$email')";
                    $in_result = mysqli_query($con, $in_query);
                    $msg1 = 1;
                }
            } else {
                $count = "1";
            }
        }
        if (isset($msg1)) {
            # code...
            $_SESSION['status'] = "<p class='alert alert-success'> File imported successfully</p>";
            header("location:student_add_batch.php");
        } else {
            $_SESSION['status'] = "<p class='alert alert-success'> File imported Failed</p>";
            header("location:student_add_batch.php");
        }
    } else {
        $_SESSION['status'] = "<p class='alert alert-success'> invalid file </p>";
        header("Location:student_add_batch.php");
        exit(0);
    }
}
?>