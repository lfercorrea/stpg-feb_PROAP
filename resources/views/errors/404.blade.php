@extends('layout')

@section('title', 'Página não encontrada')

@section('content')
    <div class="center" style="background-color: black;">
        <a href="{{ route('site.index') }}" class="waves-effect waves-light">
            <img src="{{ asset('storage/static/images/404.png') }}">
        </a>
    </div>
@endsection
