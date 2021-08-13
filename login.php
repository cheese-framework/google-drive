<?php

include_once "./init.php";

$auth = new Users();

$username = "";
$error = [];
$success = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username != "" && $password != "") {
        try {
            $data = $auth->login($username, $password);
            $_SESSION['_id'] = $data->id;
            $_SESSION['username'] = $data->username;
            $success[] = "Login succesfully";
        } catch (Exception $e) {
            $error[] = $e->getMessage();
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
    <title>Drive - Login</title>
</head>

<body class="">
    <nav>
        <div class="nav-wrapper purple darken-4">
            <a href="index.php" class="brand-logo" style="text-decoration: none;">&nbsp; &nbsp; Drive</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="register.php">Register</a></li>
                <li class="active"><a href="login.php">Login</a></li>
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
                window.location.assign('mydrive');
            }, 1500);
            </script>
            <?php
            }
            ?>
            <h4 class="text-center">Login to Drive 📂</h4>
            <form autocomplete="off" method="POST">

                <div class="input-field">
                    <input type="text" id="user_name" class="validate" name="username" required
                        value="<?= $username ?>">
                    <label for="user_name">*Username or E-Mail</label>
                    <span class="helper-text" data-error='This field is required'></span>
                </div>
                <div class="input-field">
                    <input type="password" id="password" name="password" class="validate" required minlength="4">
                    <label for="password">*Password</label>
                    <span class="helper-text" data-error='Password is required'></span>
                </div>
                <div class="input-field">
                    <input type="submit" value="Sign in" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>

    <script src="./static/js/materialize.min.js"></script>
</body>

</html>