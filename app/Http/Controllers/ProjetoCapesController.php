<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\ProjetoCapes;

class ProjetoCapesController extends Controller
{
    public function store(Request $request, $programa_id) {
        $projeto = new ProjetoCapes();

        $request->validate([
            'codigo' => 'required|string|max:255',
            'verba' => 'required|numeric',
        ], [
            'codigo.required' => 'É necessário fornecer o código do projeto.',
            'codigo.string' => 'O código do projeto deve ser do tipo string.',
            'codigo.max' => 'O código do projeto deve ter, no máximo, 255 caracteres.',
            'verba.required' => 'É necessário fornecer a verba concedida ao projeto.',
            'verba.numeric' => 'O valor da verba deve ser numérico.',
        ]);

        $projeto->programa_id = $request->programa_id;
        $projeto->codigo = $request->input('codigo');
        $projeto->verba = $request->input('verba');
        $projeto->save();

        return redirect()
            ->back()
            ->with('success', 'Projeto CAPES/AUXPE cadastrado para o programa.');
    }

    public function destroy($id) {
        $projeto = ProjetoCapes::findOrFail($id);
        $projeto->delete();

        return redirect()
            ->back()
            ->with('success', 'Projeto CAPES/AUXPE removido.');
    }
}
