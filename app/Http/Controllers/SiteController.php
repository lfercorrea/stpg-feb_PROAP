<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportacoesDiscentes;
use App\Models\ImportacoesDocentes;

class SiteController extends Controller
{
    public function index() {
        return view('index', [
            //
        ]);
    }

    public function importacoes() {
        $solicitacoes = ImportacoesDiscentes::orderBy('id', 'asc')->get();
        $vazia = ImportacoesDiscentes::count() === 0;

        return view('importacoes', [
            'solicitacoes' => $solicitacoes,
            'vazia' => $vazia,
        ]);
    }

    public function drop_importacoes_discentes() {
        ImportacoesDiscentes::truncate();

        return redirect()->route('site.index')->with('success', 'Tabela de importações foi para Caixa prego.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
