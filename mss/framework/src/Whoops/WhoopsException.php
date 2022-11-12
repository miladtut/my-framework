<?php
namespace Mss\Whoops;
class WhoopsException{
    public static function handel(){
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}
