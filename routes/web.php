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

Route::get('/Dashboard', function () {
    return view('DASHBOARD.homepage');
})->name('dashboard');

// Inventory page route
Route::get('/inventory', function () {
    return view('DASHBOARD.inventory');
})->name('inventory');

Route::get('/inventory/list', function () {
    return view('DASHBOARD.inventory_list');
})->name('inventory.list');

Route::get('/product/add', function () {
    return view('PRODUCT.add_product');
})->name('product.add');

Route::get('/POS', function () {
    return view('DASHBOARD.POS');
})->name('POS');

Route::get('/Suppliers', function () {
    return view('DASHBOARD.suppliers');
})->name('suppliers');

Route::get('/Suppliers/List', function () {
    return view('DASHBOARD.suppliers_list');
})->name('suppliers.list');
