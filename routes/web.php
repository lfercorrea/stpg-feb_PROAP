<?php

use App\Http\Controllers\CsvImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('import', [CsvImportController::class, 'showImportForm']);
Route::post('import', [CsvImportController::class, 'import'])->name('import');