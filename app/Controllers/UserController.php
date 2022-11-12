<?php
namespace App\Controllers;
use Mss\Databases\DB;
use Mss\Http\Response;
use Mss\Http\Server;
use Mss\Urls\Url;
use Mss\Views\View;

class UserController{



    public function home(){

        return <<<HTML
<a href="index">index</a>
HTML;

    }

    public function index(){
        return DB::table ('users')
            ->select('id','name')
            ->where('id','=','1')
            ->where('name','=','mido')
            ->orWhere('name','=','miko')
            ->rightJoin('roles','roles.id','=','users.role_id')
            ->leftJoin('roles','roles.id','=','users.role_id')
            ->join('roles','roles.id','=','users.role_id')
            ->get()->sql();
        return View::render ('admin.dashboard',['name'=>'melad ','lname'=>'shehata']);
    }


}