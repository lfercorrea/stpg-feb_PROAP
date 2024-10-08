@extends('layout')
@section('title', $title)
@section('content')

    <div class="container">

        <div class="section-margins">
            <h5>Importar solicitações de {{ $solicitante }}</h5>
            @include('common.aviso_importacao')
        </div>
        
        @if ($message = Session::get('success'))
            <div class="row">
                <div class="col s12">
                    <div class="row center">
                    <p><b>{{ $message }}</b></p>
                        <a href="{{ route('site.importacoes') }}" class="btn blue darken-2 waves-effect waves-light">Conferir importação</a>
                        <a href="{{ route('site.solicitacoes.index') }}" class="btn black darken-2 waves-effect waves-light">Ver solicitações</a>
                        <a href="{{ route('import_menu') }}" class="btn green darken-2 waves-effect waves-light">Importar outro</a>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('import_' . $solicitante . '_form') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col s12 file-field input-field section-margins">
                <div class="waves-effect waves-light btn red black">
                    <span>Selecionar planilha</span>
                    <input id="file" type="file" name="file">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                    <label for="file">Selecione o CSV da planilha "<b>Solicitação de Auxílio PROAP (respostas)</b>" (nesse caso, da aba  <b>{{ $solicitante }}</b>)</label>
                </div>
            </div>
            <div class="center">
                <button type="submit" class="btn black waves-effect waves-light">Importar</button>
            </div>
        </form>

    </div>
    
@endsection