<?php
include_once "../init.php";

if (!isset($_SESSION['_id'])) {
    header("location: ../login.php");
}

$username = $_SESSION['username'];
$_id = $_SESSION['_id'];

$user = new Users();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/materialize.min.css">
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <title>My Drive</title>
    <style>
        .pretty {
            color: white;
            text-decoration: none;
        }

        .pretty:hover {
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body class="">
    <nav>
        <div class="nav-wrapper purple darken-4">
            <a href="index.php" class="brand-logo pretty">&nbsp; &nbsp; My Drive</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a>Welcome, <?= $username; ?></a></li>
                <li><a href="?logout">Logout</a></li>
                <li><a>Uploaded:
                        <?= Misc::getMB($user->getTotalSizeUploaded($_id)) . "/" .  Misc::getMB(MAX_SIZE_UPLOAD); ?></a>
                </li>
                <li><a href="fileupload.php" class="btn btn-link pretty">Upload File</a></li>
                <li><a href="folderupload.php" class="btn btn-link pretty">Upload Folder</a></li>
            </ul>
        </div>
    </nav>
</body>
<div class="container">