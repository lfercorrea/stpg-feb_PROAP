<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;

class NotaController extends Controller
{
    public function store(Request $request, $id) {
        $nota = new Nota();

        $request->validate([
            'numero' => 'required|string|max:255',
            'data' => 'required|string',
            'descricao' => 'nullable|string',
            // 'valor' => 'regex:/^\d+(\,\d{1,2})?$/',
            'valor' => 'required|numeric',
            'fonte_pagadora_id' => 'required|integer',
        ], [
            'numero.required' => 'Deve ser fornecido um número/código para a nota/recibo',
            'numero.string' => 'O número ou código da nota/rebico deve ser uma string',
            'numero.max' => 'O número/código da nota não pode passar de 255 caracteres',
            'data.required' => 'A data da nota precisa ser informada',
            'data.string' => 'A data da nota precisa ser uma string',
            'descricao.string' => 'A descrição precisa ser uma string',
            'valor.required' => 'O valor precisa ser informado',
            'valor.numeric' => 'O valor precisa ser numérico',
            'fonte_pagadora_id.required' => 'A fonte pagadora deve ser informada pelo campo',
            'fonte_pagadora_id.integer' => 'A fonte pagadora deve ser do tipo INT',
        ]);

        $nota->numero = $request->numero;
        $nota->data = $request->data;
        $nota->descricao = $request->descricao;
        $nota->valor = $request->valor;
        $nota->fonte_pagadora_id = $request->fonte_pagadora_id;
        $nota->solicitacao_id = $id;

        $nota->save();
        
        return redirect()
            ->route('site.solicitacao', ['id' => $id])
            ->with('success', 'Nota lançada.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $solicitacao_id, $nota_id) {
        $nota = Nota::FindOrFail($nota_id);
        $nota->delete();

        return redirect()->route('site.solicitacao', ['id' => $solicitacao_id])->with('success', 'Nota/recibo já era.');
    }
}
