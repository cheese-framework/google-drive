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
        if ($file['name'] != "") {
            $allowed = ['txt', 'docx', 'odt', 'log', 'png', 'jpeg', 'jpg', 'gif', 'zip', 'html', 'pdf','mp4','mp3'];
            try {
                $flat = new FlatUpload("../uploads/$userFolder", $file, $allowed);
                $stream = $flat->upload();
                $size = $stream[1];
                $newSize = $size + Misc::getTotalSizeUploaded($_id);
                if ($newSize <= MAX_SIZE_UPLOAD) {
                    $path  = $stream[0];
                    $uploadedSize = FileProcessor::flatUpload($stream);
                    $uploadedFile = $stream[4];
                    Misc::setTotalSizeUploaded($newSize, $_id);
                    $bool = Misc::upload($title, "flat", $path, $size, null, $_id, $uploadedFile);
                    if ($bool) {
                        $success[] = "Total upload was " . Misc::getKB($uploadedSize);
                    } else {
                        $error[] = "Could not upload";
                    }
                } else {
                    $error[] = "Uploading this file will exceed your allowed size upload";
                }
            } catch (Exception $e) {
                $error[] = $e->getMessage();
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
        <h4 class="text-center">Single File Upload</h4>
        <p class="center">Accepted file types are:
            <b><i>.txt, .docx, .odt, .log, .png, .jpeg, .jpg, .gif, .zip, .html, .pdf, .mp4</i></b>
        </p>
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
            window.location.assign('fileupload.php');
        }, 3000);
        </script>
        <?php
        }
        ?>
        <form autocomplete="off" enctype="multipart/form-data" class="col-lg-10 mx-auto" method="POST">
            <div class="input-field">
                <input type="text" id="title" class="validate" name="title" required value="<?= $title ?>">
                <label for="title">* File Title</label>
                <span class="helper-text" data-error='This field is required'></span>
            </div>
            <div class="file-field input-field">
                <div class="btn btn-primary pretty">
                    <span>File</span>
                    <input type="file" name="file">
                </div>
                <div class="file-path-wrapper">
                    <input type="text" class="file-path validate" name="path" required>
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