<?php

class FileProcessor
{

    public static function upload($streams)
    {
        $size = 0;
        if (count($streams) > 0) {
            foreach ($streams as $stream) {
                if (move_uploaded_file($stream[2], $stream[3])) {
                    $size += $stream[1];
                }
            }
            return $size;
        } else {
            throw new Exception("Streams received is empty");
        }
    }

    public static function flatUpload($stream)
    {
        if ($stream != null) {
            if (move_uploaded_file($stream[2], $stream[3])) {
                return $stream[1];
            }
        } else {
            throw new Exception("Streams received is empty");
        }
    }
}