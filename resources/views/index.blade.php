@extends('layout')
@section('content')
    <div class="row container center">
        <div class="col s12">
            <h5>Gestão do PROAP</h5>
        </div>
    </div>
    <div class="row container">
        <a href="{{ route('site.solicitacoes') }}" class="black-text">
            <div class="col s6 center waves-effect">
                <i class="material-icons medium black-text">request_page</i>
                <div class="container">
                    Solicitações
                </div>
            </div>
        </a>
        <a href="{{ route('site.solicitantes') }}" class="black-text">
            <div class="col s6 center waves-effect">
                <i class="material-icons medium black-text">people</i>
                <div class="container">
                    Solicitantes
                </div>
            </div>
        </a>
    </div>
    @include('common.import_menu')
@endsection