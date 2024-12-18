<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanStockController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('sign-in', ['title' => 'Login']);
})->name('index');

//Route Auth
//Registration
Route::get('/sign-up', [AuthController::class, 'tampilRegistrasi'])->name('sign-up.tampil');
Route::post('/sign-up/submit', [AuthController::class, 'submitRegistrasi'])->name('sign-up.submit');
Route::get('/sign-up', [AuthController::class, 'tampilRole'])->name('sign-up.tampil');
//Login
Route::get('/sign-in', [AuthController::class, 'tampilLogin'])->name('sign-in.tampil');
Route::post('/sign-in/submit', [AuthController::class, 'submitLogin'])->name('sign-in.submit');



Route::middleware(['auth'])->group(function(){

    Route::get('/dashboard', [DashboardController::class, 'tampil'])->name('dashboard.tampil');
    //Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Route User
    Route::middleware(['role:Admin'])->group(function(){
        Route::get('/user', [UserController::class, 'tampil'])->name('user.tampil');
        Route::get('/user/tambah', [UserController::class, 'tambah'])->name('user.tambah');
        Route::post('/user/submit', [UserController::class, 'submit'])->name('user.submit');
        Route::get('user/edit{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('user/update{id}', [UserController::class, 'update'])->name('user.update');
        Route::post('user/delete{id}', [UserController::class, 'delete'])->name('user.delete');
    });

// Route Product
    Route::middleware(['role:Admin,Manajer Gudang'])->group(function(){
        Route::get('/product', [ProductController::class, 'tampil'])->name('product.tampil');
    });
    Route::middleware(['role:Admin'])->group(function(){
        Route::get('/product/tambah', [ProductController::class, 'tambah'])->name('product.tambah');
        Route::post('/product/submit', [ProductController::class, 'submit'])->name('product.submit');
        Route::get('product/edit{id}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('product/update{id}', [ProductController::class, 'update'])->name('product.update');
        Route::post('product/delete{id}', [ProductController::class, 'delete'])->name('product.delete');
        Route::get('/product/export/excel', [ProductController::class, 'exportToExcel'])->name('product.export.excel');
        Route::post('/product/import', [ProductController::class, 'import'])->name('product.import');
    });

// Route product attribute
    Route::middleware(['role:Admin,Manajer Gudang'])->group(function(){
        Route::get('/product/detail/{id}', [ProductAttributeController::class, 'tampil'])->name('product.detail.tampil');
    });
    Route::middleware(['role:Admin'])->group(function(){
        Route::get('/product/tambah', [ProductAttributeController::class, 'tambah'])->name('product.detail.tambah');
        Route::post('/product/detail/submit', [ProductAttributeController::class, 'submit'])->name('product.detail.submit');
    });
// Route Supplier
    Route::middleware(['role:Admin,Manajer Gudang'])->group(function(){
        Route::get('/supplier', [SupplierController::class, 'tampil'])->name('supplier.tampil');
    });
    Route::middleware(['role:Admin'])->group(function(){
        Route::get('/supplier/tambah', [SupplierController::class, 'tambah'])->name('supplier.tambah');
        Route::post('/supplier/submit', [SupplierController::class, 'submit'])->name('supplier.submit');
        Route::get('supplier/edit{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::post('supplier/update{id}', [SupplierController::class, 'update'])->name('supplier.update');
        Route::post('supplier/delete{id}', [SupplierController::class, 'delete'])->name('supplier.delete');
    });

// Route of Category
    Route::middleware(['role:Admin'])->group(function(){
        Route::get('/product/category', [CategoryController::class, 'tampil'])->name('product.category.tampil');
        Route::post('/product/category/submit', [CategoryController::class, 'submit'])->name('product.category.submit');
        Route::get('product/category/edit{id}', [CategoryController::class, 'edit'])->name('product.category.edit');
        Route::post('product/category/update{id}', [CategoryController::class, 'update'])->name('product.category.update');
        Route::post('product/category/delete{id}', [CategoryController::class, 'delete'])->name('product.category.delete');
    });

// Route stock transaction
    
    Route::get('/stock/transaction/{type}', [TransactionController::class, 'tampil'])->name('stock.transaction.tampil');

    Route::middleware(['role:Manajer Gudang'])->group(function(){
        Route::get('/stock/transaction/tambah', [TransactionController::class, 'tambah'])->name('stock.transaction.tambah');
        Route::post('/stock/transaction/submit/{type}', [TransactionController::class, 'submit'])->name('stock.transaction.submit');
        Route::post('/stock/transaction/delete/{type}/{id}', [TransactionController::class, 'delete'])->name('stock.transaction.delete');
    });
    Route::middleware(['role:Staff Gudang'])->group(function(){
        Route::post('/stock/transaction/update-status/{id}', [TransactionController::class, 'updateStatus'])->name('transaction.updateStatus');
    });
    Route::post('/stock/transaction/updateStockSementara/{id}', [TransactionController::class, 'updateStockSementara'])->name('updateStock');
// Route stock opname
    Route::middleware(['role:Admin,Manajer Gudang'])->group(function(){
        Route::get('/stock/opname', [StockOpnameController::class, 'tampil'])->name('stock.opname.tampil');
        
    });
// Route Laporan dan Export
    Route::middleware(['role:Admin,Manajer Gudang'])->group(function(){
        Route::get('/laporan/transaction/{type}', [LaporanController::class, 'tampil'])->name('laporan.transaction.tampil');
        Route::get('/laporan/stock', [LaporanStockController::class, 'tampil'])->name('laporan.stock.tampil');
        Route::get('/laporan/transaction/export/excel/{type}', [LaporanController::class, 'exportToExcel'])->name('laporan.transaction.export.excel');
        Route::get('/laporan/transaction/export/pdf/{type}', [LaporanController::class, 'exportToPDF'])->name('laporan.transaction.export.pdf');
        Route::get('/laporan/stock/export/excel', [LaporanStockController::class, 'exportExcel'])->name('laporan.stock.export.excel');
        Route::get('/laporan/stock/export/pdf', [LaporanStockController::class, 'exportPDF'])->name('laporan.stock.export.pdf');
        Route::get('/laporan/activity/export/excel', [ActivityController::class, 'exportToExcel'])->name('exportExcel');
        Route::get('/laporan/activity/export/pdf', [ActivityController::class, 'exportToPDF'])->name('exportPdf');
    });

    Route::middleware(['check', 'role:Admin'])->group(function(){
        Route::get('/laporan/activity', [ActivityController::class, 'tampil'])->name('activity.tampil');
        Route::post('/laporan/activity/submit', [ActivityController::class, 'submit'])->name('activity.submit');
    });

    Route::middleware(['check', 'role:Admin'])->group(function(){
        Route::get('/settings', [SettingsController::class, 'tampil'])->name('settings');
        Route::post('/settings/submit', [SettingsController::class, 'submit'])->name('submit');
        Route::post('/settings/update/{id}', [SettingsController::class, 'update'])->name('update');
    });
});

    
