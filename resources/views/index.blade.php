@extends('layout')
@section('content')
    <div class="row container">
        <div class="col s12">
            <h5>Gestão do PROAP</h5>
        </div>
    </div>
    <div class="row container">
        <div class="col s12 section-margin-bottom center">
            <h6 class="blue-text text-darken-2">Solicitações</h6>
        </div>
        <a href="{{ route('site.solicitacoes') }}" class="black-text">
            <div class="col s12 center waves-effect">
                <i class="material-icons medium black-text">request_page</i>
                <div class="container">
                    Solicitações
                </div>
            </div>
        </a>
    </div>
    @include('common.import_menu')
@endsection