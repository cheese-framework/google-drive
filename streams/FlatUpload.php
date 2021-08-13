<?php

/**
 * FlatUpload
 * Class for upload single files.
 * @author Caleb Okpara
 */

class FlatUpload
{
    private $acceptableExtensions = [];
    private $dir;
    private $file;
    private $max;

    public function __construct($dir, $file, $accepts, $maxSizePerUpload = 30000000)
    {
        $this->dir = $dir;
        $this->file = $file;
        $this->acceptableExtensions = $accepts;
        $this->max = $maxSizePerUpload;
    }

    /**
     * getMimeType.
     * This returns the accurate file extension of the file provided
     * @return string|null
     */
    private function getMimeType($filename)
    {
        $temp = explode('.', $filename);
        $mime = strtolower(end($temp));
        return $mime;
    }

    /**
     * isValidMime.
     * Checks if the mime is in the acceptable mimes array
     * @return bool
     */
    private function isValidMime($mime)
    {
        return in_array($mime, $this->acceptableExtensions);
    }

    private function getSize($file)
    {
        return $file['size'];
    }

    public function upload()
    {
        $data = [];
        $name = $this->file['name'];
        $fileName = $this->file['tmp_name'];
        $mime = $this->getMimeType($name);
        if ($this->isValidMime($mime)) {
            if ($this->getSize($this->file) <= $this->max) {
                $encryptFileName = uniqid("drive", true) . "." . $mime;
                $dest = $this->dir . '/' . basename($encryptFileName);
                $data[] = $this->dir;
                $data[] = $this->getSize($this->file);
                $data[] = $fileName;
                $data[] = $dest;
                $data[] = $encryptFileName;
            } else {
                throw new Exception("File size is larger than the max size of 10MB", 1);
            }
        } else {
            throw new UnexpectedValueException("The file is not a eligible for upload. File extension: '.$mime'", 1);
        }

        return $data;
    }
}