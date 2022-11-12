<?php
namespace Mss\Urls;
use Mss\Http\Request;
use Mss\Http\Server;

class Url{
    private function __construct ()
    {
    }

    public static function path($path){
        return Request::baseUrl ().'/'.trim ($path,'/');
    }

    public static function previous(){
        return Server::get ('HTTP_REFERER');
    }

    public static function redirect($to){
        $path = self::path ($to);
        header ('location: '.$path);
        exit();
    }
}
