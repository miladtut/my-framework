<?php
namespace Mss\Router;

use Mss\Http\Request;

class Route{

    private static $routes_container = [];
    private static $middleware ;
    private static $prefix ;

    private function __construct ()
    {
    }


    protected static function match_url($request_url,$route_url){
        $route_url = preg_replace ('#{(.*?)}#','(.*?)',$route_url);
        $route_url = "#^$route_url$#";
        if(preg_match ($route_url,$request_url,$matches)){
            array_shift ($matches);
            return ['matched'=>true,'params'=>$matches];
        }
        return ['matched'=>false,'params'=>[]];
    }

    public static function add($method,$url,$callback){
        $url = self::$prefix.'/'.trim ($url,'/|\\');
        $url = trim ($url,'/')?:'/';
        if(strpos ($method,'|')){
            foreach (explode ('|',$method) as $m){
                self::$routes_container[]=[
                    'url'=>$url,
                    'method'=>strtoupper ($m),
                    'callback'=>$callback,
                    'middleware'=>self::$middleware
                ];
            }
        }else{
            self::$routes_container[]=[
                'url'=>$url,
                'method'=>strtoupper ($method),
                'callback'=>$callback,
                'middleware'=>self::$middleware
            ];
        }
    }

    public static function handel(){
        $url = Request::url ();
        $matched = false;
        foreach (self::$routes_container as $route){
            $result = self::match_url($url,$route['url']);
            if ($result['matched']){
                $matched = true;
                foreach ($result['params'] as $param){
                    if (strpos ($param,'/')){
                        $matched = false;
                    }
                }
                if ($route['method'] != Request::method ()){
                    throw new \Exception("not allowed method " . Request::method () . " for this route ".$route['url']." allowed method is " . $route['method']);
                }
                return self::invoke ($route,$result['params']);

            }
        }
        if (!$matched){
            die('not found page');
        }

    }

    public static function get($url,$callback){
        self::add ('get',$url,$callback);
    }
    public static function post($url,$callback){
        self::add ('post',$url,$callback);
    }
    public static function any($url,$callback){
        self::add ('get|post',$url,$callback);
    }

    public static function prefix($prefix,$callback){
        $parent_prefix = self::$prefix;
        self::$prefix .= '/' . $prefix;
        if(is_callable ($callback)) {
            call_user_func ($callback);
        }else{
            throw new \BadMethodCallException("function $callback not valid");
        }
        self::$prefix = $parent_prefix;
    }

    public static function middleware($middleware,$callback){
        $parent_middleware = self::$middleware;
        self::$middleware .='|' . trim ($middleware,'|');
        self::$middleware = trim (self::$middleware,'|');
        if (is_callable ($callback)){
            call_user_func ($callback);
        }else{
            throw new \BadMethodCallException("function $callback not valid");
        }
        self::$middleware = $parent_middleware;

    }



    public static function invoke($route,$params=[]){
        self::execute_middleware ($route['middleware']);
        $callback = $route['callback'];
        if(is_callable ($callback)){
            call_user_func_array ($callback,$params);
        }elseif(strpos ($callback,'@')){
            list($controller,$method) = explode ('@',$callback);
            $controller = 'App\\Controllers\\'.$controller;
            if (class_exists ($controller)){
                $obj = new $controller;
                if (method_exists ($obj,$method)){
                   return call_user_func_array ([$obj,$method],$params);
                }else{
                    throw new \BadFunctionCallException("method $method not found");
                }
            }else{
                throw new \ReflectionException("class $controller not found");
            }
        }else{
            throw new \InvalidArgumentException('invalid callback');
        }
    }


    public static function execute_middleware($middlewares){
        foreach (explode ('|',$middlewares) as $middleware){
            if($middleware){
                $middleware = "App\Middlewares\\".$middleware;
                if (class_exists ($middleware)){
                    $obj = new $middleware;
                    if (method_exists ($obj,'handel')){
                        call_user_func_array ([$obj,'handel'],[]);
                    }else{
                        throw new \BadFunctionCallException("method handel not found");
                    }
                }else{
                    throw new \ReflectionException("class $middleware not found");
                }
            }
        }


    }





    public static function routes(){
        return self::$routes_container;
    }
}
