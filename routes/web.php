<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\SolicitanteController;
use App\Http\Controllers\RelatorioController;

Route::get('/', [SiteController::class, 'index'])->name('site.index');

Route::get('import/menu', [CsvImportController::class, 'import_menu'])->name('import_menu');
Route::get('import/discentes', [CsvImportController::class, 'show_import_discentes_form'])->name('import_discentes_form');
Route::post('import/discentes', [CsvImportController::class, 'import_discentes'])->name('import_discentes');
Route::get('import/docentes', [CsvImportController::class, 'show_import_docentes_form'])->name('import_docentes_form');
Route::post('import/docentes', [CsvImportController::class, 'import_docentes'])->name('import_docentes');
Route::get('importacoes', [CsvImportController::class, 'importacoes'])->name('site.importacoes');

Route::get('programas', [ProgramaController::class, 'index'])->name('site.programas.index');
Route::post('programas/update', [ProgramaController::class, 'update'])->name('site.programas.update');

Route::get('solicitacoes', [SolicitacaoController::class, 'index'])->name('site.solicitacoes.index');
Route::get('solicitacao/{id}', [SolicitacaoController::class, 'show'])->name('site.solicitacao.show');
Route::get('solicitacao/{id}/recibo/{nid}', [SolicitacaoController::class, 'recibo'])->name('site.solicitacao.recibo');
Route::post('solicitacao/{id}/update', [SolicitacaoController::class, 'update'])->name('site.solicitacao.update');
Route::post('solicitacao/{id}', [NotaController::class, 'store'])->name('site.nota.store');
Route::get('solicitacao/{solicitacao_id}/nota/{nota_id}', [NotaController::class, 'destroy'])->name('site.nota.destroy');

Route::get('solicitantes', [SolicitanteController::class, 'index'])->name('site.solicitantes.index');
Route::get('solicitante/{id}', [SolicitanteController::class, 'show'])->name('site.solicitante.show');
Route::get('solicitante/edit/{id}', [SolicitanteController::class, 'edit'])->name('site.solicitante.edit');
Route::put('solicitante/store/{id}', [SolicitanteController::class, 'store'])->name('site.solicitante.store');

Route::get('relatorio', [RelatorioController::class, 'index'])->name('site.relatorio.index');