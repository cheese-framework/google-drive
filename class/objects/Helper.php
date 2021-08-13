<?php 

class Helper {
    public static function dump(...$data) {
       foreach ($data as $d) {
        echo "<pre>";
        print_r($d);
        echo "</pre>";
       }
        exit;
    }
 }