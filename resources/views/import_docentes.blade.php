@extends('layout')

@section('content')

    <div class="container">

        <div class="section-margins">
            <h5>Importar solicitações de docentes</h5>
            <p>
                Gere o CSV diretamente do Google Sheets e importe por aqui.
            </p>
        </div>


        @if ($message = Session::get('success'))
        <div>
            <strong>{{ $message }}</strong>
        </div>
        @endif

        <form action="{{ route('import_docentes_form') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="file-field input-field col s12">
                <div class="waves-effect btn red black">
                    <span>Selecionar planilha</span>
                    <input id="file" type="file" name="file">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                    <label for="file">Selecione o CSV (docentes) "Solicitação de Auxílio PROAP (respostas)"</label>
                </div>
            </div>
            {{-- <div class="input-field">
                <label for="file">Arquivo CSV</label>
                <input type="file" name="file" required>
            </div> --}}
            <div class="center">
                <button type="submit" class="btn black waves-effect">Importar</button>
            </div>
        </form>

    </div>
    
@endsection