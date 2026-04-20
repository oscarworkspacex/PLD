<?php
use App\Http\Controllers\Admin\ExcelController;
use App\Http\Controllers\Admin\AlertaAnonimaController;
use App\Http\Controllers\Admin\SystemUserController;
use App\Http\Controllers\ClientCaptureController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('excel',[ExcelController::class,'index'])->name('admin.excel.index');
Route::post('excel/upload', [ExcelController::class, 'upload'])->name('admin.excel.upload');
Route::get('excel/files', [ExcelController::class, 'files'])->name('admin.excel.files');
Route::get('excel/file-preview', [ExcelController::class, 'preview'])->name('admin.excel.preview');
Route::get('excel/file-preview/rows', [ExcelController::class, 'previewRows'])->name('admin.excel.preview.rows');
Route::get('excel/list', [ExcelController::class, 'listFiles'])->name('admin.excel.list');
Route::delete('excel/delete', [ExcelController::class, 'deleteFile'])->name('admin.excel.delete');
Route::get('search-data', [ExcelController::class,'searchData'])->name('admin.excel.search.data');
Route::get('client-capture', [ClientCaptureController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.index');
Route::get('client-capture/selector', [ClientCaptureController::class, 'selector'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.selector');
Route::get('client-capture/prestamos', [ClientCaptureController::class, 'prestamos'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.prestamos');
Route::patch('client-capture/{clientCapture}/prestamo-status', [ClientCaptureController::class, 'updatePrestamoStatus'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.prestamo-status.update');
Route::post('client-capture/{clientCapture}/anexo', [ClientCaptureController::class, 'uploadAnexo'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.anexo.upload');
Route::get('client-capture/{clientCapture}/anexo', [ClientCaptureController::class, 'downloadAnexo'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.anexo.download');
Route::get('client-capture/{clientCapture}/anexos', [ClientCaptureController::class, 'listAnexos'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.anexos.list');
Route::get('client-capture/{clientCapture}/anexos/{anexo}', [ClientCaptureController::class, 'downloadAnexoItem'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.anexos.download');
Route::patch('client-capture/{clientCapture}/validacion', [ClientCaptureController::class, 'updateValidation'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.validacion.update');
Route::get('client-capture/{clientCapture}', [ClientCaptureController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.show');
Route::patch('client-capture/{clientCapture}', [ClientCaptureController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.update');
Route::delete('client-capture/{clientCapture}', [ClientCaptureController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.destroy');
Route::post('client-capture', [ClientCaptureController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.client-capture.store');
Route::get('system-users', [SystemUserController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.system-users.index');
Route::post('system-users', [SystemUserController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.system-users.store');
Route::patch('system-users/{user}', [SystemUserController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('admin.system-users.update');
Route::patch('system-users/{user}/password', [SystemUserController::class, 'updatePassword'])
    ->middleware(['auth', 'verified'])
    ->name('admin.system-users.password.update');
Route::get('alertas-anonimas', [AlertaAnonimaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.alertas-anonimas.index');
Route::post('alertas-anonimas', [AlertaAnonimaController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.alertas-anonimas.store');
Route::patch('alertas-anonimas/{alertaAnonima}/leida', [AlertaAnonimaController::class, 'markAsRead'])
    ->middleware(['auth', 'verified'])
    ->name('admin.alertas-anonimas.mark-read');
Route::delete('alertas-anonimas/{alertaAnonima}', [AlertaAnonimaController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('admin.alertas-anonimas.destroy');
Route::get('alertas/download-xml', [AlertaAnonimaController::class, 'downloadXml'])
    ->middleware(['auth', 'verified'])
    ->name('admin.alertas.download-xml');
Route::get('alertas/download-xml-rules', [AlertaAnonimaController::class, 'downloadXmlRules'])
    ->middleware(['auth', 'verified'])
    ->name('admin.alertas.download-xml-rules');