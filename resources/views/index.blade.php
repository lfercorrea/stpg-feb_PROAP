@extends('layout')
@section('title', $title)
@section('content')
    <div class="row container center section-margins">
        <div class="col s12">
            <h5>{{ $title }}</h5>
        </div>
    </div>
    <div class="row container">
        <a href="{{ route('site.solicitacoes.index') }}" class="black-text">
            <div class="col s4 center waves-effect">
                <i class="material-icons medium black-text">request_page</i>
                <div class="container">
                    Solicitações
                </div>
            </div>
        </a>
        <a href="{{ route('site.solicitantes.index') }}" class="black-text">
            <div class="col s4 center waves-effect">
                <i class="material-icons medium black-text">people</i>
                <div class="container">
                    Solicitantes
                </div>
            </div>
        </a>
        <a href="{{ route('site.programas.index') }}" class="black-text">
            <div class="col s4 center waves-effect">
                <i class="material-icons medium black-text">history_edu</i>
                <div class="container">
                    Programas
                </div>
            </div>
        </a>
    </div>
    @include('common.import_menu')
@endsection