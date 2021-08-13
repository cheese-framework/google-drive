<?php

/**
 * Deletes files and folders
 */

class Destroyer
{

    /**
     * @throws Exception
     */
    public function flatDestroy($dir, $file)
    {
        if (file_exists($dir . '/' . $file)) {
            @@unlink($dir . '/' . $file);
        } else {
            throw new Exception("No such file in the server");
        }
    }

    public function raiseDestroy($dir)
    {
        try {
            $directory = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    @@rmdir($file);
                } else {
                    @@unlink($file);
                }
            }
            @@rmdir($dir);
        } catch (Exception $e) {
            throw new Exception("No such directory in the server");
        }
    }
}