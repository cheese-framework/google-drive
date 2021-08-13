<?php

/**
 * RaiseUpload
 * Class for upload multiple files.
 * @author Caleb Okpara
 */

class RaiseUpload
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


    public function prepareUpload()
    {
        $streams = [];
        $count = 0;
        if (array_key_exists('name', $this->file) && array_key_exists('tmp_name', $this->file) && array_key_exists('size', $this->file)) {
            $id = uniqid();
            mkdir($this->dir . '/' . $id);
            for ($i = 0; $i < count($this->file['name']); $i++) {
                $currentFile = $this->file['name'][$i];
                $mime = $this->getMimeType($currentFile);
                if ($this->isValidMime($mime)) {
                    $size = $this->file['size'][$i];
                    if ($size <= $this->max) {
                        $encryptFileName = uniqid("drive", true) . "." . $mime;
                        $dest = $this->dir . '/' . $id . '/' . basename($encryptFileName);
                        $data = [];
                        $data[] = $this->dir . '/' . $id;
                        $data[] = $size;
                        $data[] = $this->file['tmp_name'][$i];
                        $data[] = $dest;
                        $data[] = $id;
                        $data[] = $encryptFileName;
                        array_push($streams, $data);
                    } else {
                        $count++;
                        continue;
                    }
                } else {
                    $count++;
                    continue;
                }
            }
        } else {
            throw new Exception("Empty Folder received or none at all", 1);
        }
        return $streams;
    }
}