<?php
namespace Mss\Files;
class File{
    private function __construct ()
    {
    }

    public static function root(){
        return ROOT;
    }

    public static function ds(){
        return DS;
    }

    public static function path($path){
        $path = str_replace (['/','\\'],self::ds (),$path);
        return self::root ().self::ds ().$path;
    }

    public static function exists($path){
        return file_exists (self::path ($path));
    }

    public static function require_file($path){
        if (self::exists ($path)){
            return require self::path ($path);
        }
    }

    public static function include_file($path){
        if (self::exists ($path)){
            include self::path ($path);
        }
    }

    public static function require_directory($path){
        $path = trim (trim ($path,'/'),'\\');
        $arr = scandir (self::path ($path));
        $files = array_diff ($arr,['.','..']);
        foreach ($files as $file){
            self::require_file ($path.self::ds ().$file);
        }
    }
}