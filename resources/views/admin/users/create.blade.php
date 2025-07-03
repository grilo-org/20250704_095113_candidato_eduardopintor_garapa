@extends('admin.layouts.app')

@section('content')
    <h4>Dados Pessoais</h4>
    <hr>

    <form method="post" action="{{ route('usuarios.store') }}" id="editor">
        @csrf

        <div class="form-group row">
            <label for="fullName" class="col-sm-2 col-form-label">Nome Completo</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="fullName" name="fullName" required="required">
            </div>
        </div>

        <div class="form-group row">
            <label for="lastName" class="col-sm-2 col-form-label">Primeiro Nome</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="lastName" name="lastName" required="required">
            </div>
            <label for="LastName" class="col-sm-1 col-form-label">Sobrenome</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="LastName" name="LastName" required="required">
            </div>
        </div>

        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" required="required">
            </div>
        </div>

        <div class="form-group row">
            <label for="photoUrl" class="col-sm-2 col-form-label">Avatar</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="photoUrl" name="photoUrl">
            </div>
        </div>

        <div class="form-group row">
            <label for="birthday" class="col-sm-2 col-form-label">Nascimento</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="birthday" name="birthday">
            </div>
            <label for="cpf" class="col-sm-1 col-form-label">CPF</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="cpf" name="cpf">
            </div>
        </div>

        <div class="form-group row">
            <label for="phoneNumber" class="col-sm-2 col-form-label">Telefone</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber">
            </div>
            <label for="cpf" class="col-sm-1 col-form-label">Gênero</label>
            <div class="col-sm-5">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="gender1" name="gender" class="custom-control-input" value="male">
                    <label class="custom-control-label" for="gender1">Masculino</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="gender2" name="gender" class="custom-control-input" value="female">
                    <label class="custom-control-label" for="gender2">Feminino</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="city" class="col-sm-2 col-form-label">Cidade</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="city" name="city" required="required">
            </div>
            <label for="state" class="col-sm-1 col-form-label">Estado</label>
            <div class="col-sm-3">
                <select class="form-control" id="state" name="state">
                    @foreach ($states as $key  => $state)
                    <option value="{{ $key }}">{{ $state }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h4>Dados de Acesso</h4>
        <hr>

        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Nome de Usuário</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <label for="cpf" class="col-sm-1 col-form-label">Aprovado</label>
            <div class="col-sm-3">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="status1" name="status" class="custom-control-input" value="true">
                    <label class="custom-control-label" for="status1">Sim</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="status2" name="status" class="custom-control-input" value="false">
                    <label class="custom-control-label" for="status2">Não</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="termsOfUseAcceptDate">
                    <label class="custom-control-label" for="termsOfUseAcceptDate">Aceite do Termo de Uso</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="creationDate" class="col-sm-2 col-form-label">Data de Cadastro</label>
            <div class="col-sm-2">
                - <input type="hidden" class="form-control" id="creationDate" name="creationDate" readonly="readonly">
            </div>
            <label for="lastLoginDate" class="col-sm-2 col-form-label">Último Acesso</label>
            <div class="col-sm-2">
                - <input type="hidden" class="form-control" id="lastLoginDate" name="lastLoginDate" readonly="readonly">
            </div>
            <label for="lastLoginDate" class="col-sm-2 col-form-label">Última Atualização</label>
            <div class="col-sm-2">
                - <input type="hidden" class="form-control" id="lastLoginDate" name="lastLoginDate" readonly="readonly">
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-12 text-right">
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </form>
@endsection
