<?php
namespace Mss\Cookies;
class Cookie{
    private function __construct ()
    {
    }


    public static function has($key){
        return isset($_COOKIE[$key]);
    }

    public static function set($key,$value){
        $expire = 1 * 365 * 24 * 60 * 60;
        $_COOKIE[$key]=$value;
        setcookie ($key,$value,$expire,'/','',true,true);
    }

    public static function get($key){
        return self::has ($key)?$_COOKIE[$key]:null;
    }

    public static function remove($key){
        if (self::has ($key)){
            unset($_COOKIE[$key]);
            setcookie ($key,null,-1,'/');
        }
    }

    public static function all(){
        return $_COOKIE;
    }

    public static function destroy(){
        foreach (static::all () as $key=>$value){
            static::remove ($key);
        }
    }

}
