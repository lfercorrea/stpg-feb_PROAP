<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\Solicitacao;

class NotaController extends Controller
{
    public function store(Request $request, $id) {
        $nota = new Nota();

        $request->validate([
            'numero' => 'required|string|max:255',
            'data' => 'nullable|date_format:Y-m-d|after:1900-01-01|before:2099-12-31',
            'descricao' => 'nullable|string',
            'valor_tipo_id' => 'required|integer',
            // 'valor' => 'regex:/^\d+(\,\d{1,2})?$/',
            'valor' => 'required|numeric',
            'fonte_pagadora_id' => 'required|integer',
            'projeto_capes_id' => 'required|integer',
        ], [
            'numero.required' => 'Deve ser fornecido um número/código para a nota/recibo',
            'numero.string' => 'O número ou código da nota/rebico deve ser uma string',
            'numero.max' => 'O número/código da nota não pode passar de 255 caracteres',
            'data.date_format' => 'A data está no formato incorreto.',
            'data.after' => 'A data deve ser posterior a 01/01/1900.',
            'data.before' => 'A data deve ser anterior a 31/12/2099.',
            'descricao.string' => 'A descrição precisa ser uma string',
            'valor_tipo_id.required' => 'O tipo de despesa precisa ser informado',
            'valor_tipo_id.integer' => 'O tipo de despesa é do tipo INT',
            'valor.required' => 'O valor precisa ser informado',
            'valor.numeric' => 'O valor precisa ser numérico',
            'fonte_pagadora_id.required' => 'A fonte pagadora deve ser informada pelo campo',
            'fonte_pagadora_id.integer' => 'A fonte pagadora deve ser do tipo INT',
            'projeto_capes_id.required' => 'O código do projeto CAPES deve ser informada pelo campo',
            'projeto_capes_id.integer' => 'O código do projeto CAPES deve ser do tipo INT',
        ]);

        $solicitacao = Solicitacao::find($id);
        $nota->numero = $request->input('numero');
        $nota->data = $request->input('data');
        $nota->descricao = $request->input('descricao');
        $nota->valor = $request->input('valor');
        $nota->valor_tipo_id = $request->input('valor_tipo_id');
        $nota->fonte_pagadora_id = $request->input('fonte_pagadora_id');
        $nota->solicitacao_id = $id;
        $nota->solicitante_id = $solicitacao->solicitante_id;
        $nota->projeto_capes_id = $request->input('projeto_capes_id');
        
        if($nota->save()) {
            $solicitacao->status_id = 5;
            $solicitacao->save();
        }
        
        return redirect()
            ->route('site.solicitacao.show', ['id' => $id])
            ->with('success', 'Nota lançada.');
    }
    
    public function destroy(string $solicitacao_id, $nota_id) {
        $nota = Nota::FindOrFail($nota_id);
        $nota->delete();

        return redirect()->route('site.solicitacao.show', ['id' => $solicitacao_id])->with('success', 'Nota/recibo já era.');
    }
}
