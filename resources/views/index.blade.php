@extends('layout')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h5>Gestão do PROAP</h5>
                <p>
                    Da planilha compartilhada pelo Gustavo "Solicitação de Auxílio PROAP (respostas)",
                    gere o CSV correspondente a cada aba "Docente" ou "Discente" e importe-a na função apropriada abaixo.
                </p>
            </div>
        </div>
        <div class="col s12 section-margins">
            <h5>Solicitações</h5>
        </div>
        <div class="row">
            <a href="{{ route('site.solicitacoes') }}" class="black-text">
                <div class="col s6 m3 center waves-effect">
                    <i class="material-icons medium black-text">request_page</i>
                    <div class="container">
                        Solicitações
                    </div>
                </div>
            </a>
        </div>
        <div class="col s12 section-margins">
            <h5>Importações de CSV</h5>
        </div>
        <div class="row">
            <a href="{{ route('import_discentes_form') }}" class="black-text">
                <div class="col s6 m3 center waves-effect">
                    <i class="material-icons medium black-text">local_library</i>
                    <div class="container">
                        Importar solicitações de discentes
                    </div>
                </div>
            </a>
            <a href="{{ route('import_docentes_form') }}" class="black-text">
                <div class="col s6 m3 center waves-effect">
                    <i class="material-icons medium black-text">school</i>
                    <div class="container">
                        Importar solicitações de docentes
                    </div>
                </div>
            </a>
            <a href="{{ route('site.importacoes') }}" class="black-text">
                <div class="col s6 m3 center waves-effect">
                    <i class="material-icons medium black-text">repartition</i>
                    <div class="container">
                        Dados importados
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection