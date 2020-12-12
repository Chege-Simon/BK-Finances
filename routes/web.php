<?php

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/my_transactions/{id}',\App\Http\Livewire\MyTransactions::class);
Route::middleware(['auth:sanctum', 'verified'])->get('/my_accounts/{id}',\App\Http\Livewire\MyAccounts::class);

Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function(){
    Route::match(['get','post'], '/admin/all_users', \App\Http\Livewire\AllUsers::class);
    Route::match(['get','post'], '/admin/all_accounts', \App\Http\Livewire\AllAccounts::class);
    Route::match(['get','post'], '/admin/all_transactions', \App\Http\Livewire\AllTransactions::class);
    Route::match(['get','post'], '/admin/all_organisations', \App\Http\Livewire\AllOrganisations::class);
});
