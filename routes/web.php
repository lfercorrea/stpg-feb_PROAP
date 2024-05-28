<?php

use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SolicitacaoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index'])->name('site.index');

/**
 * importar csv
 */
Route::get('import/discentes', [CsvImportController::class, 'show_import_discentes_form'])->name('import_discentes_form');
Route::post('import/discentes', [CsvImportController::class, 'import_discentes'])->name('import_discentes');
Route::get('import/docentes', [CsvImportController::class, 'show_import_docentes_form'])->name('import_docentes_form');
Route::post('import/docentes', [CsvImportController::class, 'import_docentes'])->name('import_docentes');

Route::get('importacoes', [SiteController::class, 'importacoes'])->name('site.importacoes');
Route::post('importacoes/discentes/drop', [SiteController::class, 'drop_importacoes_discentes'])->name('site.drop_importacoes_discentes');

Route::get('solicitacoes', [SolicitacaoController::class, 'index'])->name('site.solicitacoes');
Route::get('solicitante/{id}', [SiteController::class, 'solicitante'])->name('site.solicitante');