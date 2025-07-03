@extends('admin.layouts.app')

@section('content')
    <h4>Dados Pessoais</h4>
    <hr>
    <div class="form-group row">
        @if(!empty($user->full_name))
        <div class="col-sm-12">
            <strong>Nome Completo:</strong>
            {{ $user->full_name }}
        </div>
        @endif

        @if(!empty($user->first_name))
        <div class="col-sm-12">
            <strong>Primeiro Nome:</strong>
            {{ $user->first_name }}
        </div>
        @endif
        @if(!empty($user->last_name))
        <div class="col-sm-12">
            <strong>Sobrenome:</strong>
            {{ $user->last_name }}
        </div>
        @endif

        @if(!empty($user->email))
        <div class="col-sm-12">
            <strong>Email:</strong>
            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
        </div>
        @endif

        @if(!empty($user->birthday))
        <div class="col-sm-12">
            <strong>Nascimento:</strong>
            {{ $user->birthday }}
        </div>
        @endif
        @if(!empty($user->cpf))
        <div class="col-sm-12">
            <strong>CPF:</strong>
            {{ $user->cpf }}
        </div>
        @endif

        @if(!empty($user->phone_number))
        <div class="col-sm-6">
            <strong>Telefone:</strong>
            {{ $user->phone_number }}
        </div>
        @endif
        @if(!empty($user->gender))
        <div class="col-sm-6">
            <strong>Gênero:</strong>
            {{ $user->gender }}
        </div>
        @endif


        @if(!empty($user->city))
        <div class="col-sm-6">
            <strong>Cidade:</strong>
            {{ $user->city }}
        </div>
        @endif
        @if(!empty($user->state))
        <div class="col-sm-6">
            <strong>Estado:</strong>
            <select class="form-control" id="state" name="state">
                @foreach ($states as $key  => $state)
                <option value="{{ $key }}"{{ ($state == $user->state) ? ' selected="selected" ' : '' }}>{{ $state }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    @if(!empty($user->photo_url))
    <div class="form-group row">
        <div class="col-sm-2">
            <strong>Foto:</strong>
        </div>
        <div class="col-sm-4">
            <img src="{{ $user->photo_url }}" class="img-thumbnail" alt="Foto usuário" width="100">
        </div>
    </div>
    @endif

    <h4>Dados de Acesso</h4>
    <hr>

    @if(!empty($user->username) || !empty($user->status) || !empty($user->terms_of_use_accept_date))
    <div class="form-group row">
        @if(!empty($user->username))
        <div class="col-sm-4">
            <strong>Nome de Usuário:</strong>
            {{ $user->username }}
        </div>
        @endif
        <div class="col-sm-4">
            <strong>Status:</strong>
            {{ (isset($user->status)  && $user->status == 'a') ? 'ativo' : 'inativo' }}
        </div>
        @if(!empty($user->terms_of_use_accept_date))
        <div class="col-sm-4">
            <strong>Aceite do Termo de Uso:</strong>
            {{ $user->terms_of_use_accept_date }}
        </div>
        @endif
    </div>
    @endif

    @if(!empty($user->created_date) || !empty($user->last_login_date) || !empty($user->last_login_date))
    <div class="form-group row">
        <div class="col-sm-4">
            <strong>Data de Cadastro:</strong>
            {{ $user->creation_date }}
        </div>
        <div class="col-sm-4">
            <strong>Último Acesso:</strong>
            {{ $user->last_login_date }}
        </div>
        <div class="col-sm-4">
            <strong>Última Atualização:</strong>
            {{ $user->last_login_date }}
        </div>
    </div>
    @endif

    <hr>

    <div class="row">
        <div class="col-sm-12">
            <a href="{{ route('usuarios') }}" class="btn btn-success">&laquo; Voltar</a>
        </div>
    </div>
@endsection
