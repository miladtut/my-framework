<?php

use Mss\Router\Route;

Route::get ('/index','UserController@index');
Route::get ('/','UserController@home');

//Route::prefix ('admin',function (){
//    Route::middleware ('AdminMiddleware|UserMiddleware',function (){
//        Route::get ('dashboard/{id}/user',function ($id){
//            print_r ($id);
//        });
//        Route::prefix ('emp',function (){
//            Route::post ('profile',function (){
//
//            });
//        });
//        Route::get ('users','UserController@index');
//    });
//
//
//});
