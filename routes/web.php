<?php

use App\Mail\MensagemTestEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

/*
Route::get('/home', 'HomeController@index')
    ->name('home')
    ->middleware('verified');
*/

Route::get('tarefa/exportacao/{extensao}', 'TarefaController@exportacao')
    ->name('tarefa.exportacao');

Route::resource('tarefa', 'TarefaController')
    ->middleware('verified'); 

Route::get('/mensagem-test', function(){
    return new MensagemTestEmail();
    //Mail::to('archeronfaust@gmail.com')->send(new MensagemTestEmail());
   // return 'enviado com sucesso';
});