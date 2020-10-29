<?php

use App\Import;
use App\Jobs\ImportData;
use App\Jobs\ProcessData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

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

Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::get('/', 'MovieController@index');

Route::get('/movie/{id}', 'MovieController@show')->name('movie.show');

Route::post('/search', 'SearchController@index')->name('search');

Auth::routes();

Route::get('/admin/queue', function () {

    // dispatch(new ImportData);
    // dispatch(new ProcessData);
});

Route::get('/home', 'HomeController@index')->name('home');
