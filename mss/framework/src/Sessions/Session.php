<?php
namespace Mss\Sessions;
class Session{

    /**
     * Session constructor.
     */
    private function __construct ()
    {
    }

    /**
     * start session
     */
    public static function start(){
        if(! session_id ()){
            ini_set ('session.use_only_cookies',true);
            session_start ();
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public static function has($key){
        return isset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @param $value
     */
    public static function add($key,$value){
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public static function get($key){
        return self::has($key)?$_SESSION[$key]:null;
    }

    /**
     * @param $key
     */
    public static function forget($key){
        if (self::has ($key)){
            unset($_SESSION[$key]);
        }
    }

    /**
     * @return mixed
     */
    public static function all(){
        return $_SESSION;
    }

    /**
     * destroy session
     */
    public static function destroy(){
        session_destroy ();
    }


}
