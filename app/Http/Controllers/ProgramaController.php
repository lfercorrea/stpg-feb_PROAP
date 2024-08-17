<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\ProjetoCapes;

class ProgramaController extends Controller
{
    public function index() {

        return view('programas', [
            'title' => 'Programas',
            'programas' => Programa::orderBy('nome', 'asc')->get(),
        ]);
    }

    public function edit(string $id) {
        $programa = Programa::with(['projetos_capes' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->findOrFail($id);

        return view('programa_edit', [
            'title' => 'Parâmetros do programa - ' . $programa->nome,
            'programa' => $programa,
        ]);
    }

    public function store(Request $request, string $id) {
        $programa = Programa::findOrFail($id);

        $request->validate([
            'coordenador' => 'required|string|max:255',
            'vice_coordenador' => 'required|string|max:255',
            'projeto_capes' => 'required|string|max:255',
            'saldo_inicial' => 'required|string|max:255',
        ], [
            'coordenador.required' => 'É necessário definir o nome completo do coordenador do programa.',
            'coordenador.string' => 'O nome completo do coordenador do programa precisa ser uma string.',
            'coordenador.max' => 'O nome completo do coordenador do programa precisa ter, no máximo, 255 caracteres.',
            'vice_coordenador.required' => 'É necessário definir o nome completo do vice-coordenador do programa.',
            'vice_coordenador.string' => 'O nome completo do vice-coordenador do programa precisa ser uma string.',
            'vice_coordenador.max' => 'O nome completo do vice-coordenador do programa precisa ter, no máximo, 255 caracteres.',
            'projeto_capes.required' => 'É necessário definir o código do projeto CAPES do programa.',
            'projeto_capes.string' => 'O código do projeto CAPES do programa precisa ser uma string.',
            'projeto_capes.max' => 'O código do projeto CAPES do programa precisa ter, no máximo, 255 caracteres.',
            'saldo_inicial.required' => 'É necessário definir o saldo inicial do programa.',
            'saldo_inicial.string' => 'O saldo inicial do programa precisa ser uma string.',
            'saldo_inicial.max' => 'O saldo inicial do programa precisa ter, no máximo, 255 caracteres.',
        ]);

        $programa->coordenador = $request->input('coordenador');
        $programa->vice_coordenador = $request->input('vice_coordenador');
        $programa->projeto_capes = $request->input('projeto_capes');
        $programa->saldo_inicial = $request->input('saldo_inicial');

        $programa->save();

        return redirect()
            ->back()
            ->with('success', 'Parâmetros do programa atualizados.');
    }
    
    /**
     * Desativado. Após reestruturação dos programas, vários parâmetros dinâmicos foram acrescentados,
     * o que viabilizou a formulação de uma página individual para edição dos parâmetros de cada programa.
     * Além disso, o parâmetro projeto_capes inclui a informação de um dos vários projetos cadastrados para
     * cada programa, na tabela projetos_capes (1:n) separada. Assim, o método update() foi substituído pelos
     * métodos edit() e store().
     */
    // public function update(Request $request) {
    //     $saldos = $request->input('saldos', []);
    //     $coordenadores = $request->input('coordenadores', []);
    //     $projetos_capes = $request->input('projetos_capes', []);
        
    //     foreach($coordenadores as $id => $coordenador) {
    //         $programa = Programa::find($id);
            
    //         if($programa) {
    //             $programa->coordenador = $coordenador;
    //             $programa->save();
    //         }
    //     }
        
    //     foreach($projetos_capes as $id => $projeto_capes) {
    //         $programa = Programa::find($id);
            
    //         if($programa) {
    //             $programa->projeto_capes = $projeto_capes;
    //             $programa->save();
    //         }
    //     }
        
    //     foreach($saldos as $id => $saldo) {
    //         $programa = Programa::find($id);
            
    //         if($programa) {
    //             $programa->saldo_inicial = $saldo;
    //             $programa->save();
    //         }
    //     }

    //     return redirect()->route('site.programas.index')
    //         ->with('success', 'Programas atualizados.');
    // }
}
