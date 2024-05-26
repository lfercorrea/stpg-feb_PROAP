@extends('layout')
@section('content')

    <div class='center'>
        <h5>Solicitações</h5>
        <hr>
    </div>

    <div class="row">
        <div class="col s12 m3 input-field">
            <a href="#" class="btn green waves-effect waves-light">Nova</a>
            <a href="#" class="btn black waves-effect waves-light">Extrato</a>
        </div>
        <form action="#" method="GET">
            <div class="col s12 m3 input-field">
                <input type="text" name="search" placeholder="Buscar"> 
            </div>
            <div class="col s4 m2 input-field">
                <select name="programa_id[]" id="programa_id" multiple="" tabindex="-1" style="display: none;">
                    <option value="" selected disabled>Programas</option>
                    {{-- <optgroup label="Tamanhos"> --}}
                        
                        @foreach ($programas as $programa)
                            <option value="{{ $programa->id }}">{{ $programa->nome }}</option>
                        @endforeach
                        
                    </optgroup>
                </select>
            </div>
            <div class="col s8 m2 input-field">
                <select class="browser-default" name="id_categoria"><option value="">Todas as categorias</option>
                    
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                    @endforeach
                    
                </select>
            </div>
            <div class="col s12 m2 input-field">
                <button class="btn waves-effect waves-light black" type="submit">Buscar</button> 
            </div>
        </form>
    </div>

    {{-- @if ($count_solicitacoes > 0)
        <div class="container center">
            @php
                $count_message = [];

                if (!empty($search_term)) {
                    $count_message[] = "termo <b><i>\"$search_term\"</i></b>";
                }

                if (!empty($search_id_categoria)) {
                    $count_message[] = "categoria <b><i>\"{$categoria_nome[$search_id_categoria]}\"</i></b>";
                }

                if (!empty($search_id_tamanho)) {
                    $arr_tamanhos_selecionados = [];
                    
                    foreach ( $search_id_tamanho as $selected_id) {
                        $arr_tamanhos_selecionados[] = $tamanho_nome[$selected_id];
                    }

                    $tamanhos_selecionados = implode(', ', $arr_tamanhos_selecionados);
                    $count_message[] = "tamanhos selecionados <b><i>\"{$tamanhos_selecionados}\"</i></b>";
                }

                $plural = (count($count_message) > 0) ? ': ' : '';

                echo "<h5>$count_solicitacoes itens encontrados$plural " . implode(", ", $count_message) . "</h5>";
            @endphp
        </div>
    @endif --}}


    <div class="row">
        @if (request()->input('search') OR request()->input('id_categoria') OR request()->input('id_tamanho'))
            {{ $solicitacoes->appends([
                'search' => request()->input('search'), 
                'id_categoria' => request()->input('id_categoria'),
                'id_tamanho' =>request()->input('id_tamanho'),
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
        <table class="striped responsive-table">
            <thead>
                <tr>
                    <th class="center-align">Ações</th>
                    <th>Solicitante</th>
                    <th>Programa</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($solicitacoes as $solicitacao)
                <tr>
                    <td class="center-align">
                        <a href="/admin/alterar/solicitacao/{{ $solicitacao->id }}" class="btn-small waves-effect blue darken-1">
                            <i class="material-icons center">edit</i>
                        </a>
                        <button class="btn-small waves-effect red darken-1 modal-trigger" data-target="confirm-delete-modal" data-target-url="/admin/excluir/solicitacao/" data-target-id="{{ $solicitacao->id }}">
                            <i class="material-icons center">delete</i>
                        </button>
                    </td>
                    <td>{{ $solicitacao->solicitante->nome }}</td>
                    <td><b>{{ $solicitacao->programa->nome }}</b></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="container center">
            <h5>Nenhum solicitacao encontrado.</h5>
            <br>
            <a href="{{ route('admin.estoque') }}" class="btn waves-effect waves-light black">Voltar</a>
        </div>
    @endif

    <div class="row">
        @if (request()->input('search') OR request()->input('id_categoria') OR request()->input('id_tamanho'))
            {{ $solicitacoes->appends([
                'search' => request()->input('search'), 
                'id_categoria' => request()->input('id_categoria'),
                'id_tamanho' => request()->input('id_tamanho'),
                ])
                ->links('common/pagination') }}
        @else
            {{ $solicitacoes->links('common/pagination') }}
        @endif
    </div>

@endsection
