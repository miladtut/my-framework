<?php
namespace Mss\Sessions;
class Flash{

    private static $prefix = 'flash_';

    public static function set($key,$value){
        $key = self::$prefix.$key;
        Session::add ($key,$value);
    }

    public static function get($key){
        $key = self::$prefix.$key;
        $out = null;
        if (Session::has ($key)){
            $out = Session::get ($key);
            Session::forget ($key);
        }
        return $out;
    }
}
