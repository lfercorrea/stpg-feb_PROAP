<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;

class ProgramaController extends Controller
{
    public function index() {
        return view('programas', [
            'title' => 'Programas',
            'programas' => Programa::orderBy('nome', 'asc')->get(),
        ]);
    }
    
    public function update(Request $request) {
        $saldos = $request->input('saldos', []);

        foreach($saldos as $id => $value) {
            $programa = Programa::find($id);
            
            if($programa) {
                $programa->saldo_inicial = $value;
                $programa->save();
            }
        }

        return redirect()->route('site.programas.index')
            ->with('success', 'Programas atualizados.');
    }
}
