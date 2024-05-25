<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\SolicitacoesDiscentesImport;
use App\Imports\SolicitacoesDocentesImport;

class CsvImportController extends Controller
{
    public function show_import_discentes_form() {
        return view('import_discentes');
    }

    public function show_import_docentes_form() {
        return view('import_docentes');
    }

    public function show() {
        return view('importacoes');
    }

    public function import_discentes(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        try{
            Excel::import(new SolicitacoesDiscentesImport, $request->file('file'));

            return back()->with('success', 'Arquivo importado.');
        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = 'Linha ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
    
            return back()->withErrors($errorMessages);
        } catch (\Exception $e) {

            return back()->with('fail', 'Deu merda na importação: ' . $e->getMessage());
        }
    }

    public function import_docentes(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new SolicitacoesDocentesImport, $request->file('file'));

        return back()->with('success', 'Arquivo importado.');
    }
}
