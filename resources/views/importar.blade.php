@extends('layout')
@section('title', $title)
@section('content')
    <div class="row section-margin-bottom">
        @include('common.import_menu')
    </div>
    <div class="row center section-margins side-margins">
        <a class="btn-small black waves-effect waves-black" onclick="history.back()">Voltar</a>
    </div>
@endsection