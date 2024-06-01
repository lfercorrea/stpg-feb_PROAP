<div class="row container">
    <div class="col s12">
        <h5>Importações de CSV</h5>
    </div>
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
                Dados da última importação
            </div>
        </div>
    </a>
</div>