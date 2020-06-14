<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // return view('home');
    // return view('login');
    if(Auth::check('web')){
        return view('home');
    }else{
       return redirect('/login');
    }
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/admin',  function () {
    // return view('home');
    return view('adminpanel.main');
});
Route::prefix('/admin', function(){

});

