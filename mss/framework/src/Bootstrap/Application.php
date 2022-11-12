<?php
namespace Mss\Bootstrap;

use Mss\Cookies\Cookie;
use Mss\Files\File;
use Mss\Http\Request;
use Mss\Http\Response;
use Mss\Http\Server;
use Mss\Router\Route;
use Mss\Sessions\Session;
use Mss\Urls\Url;
use Mss\Whoops\WhoopsException;

class Application{
    public function __construct ()
    {
    }

    public function start(){
        // handel exceptions
        WhoopsException::handel ();

        //start session
        Session::start ();

        //handel request
        Request::handel ();

        //get route files
        File::require_directory ('routes');

        //handel routes
        $data = Route::handel ();

        Response::output ($data);

    }
}