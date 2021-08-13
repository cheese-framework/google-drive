<?php

class Misc
{
    public static function getMB($value)
    {
        if ($value > 0) {
            return round($value / 1048576, 1) . "MB";
        } else {
            return "0MB";
        }
    }

    public static function getKB($value)
    {
        if ($value > 0) {
            return number_format($value / 1000) . "KB";
        } else {
            return "0KB";
        }
    }

    public static function setTotalSizeUploaded($size, $id)
    {
        $db = Database::getInstance();
        $db->query("UPDATE users SET totalSizeUploaded=? WHERE id=?");
        $db->bind(1, $size);
        $db->bind(2, $id);
        return $db->execute();
    }

    public static function getTotalSizeUploaded($id)
    {
        $db = Database::getInstance();
        $db->query("SELECT totalSizeUploaded FROM users WHERE id=?");
        $db->bind(1, $id);
        $data = $db->single();
        return ($db->rowCount() > 0) ? $data->totalSizeUploaded : 0;
    }

    public static function getFolder($id)
    {
        $db = Database::getInstance();
        $db->query("SELECT folder FROM users WHERE id=?");
        $db->bind(1, $id);
        $data = $db->single();
        if ($db->rowCount() > 0) {
            return $data->folder;
        } else {
            return "uploads";
        }
    }

    public static function upload($title, $type, $path, $size, $folder, $userId, $file)
    {
        $db = Database::getInstance();
        $db->query("INSERT INTO uploads (title,type,path,size,folder,user, file) VALUES(?,?,?,?,?,?,?)");
        $db->bind(1, $title);
        $db->bind(2, $type);
        $db->bind(3, $path);
        $db->bind(4, $size);
        $db->bind(5, $folder);
        $db->bind(6, $userId);
        $db->bind(7, $file);
        $db->execute();
        return $db->rowCount() > 0;
    }
}