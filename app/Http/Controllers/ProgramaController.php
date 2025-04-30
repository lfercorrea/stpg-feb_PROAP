<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\ProjetoCapes;

class ProgramaController extends Controller
{
    public function index() {
        $programas = Programa::orderBy('nome', 'asc')->get();

        $total_verbas = 0;

        foreach ($programas as $programa) {
            $total_verbas += $programa->soma_verbas();
        }

        $programas->total_verbas = $total_verbas;

        return view('programas', [
            'title' => 'Programas',
            'programas' => $programas,
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
            'vice_coordenador' => 'nullable|string|max:255',
        ], [
            'coordenador.required' => 'É necessário definir o nome completo do coordenador do programa.',
            'coordenador.string' => 'O nome completo do coordenador do programa precisa ser uma string.',
            'coordenador.max' => 'O nome completo do coordenador do programa precisa ter, no máximo, 255 caracteres.',
            'vice_coordenador.string' => 'O nome completo do vice-coordenador do programa precisa ser uma string.',
            'vice_coordenador.max' => 'O nome completo do vice-coordenador do programa precisa ter, no máximo, 255 caracteres.',
        ]);

        $programa->coordenador = $request->input('coordenador');
        $programa->vice_coordenador = $request->input('vice_coordenador');

        $programa->save();

        return redirect()
            ->back()
            ->with('success', 'Parâmetros do programa atualizados.');
    }
}
