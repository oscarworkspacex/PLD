<?php
use App\Http\Controllers\Admin\ExcelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('excel',[ExcelController::class,'index'])->name('admin.excel.index');
Route::post('excel/upload', [ExcelController::class, 'upload'])->name('admin.excel.upload');
Route::get('excel/files', [ExcelController::class, 'files'])->name('admin.excel.files');
Route::get('excel/list', [ExcelController::class, 'listFiles'])->name('admin.excel.list');
Route::delete('excel/delete', [ExcelController::class, 'deleteFile'])->name('admin.excel.delete');
Route::get('search-data', [ExcelController::class,'searchData'])->name('admin.excel.search.data');