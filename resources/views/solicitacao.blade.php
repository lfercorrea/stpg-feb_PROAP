@extends('layout')
@section('content')
    <div class="side-margins">
        <div class="row">
            <table class="compact-table striped responsive-table">
                <tr>
                    <th>Solicitacão</th>
                    <th>Data</th>
                    <th>Orientador</th>
                    <th class="center-align">Parecer</th>
                    <th class="center-align">Orçamento</th>
                    <th class="center-align">Artigo</th>
                    <th class="center-align">Aceite</th>
                </tr>
                <tr>
                    <td>{{ $solicitacao->tipo->nome }}: {{ $resumo_solicitacao }}</td>
                    <td>{{ $solicitacao->carimbo_data_hora }}</td>
                    <td>{{ $solicitacao->nome_do_orientador }}</td>
                    <td class="center-align">
                        @if ($link_parecer)
                            <a href="{{ $link_parecer }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_parecer }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                        @endif
                    </td>
                    <td class="center-align">
                        @if ($link_orcamento)
                            <a href="{{ $link_orcamento }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_orcamento }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                        @endif
                    </td>
                    <td class="center-align">
                        @if ($link_artigo_copia)
                            <a href="{{ $link_artigo_copia }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_artigo_copia }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                        @endif
                    </td>
                    <td class="center-align">
                        @if ($link_artigo_aceite)
                            <a href="{{ $link_artigo_aceite }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_artigo_aceite }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                        @endif
                    </td>
                </tr>
            </table>
            <table class="compact-table striped responsive-table">
                <tr>
                    <th>Solicitante</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>RG</th>
                    <th>Órgão emissor</th>
                    <th>Data de emissão</th>
                    <th>Banco</th>
                    <th>Agência</th>
                    <th>Conta</th>
                </tr>
                <tr>
                    <td>{{ $solicitacao->solicitante->nome }} (<a href="mailto:{{ $solicitacao->solicitante->email }}" class="hover-underline">{{ $solicitacao->solicitante->email }}</a>)</td>
                    <td>{{ $solicitacao->solicitante->telefone }}</td>
                    <td>{{ $solicitacao->solicitante->cpf }}</td>
                    <td>{{ $solicitacao->solicitante->rg }}</td>
                    <td>{{ $solicitacao->solicitante->rg_orgao_expedidor }}</td>
                    <td>{{ $solicitacao->solicitante->rg_data_expedicao }}</td>
                    <td>{{ $solicitacao->solicitante->banco }}</td>
                    <td>{{ $solicitacao->solicitante->banco_agencia }}</td>
                    <td>{{ $solicitacao->solicitante->banco_conta }}</td>
                </tr>
            </table>
        </div>
        <div class="container">
            @if ($solicitacao->nota->isNotEmpty())
                <div class="section-margins">
                    <h5>Notas/recibos</h5>
                </div>
                <table class="compact-table striped responsive-table">
                    <tr>
                        <th>Nº</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Fonte pagadora</th>
                        <th>Valor</th>
                    </tr>
                    @foreach ($solicitacao->nota as $nota)
                        <tr>
                            <td>{{ $nota->numero }}</td>
                            <td>{{ $nota->data }}</td>
                            <td>{{ $nota->descricao }}</td>
                            <td>{{ $nota->fonte_pagadora->nome }}</td>
                            <td>{{ $nota->valor }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
            <div class="section-margins">
                <h5>Lançar nota/recibo</h5>
            </div>
            <div class="row">
                <form class="col s12" action="{{ route('site.lancar_nota', ['id' => $solicitacao->id]) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="input-field col s7 m6">
                            <input name="numero" id="numero" type="text" maxlength="255" class="validate" required>
                            <label for="numero">Número</label>
                        </div>
                        <div class="input-field col s5 m2">
                            <input name="data" id="data" type="date" class="validate" required>
                            <label for="data">Data</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <select name="fonte_pagadora_id" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach ($fontes_pagadoras as $fonte_pagadora)
                                    <option value="{{ $fonte_pagadora->id }}">{{ $fonte_pagadora->nome }}</option>
                                @endforeach
                            </select>
                            <label>Fonte pagadora</label>
                        </div>
                        <div class="input-field col s12 m9">
                            <input name="descricao" id="descricao" type="text" class="validate">
                            <label for="descricao">Descrição/observação</label>
                        </div>
                        <div class="input-field col s8 m2">
                            <input name="valor" id="valor" type="number" min="0" step="0.01" class="validate" required>
                            <label for="valor">Valor</label>
                        </div>
                        <div class="input-field col s4 m1">
                            <input type="submit" class="input-field btn-small green" value="OK">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="container center">
            <a class="btn black waves-effect waves-black" href="{{ route('site.solicitante', ['id' => $solicitacao->solicitante->id]) }}">Voltar</a>
        </div>
    </div>
@endsection