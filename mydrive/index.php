<?php include_once "./includes/header.php";


// zip and download

if (isset($_GET['download']) && $_GET['download'] != "") {

    $the_folder = '../uploads/' . $_GET['download'];
    $zip_file_name = '../archive.zip';
    $za = new ParkerZipper;
    $res = $za->open($zip_file_name, ZipArchive::CREATE);
    if ($res === TRUE) {
        echo "<div class='alert alert-success m-2'>Folder Zipped and ready to download</div>";
        $za->addDir($the_folder, basename($the_folder));
        $za->close();
        $za->download($zip_file_name);
    } else {
        echo "<div class='alert alert-danger m-2'>Could not create a zip archive</div>";
    }
}

// delete folder
if (isset($_GET['deleteFolder']) && $_GET['deleteFolder'] != "") {
    $size = $_GET['size'];
    $dir = $_GET['deleteFolder'];
    $mainFolder = explode("/", $dir);
    $mainFolder = end($mainFolder);
    try {
        Analytics::deleteRaise("../uploads/" . $dir, $mainFolder, $_id, $size);
        header("Location: index.php");
    } catch (Exception $e) {
        echo "<div class='alert alert-danger m-2'>" . $e->getMessage() . "</div>";
    }
}

// delete file
if (isset($_GET['deleteFile']) && $_GET['deleteFile'] != "") {
    $size = $_GET['size'];
    $dir = $_GET['deleteFile'];
    $mainFile = explode("/", $dir);
    $dir = $mainFile[0];
    $mainFile = end($mainFile);
    try {
        Analytics::deleteFlat("../uploads/" . $dir, $mainFile, $_id, $size);
        header("Location: index.php");
    } catch (Exception $e) {
        echo "<div class='alert alert-danger m-2'>" . $e->getMessage() . "</div>";
    }
}



?>
<div class="col-lg-12 mx-auto my-3">
    <div class="card">
        <?php
        $myFiles = Analytics::getMyFiles($_id);
        $myFlatFiles = [];
        $myFolderFiles = [];
        if ($myFiles) {
            foreach ($myFiles as $file) {
                if ($file->type == "flat") {
                    $myFlatFiles[] = $file;
                } else {
                    if (key_exists($file->folder, $myFolderFiles)) {
                        $myFolderFiles[$file->folder][] = $file;
                    } else {
                        $myFolderFiles[$file->folder] = [$file];
                    }
                }
            }
        ?>
            <div class="row card-body">
                <!-- loop through folders first -->
                <?php
                foreach ($myFolderFiles as $myFolder) {
                    $fsize = 0;
                    foreach ($myFolder as $fdr) {
                        $fsize += $fdr->size;
                    }
                    $root = Misc::getFolder($_id) . "/" . $myFolder[0]->folder;
                    echo "<div class='col-lg-3 card m-2'>";
                    echo "<div class='card-body'>";
                    echo "<b><img src='../folder.jpg' alt='Folder Image' width='90px' height='90px'/></b>";
                    echo "<br><br><p>" . count($myFolder) . " files in folder</p>";
                    echo "<div><a href='?download={$root}' class='btn btn-success'>Zip and Download</a></div><br>";
                    echo "<div><a href='?deleteFolder={$root}&size={$fsize}' class='btn btn-danger'>Delete</a></div>";
                    echo "</div>";

                    echo "</div>";
                }

                // loop through flat files now
                foreach ($myFlatFiles as $myFile) {
                    $root = Misc::getFolder($_id) . "/" . $myFile->file;
                    $extension = explode(".", $myFile->file);
                    $extension = strtolower(end($extension));
                    if ($extension == 'png' || $extension == 'jpg') {
                        echo "<div class='col-lg-3 card m-2' style='height: 200px !important'>";
                        echo "<div class='card-body'>";
                        echo "<b><img alt=" . $myFile->title . " src='../image.jpg' width='100%' height='100px' /></b>";
                        echo "</div>";
                        echo "<div class='mb-2'><a class='btn btn-success' href='../uploads/" . $root . "' download=" . str_replace(' ', '', $myFile->title) . ">Download</a> 
                        <a href='?deleteFile={$root}&size={$myFile->size}' class='btn btn-danger'>Delete</a>
                        </div>";
                        echo "</div>";
                    } else {
                        echo "<div class='col-lg-3 card m-2' style='height: 200px !important'>";
                        echo "<div class='card-body'>";
                        echo "<b>Title: 🗃 {$myFile->title}</b>";
                        echo "</div>";
                        echo "<div class='mb-2'><a class='btn btn-success'  href='../uploads/" . $root . "' download=" . str_replace(' ', '', $myFile->title) . ">Download</a>
                        <a href='?deleteFile={$root}&size={$myFile->size}' class='btn btn-danger'>Delete</a>
                        </div>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        <?php
        } else {
            echo '<p class="text-center my-3">No content uploaded yet</p>';
        }

        ?>
    </div>
</div>

<?php include_once "./includes/footer.php"; ?>