<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceController2;

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

Route::get('/invoice', [InvoiceController::class, 'generateInvoice']);
Route::get('/invoice', [InvoiceController::class, 'genInvoice']);

//invoice System
Route::get('/invoices', [InvoiceController2::class, 'index']);
Route::get('/invoices/create', 'InvoiceController2@create');
Route::post('/invoices', 'InvoiceController2@store');
Route::get('/invoices/{id}', 'InvoiceController2@show');
Route::get('/invoices/{id}/edit', 'InvoiceController2@edit');
Route::put('/invoices/{id}', 'InvoiceController2@update');
Route::delete('/invoices/{id}', 'InvoiceController2@destroy');
Route::get('/invoices/{id}/generate-pdf', 'InvoiceController2@generatePdf');
