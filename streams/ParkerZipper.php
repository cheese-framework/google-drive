<?php

class ParkerZipper extends ZipArchive
{
    public function addDir($location, $name)
    {
        $this->addEmptyDir($name);
        $this->addDirDo($location, $name);
    }

    public function addDirDo($location, $name)
    {
        $name .= "/";
        $location .= "/";
        $dir = opendir($location);
        while ($file = readdir($dir)) {
            if ($file == '.'  || $file == '..') continue;
            $do = (filetype($location . $file) == 'dir') ? 'addDir' : 'addFile';
            $this->$do($location . $file, $name .  $file);
        }
    }

    public function download($filename)
    {
        if (file_exists($filename)) {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-type: application/zip");
            header("Content-Disposition: attachment; filename=$filename");

            readfile($filename);

            // delete file
            unlink($filename);
        }
    }
}
