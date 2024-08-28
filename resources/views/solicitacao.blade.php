@extends('layout')
@section('title', $title)
@section('content')
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h6>Confirmar</h6>
            <p>Vai mesmo excluir esta nota?</p>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect btn-flat">Cancelar</a>
            <a href="#" id="confirmDelete" class="waves-effect waves-light btn red darken-1">Confirmar</a>
        </div>
    </div>
    <div class="print-only section-margins">
        <h6><b>Detalhes da solicitação</b></h6>
    </div>
    <div class="row print-hidden">
        <div class="col s10">
            <h5>Detalhes da solicitação</h5>
        </div>
        <div class="col s2">
            <button id="print-button" class="btn-flat waves-effect waves-black" type="button">
                Imprimir
                <i class="material-icons right">print</i>
            </button>
        </div>
    </div>
    <div class="side-margins">
        <form class="col s12" action="{{ route('site.solicitacao.update', ['id' => $solicitacao->id]) }}" method="POST">
            @csrf
            <div class="row">
                <table class="compact-table striped responsive-table">
                    <tr>
                        <th>Solicitante</th>
                        <th>Telefone</th>
                        <th>CPF</th>
                        <th class="print-hidden">RG</th>
                        <th class="print-hidden">Órgão emissor</th>
                        <th class="print-hidden">Data de emissão</th>
                        <th>Banco</th>
                        <th>Agência</th>
                        <th>Conta</th>
                    </tr>
                    <tr>
                        <td>
                            <a href="{{ route('site.solicitante.show', ['id' => $solicitacao->solicitante->id]) }}" class="hover-underline">{{ $solicitacao->solicitante->nome }}</a>
                            (<a href="mailto:{{ $solicitacao->solicitante->email }}" class="hover-underline">{{ $solicitacao->solicitante->email }}</a>)
                            <a href="{{ route('site.solicitante.edit', ['id' => $solicitacao->solicitante->id]) }}" class="btn-flat waves-effect print-hidden"><i class="material-icons tiny">edit</i></a>
                        </td>
                        <td>{{ $solicitacao->solicitante->telefone }}</td>
                        <td>{{ $solicitacao->solicitante->cpf }}</td>
                        <td class="print-hidden">{{ $solicitacao->solicitante->rg }}</td>
                        <td class="print-hidden">{{ $solicitacao->solicitante->rg_orgao_expedidor }}</td>
                        <td class="print-hidden">{{ $solicitacao->solicitante->rg_data_expedicao }}</td>
                        <td>{{ $solicitacao->solicitante->banco }}</td>
                        <td>{{ $solicitacao->solicitante->banco_agencia }}</td>
                        <td>{{ $solicitacao->solicitante->banco_conta }}</td>
                    </tr>
                </table>
                <table class="compact-table striped responsive-table">
                    <tr>
                        <th>Solicitacão</th>
                        @if ($periodo)
                            <th>Período do evento</th>
                        @endif
                        <th>Solicitado em</th>
                        <th>Programa</th>
                        @if ($solicitacao->nome_do_orientador)
                            <th>Orientador</th>
                        @endif
                        @if ($site_evento)
                            <th class="center-align print-hidden">Site</th>
                        @endif
                        <th class="center-align print-hidden">Parecer</th>
                        <th class="center-align print-hidden">Orçamento</th>
                        <th class="center-align print-hidden">Artigo</th>
                        <th class="center-align print-hidden">Aceite</th>
                    </tr>
                    <tr>
                        <td class="justify"><i>{{ optional($solicitacao->servico_tipo)->nome ?? $solicitacao->tipo->nome }}</i>: {{ $resumo_solicitacao }}</td>
                        @if ($periodo)
                            <td>
                                <div class="print-only">
                                    {{ $periodo }}
                                </div>
                                <div class="print-hidden">
                                    <textarea name="periodo" class="materialize-textarea" cols="30" required>{{ $periodo }}</textarea>
                                </div>
                            </td>
                        @endif
                        <td>{{ $solicitacao->carimbo_data_hora }}</td>
                        <td>{{ $solicitacao->programa->nome }}</td>
                        @if ($solicitacao->nome_do_orientador)
                            <td>{{ $solicitacao->nome_do_orientador }}</td>
                        @endif
                        @if ($site_evento)
                            <td class="center-align print-hidden">
                                <a href="{{ $site_evento }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $site_evento }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            </td>
                        @endif
                        <td class="center-align print-hidden">
                            @if ($link_parecer)
                                <a href="{{ $link_parecer }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_parecer }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            @endif
                        </td>
                        <td class="center-align print-hidden">
                            @if ($link_orcamento)
                                <a href="{{ $link_orcamento }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_orcamento }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            @endif
                        </td>
                        <td class="center-align print-hidden">
                            @if ($link_artigo_copia)
                                <a href="{{ $link_artigo_copia }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_artigo_copia }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            @endif
                        </td>
                        <td class="center-align print-hidden">
                            @if ($link_artigo_aceite)
                                <a href="{{ $link_artigo_aceite }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_artigo_aceite }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            @endif
                        </td>
                    </tr>
                </table>
                <table class="compact-table striped responsive-table">
                    <tr>
                        <th>Justificativa</th>
                    </tr>
                    <tr>
                        <td class="justify"><i>{{ $justificativa }}</i></td>
                    </tr>
                </table>
                <h6>Valor solicitado</h6>
                <table class="compact-table striped responsive-table">
                    <tr>
                        @if ($valor)
                            <th>Geral</th>
                        @endif
                        @if ($valor_diarias)
                            <th>Diárias</th>
                        @endif
                        @if ($valor_inscricao)
                            <th>Taxa de inscrição</th>
                        @endif
                        @if ($valor_passagens)
                            <th>Transporte/passagens</th>
                        @endif
                    </tr>
                    <tr>
                        @if ($valor)
                            <td>{{ $valor }}</td>
                        @endif
                        @if ($valor_diarias)
                            <td>{{ $valor_diarias }}</td>
                        @endif
                        @if ($valor_inscricao)
                            <td>{{ $valor_inscricao }}</td>
                        @endif
                        @if ($valor_passagens)
                            <td>{{ $valor_passagens }}</td>
                        @endif
                    </tr>
                </table>
            </div>
            <div class="row print-hidden">
                <div class="col s12 m3">
                    <label>Status</label>
                    <select name="status_id" class="browser-default" required>
                        <option value="{{ $solicitacao->status->id }}" selected>{{ $solicitacao->status->nome }}</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-field col s12 m8">
                    <input id="observacao" name="observacao" type="text" class="validate" value="{{ old('observacao', $solicitacao->observacao) }}">
                    <label for="observacao">Observação</label>
                </div>
                <div class="input-field col s12 m1">
                    <button class="btn-small green darken-2 waves-effect waves-light" type="submit" name="action">Salvar</button>
                </div>
            </div>
        </form>
        <div class="print-only">
            <div class="row">
                <div class="col s6 small-text">
                    <b>Status:</b> {{ $solicitacao->status->nome }}
                </div>
            </div>
            @if ($solicitacao->observacao)
                <div class="row">
                    <div class="col s12 small-text justify">
                        <b>Observação:</b> {{ $solicitacao->observacao }}
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            @if ($solicitacao->notas->isNotEmpty())
                <div class="section-margins">
                    <h5>Notas/recibos</h5>
                </div>
                <table class="compact-table striped responsive-table">
                    <tr>
                        <th class="center-align print-hidden"><i class="material-icons tiny">delete</i></th>
                        <th>Nº</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Fonte pagadora</th>
                        <th>Projeto CAPES</th>
                        <th>Tipo de despesa</th>
                        <th>Valor</th>
                    </tr>
                    @foreach ($solicitacao->notas as $nota)
                        <tr>
                            <td class="center-align print-hidden">
                                <a href="{{ route('site.nota.destroy', ['solicitacao_id' => $solicitacao->id, 'nota_id' => $nota->id]) }}" class="confirm-link">
                                    <i class="material-icons tiny red-text">delete</i>
                                </a>
                            </td>
                            <td>
                                <b>{{ $nota->numero }}</b>
                                (<a href="{{ route('site.solicitacao.recibo', ['id' => $solicitacao->id, 'nid' => $nota->id]) }}" class="hover-underline" target="_blank">gerar recibo</a>)
                            </td>
                            <td>{{ \Carbon\Carbon::parse($nota->data)->format('d/m/Y') }}</td>
                            <td>{{ $nota->descricao }}</td>
                            <td>{{ $nota->fonte_pagadora->nome }}</td>
                            <td>{{ $nota->projeto_capes->codigo }}</td>
                            <td>{{ $nota->valor_tipo->nome }}</td>
                            <td>{{ $brl->formatCurrency($nota->valor, 'BRL') }}</td>
                        </tr>
                    @endforeach
                </table>
                <table class="compact-table striped responsive-table">
                    <thead>
                        <tr>
                            <th colspan="2" class="center-align">Totais</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="right-align"><b>Notas:</b></td>
                            <td class="right-align"><span class="blue-text text-darken-2"><b>{{ $count_notas }}</b></span></td>
                        </tr>
                        <tr>
                            <td class="right-align"><b>Valor:</b></td>
                            <td class="right-align"><span class="blue-text text-darken-2"><b>{{ $brl->formatCurrency($valor_total, 'BRL') }}</b></span></td>
                        </tr>
                    </tbody>
                </table>
            @endif
            @if ($solicitacao->programa->coordenador)
                <div class="section-margins print-hidden">
                    <h6>Lançar nota/recibo</h6>
                </div>
                <div class="row print-hidden">
                    <form class="col s12" action="{{ route('site.nota.store', ['id' => $solicitacao->id]) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="input-field col s7 m4">
                                <input name="numero" id="numero" type="text" maxlength="255" class="validate" required>
                                <label for="numero">Número</label>
                            </div>
                            <div class="input-field col s5 m2">
                                <input name="data" id="data" type="date" class="validate" min="1900-01-01" max="2099-12-31" required>
                                <label for="data">Data</label>
                            </div>
                            <div class="col s12 m2">
                                <label>Fonte pagadora</label>
                                <select name="fonte_pagadora_id" class="browser-default" required>
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach ($fontes_pagadoras as $fonte_pagadora)
                                        <option value="{{ $fonte_pagadora->id }}">{{ $fonte_pagadora->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s12 m4">
                                <label>Projeto CAPES</label>
                                <select name="projeto_capes_id" class="browser-default" required>
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach ($projetos_capes as $projeto_capes)
                                    <option value="{{ $projeto_capes->id }}">{{ $projeto_capes->codigo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input name="descricao" id="descricao" type="text" class="validate">
                                <label for="descricao">Descrição/observação</label>
                            </div>
                            <div class="col s12 m3">
                                <label>Tipo de despesa</label>
                                <select name="valor_tipo_id" class="browser-default" required>
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach ($valor_tipos as $valor_tipo)
                                        <option value="{{ $valor_tipo->id }}">{{ $valor_tipo->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-field col s8 m2">
                                <input name="valor" id="valor" type="number" placeholder="1234567,89" min="0" step="0.01" class="validate" required>
                                <label for="valor">Valor (R$)</label>
                            </div>
                            <div class="input-field col s4 m1">
                                <button class="btn-small green darken-2 waves-effect waves-light" type="submit" name="action">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="alert print-hidden">
                    <p>
                        O programa está sem coordenador definido, o que impossibilita o lançamento de notas.
                    </p>
                    <a href="{{ route('site.programa.edit', ['id' => $solicitacao->programa->id]) }}" class="btn-small black darken-2">Clique para lançar</a>
                </div>
            @endif
        </div>
    </div>
    <div class="container center print-hidden">
        <a class="btn-flat waves-effect waves-black" onclick="history.back()">Voltar</a>
        <a class="btn black waves-effect waves-light" href="{{ route('site.solicitante.show', ['id' => $solicitacao->solicitante->id]) }}">Resumo do solicitante</a>
    </div>
@endsection