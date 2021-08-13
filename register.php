<?php

include_once "./init.php";

$auth = new Users();

$username = "";
$email = "";
$error = [];
$success = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($username != "" && $email != "" && $password != "" && $confirm != "") {
        $folderName = strtoupper(uniqid(trim($username), true));
        $folderName = str_replace(' ', '', $folderName);
        $folder = "uploads/$folderName";
        if ($password === $confirm && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            try {
                $bool = $auth->register($username, $email, $password, $folderName);
                if ($bool) {
                    if (!file_exists($folder)) {
                        mkdir($folder);
                    }
                    $success[] = "You have been registered and a folder has been created for you. Folder name: <b>$folderName</b><br>Redirecting you in 5secs";
                } else {
                    $error[] = "Cannot register you now";
                }
            } catch (Exception $e) {
                $error[] = $e->getMessage();
            }
        } else {
            $error[] = "Passwords do not match";
            $error[] = "The email might also be invalid";
        }
    } else {
        $error[] = "All fields are needed";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./static/css/materialize.min.css">
    <link rel="stylesheet" href="./static/css/bootstrap.min.css">
    <title>Drive - Register</title>
</head>

<body class="">
    <nav>
        <div class="nav-wrapper purple darken-4">
            <a href="index.php" class="brand-logo" style="text-decoration: none;">&nbsp; &nbsp; Drive</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li class="active"><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="col-lg-9 mx-auto my-3">
            <?php
            if (!empty($error)) {
                echo "<div class='alert alert-warning'>";
                foreach ($error as $err) {
                    echo $err . "<br>";
                }
                echo "</div>";
            }

            if (!empty($success)) {
                echo "<div class='alert alert-success'>";
                foreach ($success as $succ) {
                    echo $succ . "<br>";
                }
                echo "</div>";
            ?>
                <script>
                    setTimeout(() => {
                        window.location.assign('login.php');
                    }, 5000);
                </script>
            <?php
            }
            ?>
            <h4 class="text-center">Register to Drive 📂</h4>
            <form autocomplete="off" method="POST">

                <div class="input-field">
                    <input type="text" id="user_name" class="validate" name="username" required value="<?= $username ?>">
                    <label for="user_name">*Username</label>
                    <span class="helper-text" data-error='Username is required'></span>
                </div>
                <div class="input-field">
                    <input type="email" id="email" name="email" class="validate" required value="<?= $email ?>">
                    <label for="email">*E-Mail Address</label>
                    <span class="helper-text" data-error='Email is required'></span>
                </div>
                <div class="input-field">
                    <input type="password" id="password" name="password" class="validate" required minlength="4">
                    <label for="password">*Password</label>
                    <span class="helper-text" data-error='Password is required'></span>
                </div>
                <div class="input-field">
                    <input type="password" id="confirm" name="confirm" class="validate" required minlength="4">
                    <label for="confirm">*Confirm Password</label>
                    <span class="helper-text" data-error='Password is required'></span>
                </div>
                <div class="input-field">
                    <input type="submit" value="Register" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>

    <script src="./static/js/materialize.min.js"></script>
</body>

</html>