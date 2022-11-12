<?php
namespace Mss\Http;
class Request{

    public static $url;
    public static $base_url;
    public static $full_url;
    public static $query_string;
    public static $scheme;
    public static $host;

    private function __construct ()
    {
    }

    public static function handel(){
        self::set_base_url ();
        self::set_full_url ();

    }

    public static function set_base_url(){
        $scheme = Server::get ('REQUEST_SCHEME');
        $host = Server::get ('HTTP_HOST');
        $script_name = Server::get ('SCRIPT_NAME');
        $script_name = trim (trim (dirname ($script_name),'/'),'\\');
        $base_url = $scheme.'://'.$host.'/'.$script_name;
        $base_url = trim (trim ($base_url,'/'),'\\');
        self::$base_url = $base_url;
        return self::$base_url;
    }

    public static function set_full_url(){
        $url = Server::get ('REQUEST_URI');
        $script_name = dirname (Server::get ('SCRIPT_NAME'));
        $url = str_replace ($script_name,'',$url);
        self::$full_url = trim (trim ($url,'/'),'\\');
        if (strpos (self::$full_url,'?')){
            list(self::$url,self::$query_string) = explode ('?',self::$full_url);
        }else{
            self::$url = self::$full_url;
        }

    }


    public static function has($key){
        return (isset($_REQUEST[$key]));
    }

    public static function get($key){
        return self::has ($key) ? $_REQUEST[$key] : null;
    }

    public static function method(){
        return Server::get('REQUEST_METHOD');
    }

    public static function all(){
        return $_REQUEST;
    }

    public static function set($key,$value){
        $_REQUEST[$key]=$value;
    }

    public static function url(){
        return self::$url?:'/';
    }

    public static function is($match){
        $match = "#^$match#";
        return preg_match ($match,self::url ());
    }

    public static function previous(){
        return Server::get ('HTTP_REFERER');
    }

    public static function baseUrl(){
        return self::$base_url;
    }
}