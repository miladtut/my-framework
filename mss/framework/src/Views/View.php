<?php
namespace Mss\Views;


use Jenssegers\Blade\Blade;
use Mss\Files\File;


class View{

//    public static $path = ROOT.DS.'views'.DS;

    public static function render($view,$data){
//       return self::bladeEngineRender ($view,$data);
       return self::noEngineRender ($view,$data);
    }

    public static function noEngineRender($view,$data){
        $view = str_replace ('.',DS,$view);
        $view_path = File::path ('views')."/$view.php";
        if (!file_exists ($view_path)){
            throw new \Exception("view file $view_path dose\'t exist");
        }
        ob_start ();
        extract ($data);
        include $view_path;
        $content = ob_get_contents ();
        ob_end_clean ();
        return $content;
    }

    public static function bladeEngineRender($view,$data){
        $blade = new Blade(File::path ('views'), File::path ('storage/cache'));
        return $blade->make($view, $data)->render();
    }
}