<div class="row container">
    <div class="col s12 section-margin-bottom center">
        <h6 class="blue-text text-darken-2">{{ $title }}</h6>
    </div>
    <a href="{{ route('import_discentes_form') }}" class="black-text">
        <div class="col s6 m4 center waves-effect">
            <i class="material-icons medium black-text">local_library</i>
            <div class="container">
                Importar solicitações de discentes
            </div>
        </div>
    </a>
    <a href="{{ route('import_docentes_form') }}" class="black-text">
        <div class="col s6 m4 center waves-effect">
            <i class="material-icons medium black-text">school</i>
            <div class="container">
                Importar solicitações de docentes
            </div>
        </div>
    </a>
    <a href="{{ route('site.importacoes') }}" class="black-text">
        <div class="col s6 m4 center waves-effect">
            <i class="material-icons medium black-text">repartition</i>
            <div class="container">
                Dados da última importação
            </div>
        </div>
    </a>
</div>