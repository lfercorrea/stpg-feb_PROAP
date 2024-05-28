@extends('layout')
@section('content')

    <div class='center'>
        <h5>Solicitações</h5>
        <hr>
    </div>

    <div class="row">
        <div class="col s12 m2 input-field">
            <a href="{{ route('import_discentes_form') }}" class="btn green waves-effect waves-light">Importar</a>
        </div>
        <form action="#" method="GET">
            <div class="col s12 m3 input-field">
                <input type="text" name="search" placeholder="Buscar"> 
            </div>
            <div class="col s4 m3 input-field">
                <select name="programa_id[]" id="programa_id" multiple="" tabindex="-1" style="display: none;">
                    <option value="" selected disabled>Programas</option>
                        
                        @foreach ($programas as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                        
                    </optgroup>
                </select>
            </div>
            <div class="col s8 m2 input-field">
                <select class="browser-default" name="tipo_solicitacao_id"><option value="">Solicitação</option>
                    
                    @foreach ($tipos_solicitacao as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                    
                </select>
            </div>
            <div class="col s12 m2 input-field">
                <button class="btn waves-effect waves-light black" type="submit">Buscar</button> 
            </div>
        </form>
    </div>

    @if ($count_solicitacoes > 0)
        <div class="row">
            @php
                $count_message = [];

                if(!empty($search_term)) {
                    $count_message[] = "Termo: <b><i>\"$search_term\"</i></b>";
                }

                if (!empty($search_tipo_solicitacao_id)) {
                    $count_message[] = "Solicitacão: <b><i>\"$tipos_solicitacao[$search_tipo_solicitacao_id]\"</i></b>";
                }

                if(!empty($search_programa_id)) {
                    $arr_programas_selecionados = [];
                    
                    foreach($search_programa_id as $selected_id) {
                        $arr_programas_selecionados[] = $programas[$selected_id];
                    }

                    $programas_selecionados = implode(', ', $arr_programas_selecionados);
                    $count_message[] = "Programas: <b><i>\"$programas_selecionados\"</i></b>";
                }

                $plural = ($count_solicitacoes > 1) ? 's' : '';

                echo "<b>$count_solicitacoes</b> resultado$plural<br>" . implode("<br>", $count_message);
            @endphp
        </div>
    @endif
    
    <div class="row">
        @if (request()->input('search') OR request()->input('tipo_solicitacao_id') OR request()->input('programa_id'))
            {{ $solicitacoes->appends([
                'search' => request()->input('search'), 
                'programa_id' => request()->input('programa_id'),
                'tipo_solicitacao_id' => request()->input('tipo_solicitacao_id'),
                ])
                ->links('common/pagination') }}
        @else
            {{ $solicitacoes->links('common/pagination') }}
        @endif
    </div>

    <div id="confirm-delete-modal" class="modal">
        <div class="modal-content">
            <h5>Confirmar</h5>
            <p>
                Tem certeza? Depois disso já era... valeu?
            </p>
        </div>
        <div class="modal-footer">
            <form id="delete-form" action="#" method="POST">
                @csrf
                @method('DELETE')
                <a href="#!" class="modal-close waves-effect waves-black btn-flat">Cancelar</a>
                <button type="submit" class="btn waves-effect waves-light red darken-1">Excluir</button>
            </form>
        </div>
    </div>

    @if (count($solicitacoes) > 0)
        <table class="bordered striped responsive-table highlight">
            <thead>
                <tr>
                    <th class="center-align">Ações</th>
                    <th>Solicitante</th>
                    <th>Tipo de solicitação</th>
                    <th>Programa</th>
                    <th>Data</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($solicitacoes as $solicitacao)
                <tr>
                    <td class="center-align">
                        <a href="{{ route('site.solicitante', ['id' => $solicitacao->solicitante->id]) }}" class="btn-small waves-effect blue darken-1">
                            <i class="material-icons center">edit</i>
                        </a>
                        <button class="btn-small waves-effect red darken-1 modal-trigger" data-target="confirm-delete-modal" data-target-url="/admin/excluir/solicitacao/" data-target-id="{{ $solicitacao->id }}">
                            <i class="material-icons center">delete</i>
                        </button>
                    </td>
                    <td><a href="{{ route('site.solicitante', ['id' => $solicitacao->solicitante->id]) }}" class="black-text hover"><b>{{ Str::upper($solicitacao->solicitante->nome) }}</b></a> ({{ $solicitacao->solicitante->email }})</td>
                    <td>{{ $solicitacao->tipo->nome }}</td>
                    <td>{{ $solicitacao->programa->nome }}</td>
                    <td>{{ $solicitacao->carimbo_data_hora }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="container center">
            <h5>Nenhuma solicitacão encontrada.</h5>
            <br>
            <a href="{{ route('site.index') }}" class="btn waves-effect waves-light black">Inicial</a>
        </div>
    @endif

    <div class="row">
        @if (request()->input('search') OR request()->input('tipo_solicitacao_id') OR request()->input('programa_id'))
            {{ $solicitacoes->appends([
                'search' => request()->input('search'), 
                'programa_id' => request()->input('programa_id'),
                'tipo_solicitacao_id' => request()->input('tipo_solicitacao_id'),
                ])
                ->links('common/pagination') }}
        @else
            {{ $solicitacoes->links('common/pagination') }}
        @endif
    </div>

@endsection
