<?php

class Analytics
{

    public static function getMyFiles($user_id)
    {
        $db = Database::getInstance();
        $db->query("SELECT * FROM uploads WHERE user=? AND file is not NULL");
        $db->bind(1, $user_id);
        $data = $db->resultset();
        if ($db->rowCount() > 0)
            return $data;
        return NULL;
    }


    public static function deleteFlat($dir, $file, $user_id, $size)
    {
        $destroy = new Destroyer();
        try {
            $destroy->flatDestroy($dir, $file);
            $s = Misc::getTotalSizeUploaded($user_id);
            $size = $s - $size;
            Misc::setTotalSizeUploaded($size, $user_id);
            $db = Database::getInstance();
            $db->query("DELETE FROM uploads WHERE user=? AND file=?");
            $db->bind(1, $user_id);
            $db->bind(2, $file);
            $db->execute();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function deleteRaise($dir, $folder, $user_id, $size)
    {
        $destroy = new Destroyer();
        try {
            $destroy->raiseDestroy($dir);
            $s = Misc::getTotalSizeUploaded($user_id);
            $size = $s - $size;
            Misc::setTotalSizeUploaded($size, $user_id);
            $db = Database::getInstance();
            $db->query("DELETE FROM uploads WHERE folder=? AND user=?");
            $db->bind(1, $folder);
            $db->bind(2, $user_id);
            $db->execute();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
