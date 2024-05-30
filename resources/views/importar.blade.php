@extends('layout')
@section('content')
    <div class="row section-margin-bottom">
        @include('common.menu_importar')
    </div>
    <div class="row center section-margins side-margins">
        <a href="{{ route('site.solicitacoes') }}" class="btn black waves-effect waves-light">Voltar para solicitações</a>
    </div>
@endsection