<?php

use App\Http\Controllers\PDFExporterController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => ['auth']], function(){
    Route::get('/print/project-sumary', [PrintController::class, 'projectSummary'])->name('print.project');
    Route::get('/print/expenses', [PrintController::class, 'expenses'])->name('print.expenses');

    Route::get('/pdf/expenses/{id}', [PDFExporterController::class, 'projectExpensesExport'])->name('pdf.expenses');

    
});