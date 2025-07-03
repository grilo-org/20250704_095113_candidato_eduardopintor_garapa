@extends('admin.layouts.app')

@section('content')
<div class="jumbotron">

    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    <h3>Selecione uma opção abaixo:</h3>
    <div class="panel panel-default">
        <div class="panel panel-default"><a href="{{ route('curadores') }}" class="nav-link">Curadores</a></div>
        <div class="panel panel-default"><a href="{{ route('experiencias') }}" class="nav-link">Experiências</a></div>
        @if(Auth::user()->hasRole('admin'))
        <div class="panel panel-default"><a href="{{ route('comidas') }}" class="nav-link">Comidas</a></div>
        <div class="panel panel-default"><a href="{{ route('usuarios') }}" class="nav-link">Usuários</a></div>
        @endif
    </div>
</div>

@endsection
