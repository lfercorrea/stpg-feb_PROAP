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
        $coordenadores = $request->input('coordenadores', []);
        $projetos_capes = $request->input('projetos_capes', []);
        
        foreach($coordenadores as $id => $coordenador) {
            $programa = Programa::find($id);
            
            if($programa) {
                $programa->coordenador = $coordenador;
                $programa->save();
            }
        }
        
        foreach($projetos_capes as $id => $projeto_capes) {
            $programa = Programa::find($id);
            
            if($programa) {
                $programa->projeto_capes = $projeto_capes;
                $programa->save();
            }
        }
        
        foreach($saldos as $id => $saldo) {
            $programa = Programa::find($id);
            
            if($programa) {
                $programa->saldo_inicial = $saldo;
                $programa->save();
            }
        }

        return redirect()->route('site.programas.index')
            ->with('success', 'Programas atualizados.');
    }
}
