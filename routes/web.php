<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    Validator::make([
        'type' => 'A',
        'data' => '1.2.3.4',
    ], [
            'data' => [
                'required',
                new App\Rules\DnsRecord(),
            ],
        ]
    )->validate();

    echo 'ok';
})->name('test');
