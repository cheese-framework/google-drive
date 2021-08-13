<?php

include_once "./includes/header.php";

$error = [];
$success = [];
$title = "";

$userFolder = Misc::getFolder($_id);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'];
    $file = $_FILES['file'];

    if (trim($title) != "") {
        if (isset($file)) {
            $allowed = ['txt', 'docx', 'odt', 'log', 'png', 'jpeg', 'jpg', 'gif', 'zip', 'html', 'pdf','mp4'];
            try {
                $raised = new RaiseUpload("../uploads/$userFolder", $file, $allowed, 20000000);
                $streams = $raised->prepareUpload();
                $size = 0;
                if (count($streams) > 0) {
                    foreach ($streams as $stream) {
                        $size += $stream[1];
                        $bool = Misc::upload($title, "folder", "../uploads/$userFolder", $stream[1], $stream[4], $_id, $stream[5]);
                    }
                }
                $newSize = $size + Misc::getTotalSizeUploaded($_id);
                if ($newSize <= MAX_SIZE_UPLOAD) {
                    $uploadedSize = FileProcessor::upload($streams);
                    Misc::setTotalSizeUploaded($newSize, $_id);
                    $success[] = "Total upload was " . Misc::getMB($uploadedSize);
                } else {
                    $error[] = "Uploading this file will exceed your allowed size upload";
                }
            } catch (Exception $e) {
                $error[] = $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            }
        } else {
            $error[] = "No valid file selected";
        }
    } else {
        $error[] = "Please set the title";
    }
}

?>
<div class="col-lg-10 mx-auto my-3">
    <div class="card p-3">
        <h4 class="text-center">Folder Upload</h4>

        <?php
        if (!empty($error)) {
            echo "<div class='alert alert-warning col-lg-10 mx-auto'>";
            foreach ($error as $err) {
                echo $err . "<br>";
            }
            echo "</div>";
        }

        if (!empty($success)) {
            echo "<div class='alert alert-success col-lg-10 mx-auto'>";
            foreach ($success as $succ) {
                echo $succ . "<br>";
            }
            echo "</div>"; ?>
        <script>
        setTimeout(() => {
            window.location.assign('folderupload.php');
        }, 3000);
        </script>
        <?php
        }
        ?>
        <form autocomplete="off" enctype="multipart/form-data" class="col-lg-10 mx-auto" method="POST">
            <div class="input-field">
                <input type="text" id="title" class="validate" name="title" required value="<?= $title ?>">
                <label for="title">* Folder Title</label>
                <span class="helper-text" data-error='This field is required'></span>
            </div>
            <div class="file-field input-field">
                <div class="btn btn-primary pretty">
                    <span>File</span>
                    <input type="file" name="file[]" multiple directory="" webkitdirectory="" mozdirectory="">
                </div>
                <div class="file-path-wrapper">
                    <input type="text" class="file-path validate" name="path[]" multiple directory="" webkitdirectory=""
                        mozdirectory="" required>
                    <span class="helper-text" data-error='This field is required'></span>
                </div>
            </div>
            <div class="input-field">
                <input type="submit" value="Upload" name="upload" class="btn btn-dark">
            </div>
        </form>
    </div>
</div>

<?php include_once "./includes/footer.php"; ?>