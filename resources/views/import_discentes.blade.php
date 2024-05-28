@extends('layout')
@section('content')

    <div class="container">

        <div class="section-margins">
            <h5>Importar solicitações de discentes</h5>
            <p>
                Gere o CSV diretamente do Google Sheets e importe por aqui. Cuidado, porque essa merda do
                Google Shits transforma os emails em links e isso estraga o CSV. Faça a exportação do CSV
                só depois de remover esses links e não me pergunte como fazer isso.
            </p>
        </div>
        
        @if ($message = Session::get('success'))
            <div class="row">
                <div class="col s12">
                    <div class="row center">
                    <p>{{ $message }}</p>
                        <a href="{{ route('site.importacoes') }}" class="btn blue darken-2 waves-effect waves-light">Conferir importação</a>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('import_discentes_form') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col s12 file-field input-field section-margins">
                <div class="waves-effect waves-light btn red black">
                    <span>Selecionar planilha</span>
                    <input id="file" type="file" name="file">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                    <label for="file">Selecione o CSV (discentes) "Solicitação de Auxílio PROAP (respostas)"</label>
                </div>
            </div>
            <div class="center">
                <button type="submit" class="btn black waves-effect waves-light">Importar</button>
            </div>
        </form>

    </div>
    
@endsection