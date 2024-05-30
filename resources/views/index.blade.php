@extends('layout')
@section('content')
    <div class="row container">
        <div class="col s12">
            <h5>Gestão do PROAP</h5>
            Da planilha compartilhada pelo Gustavo "Solicitação de Auxílio PROAP (respostas)",
            gere o CSV correspondente a cada aba "Docente" ou "Discente" e importe-a na função apropriada abaixo.
        </div>
    </div>
    <div class="row container">
        <div class="col s12">
            <h5>Solicitações</h5>
        </div>
        <a href="{{ route('site.solicitacoes') }}" class="black-text">
            <div class="col s6 m3 center waves-effect">
                <i class="material-icons medium black-text">request_page</i>
                <div class="container">
                    Solicitações
                </div>
            </div>
        </a>
    </div>
    @include('common.menu_importar')
@endsection