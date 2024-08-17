@extends('layout')
@section('title', $title)
@section('content')
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h6>Confirmar</h6>
            <p>Vai mesmo excluir este projeto?</p>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect btn-flat">Cancelar</a>
            <a href="#" id="confirmDelete" class="waves-effect waves-light btn red darken-1">Confirmar</a>
        </div>
    </div>
    <div class="section-margins">
        <h5>{{ $title }}</h5>
    </div>
    <div class="container">
        @if ($programa->projetos_capes->count() === 0)
            <div class="alert">
                <p>
                    Não existe nenhum projeto CAPES/AUXPE associado a este programa. Antes de definir
                    parâmetros como o saldo inicial e nome dos coordenadores, adicione um projeto CAPES/AUXPE,
                    então o formulário para preenchimento ficará visível aqui. O código do projeto é fornecido pelo
                    coordenador do programa.
                </p>
            </div>
        @else
            <form action="{{ route('site.programa.store', ['id' => $programa->id]) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input name="coordenador" id="coordenador" value="{{ $programa->coordenador }}" type="text" class="validate">
                        <label for="coordenador">Nome completo do coordenador</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input name="vice_coordenador" id="vice_coordenador" value="{{ $programa->vice_coordenador }}" type="text" class="validate">
                        <label for="vice_coordenador">Nome completo do vice-coordenador</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select name="projeto_capes">
                            <option value="{{ old('projeto_capes', $programa->projeto_capes) }}" selected>{{ old('projeto_capes', $programa->projeto_capes) }}</option>
                            @foreach ($programa->projetos_capes as $projeto)
                                <option value="{{ $projeto->codigo }}">{{ $projeto->codigo }}</option>
                            @endforeach
                        </select>
                        <label>Projeto CAPES/AUXPE vigente</label>
                    </div>
                    <div class="input-field col s12 m3">
                        <input name="saldo_inicial" id="saldo_inicial" value="{{ $programa->saldo_inicial }}" type="text" class="validate">
                        <label for="saldo_inicial">Saldo inicial (R$)</label>
                    </div>
                </div>
                <div class="container center print-hidden">
                    <a class="btn-flat waves-effect waves-black" onclick="history.back()">Voltar</a>
                    <button class="btn-small black darken-2 waves-effect waves-light" type="submit" name="action">Salvar</button>
                </div>
            </form>
            <div class="section-margin-top">
                <h6>Projetos CAPES/AUXPE do programa</h6>
            </div>
            <table class="bordered striped responsive-table highlight">
                <thead>
                    <tr>
                        <th colspan="2">Código do projeto</th>
                        <th class="center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programa->projetos_capes as $projeto_capes)
                        <tr>
                            <td class="center small">
                                <a href="{{ route('site.projeto_capes.destroy', ['id' => $projeto_capes->id]) }}" class="confirm-link">
                                    <i class="material-icons tiny red-text">delete</i>
                                </a>
                            </td>
                            <td>
                                {{ $projeto_capes->codigo }}
                            </td>
                            <td class="center">
                                @if ($projeto_capes->codigo === $programa->projeto_capes)
                                    <b>Vigente</b>
                                @else
                                    Inativo
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="row">
            <div class="section-margin-top">
                <h6>Adicionar projeto CAPES/AUXPE ao programa</h6>
            </div>
            {{-- Aciona o controlador ProjetoCapes --}}
            <form action="{{ route('site.projeto_capes.store', ['programa_id' => $programa->id]) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="input-field col s12 m8">
                        <input name="codigo" id="codigo" type="text" class="validate">
                        <label for="codigo">Código do projeto (fornecido pelo coordenador do programa)</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <button class="btn-small green darken-2 waves-effect waves-light" type="submit" name="action">Adicionar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection