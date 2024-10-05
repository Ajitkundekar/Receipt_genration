<?php
include("config.php");
$error = "";

if (isset($_POST['register'])) {
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['pass'];
    $confirm_pass = $_REQUEST['confirm_pass'];

    if (!empty($user) && !empty($pass) && !empty($confirm_pass)) {
        if ($pass === $confirm_pass) {
            // Check if the username already exists
            $query = "SELECT name FROM admin WHERE name='$user'";
            $result = mysqli_query($con, $query) or die(mysqli_error($con));
            if (mysqli_num_rows($result) == 0) {
                // Insert the new user into the database
                $insert_query = "INSERT INTO admin (name, pass) VALUES ('$user', '$pass')";
                if (mysqli_query($con, $insert_query)) {
                    $_SESSION['auser'] = $user;
                    header("Location: index.php");
                } else {
                    $error = "* Registration failed, please try again.";
                }
            } else {
                $error = "* Username already exists.";
            }
        } else {
            $error = "* Passwords do not match.";
        }
    } else {
        $error = "* Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin Registration</title>
    <style>
        .registration-form {
            width: 350px;
            padding: 2rem 1rem 1rem;
            margin-top: 10%;
        }

        form {
            padding: 1rem;
        }
    </style>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/all.min.css" rel="stylesheet" />
    <link href="assets/css/fontawesome.min.css" rel="stylesheet" />
    <!-- Bootstrap core JS-->
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div class="container">
            <div class="wrapper d-flex align-items-center justify-content-center h-100">
                <div class="card registration-form">
                    <div class="card-body">
                        <h2 class="card-title text-center"> Admin Registration </h2>
                        <p style="color:red;"><?php echo $error; ?></p>

                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="user" class="form-control" placeholder="Enter username">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="pass" class="form-control" placeholder="Enter password">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_pass" class="form-control" placeholder="Confirm password">
                            </div>
                            <button class="btn btn-primary btn-block w-100" name="register" type="submit">Register</button>
                        </form>
                        <p class="mt-2 text-center">Already have an account? <a href="index.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>

</html>
