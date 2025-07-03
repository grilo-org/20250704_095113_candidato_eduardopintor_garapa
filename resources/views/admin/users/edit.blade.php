@extends('admin.layouts.app')

@section('content')
    <h4>Dados Pessoais</h4>
    <hr>

    <form method="post" action="{{ route('curadores.update', ['id' => $id]) }}" id="editor">
        @csrf
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{ $curator['name'] }}" required="required">
            </div>
        </div>

        <div class="form-group row">
            <label for="office" class="col-sm-2 col-form-label">Profissão</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="office" name="office" value="{{ $curator['office'] }}" required="required">
            </div>
        </div>

        <div class="form-group row">
            <label for="facebook" class="col-sm-2 col-form-label">Facebook</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $curator['facebook'] }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="instagram" class="col-sm-2 col-form-label">Instagram</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $curator['instagram'] }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="pic_url" class="col-sm-2 col-form-label">Avatar</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pic_url" name="pic_url" value="{{ $curator['pic_url'] }}" required="required">
            </div>
        </div>

        <div class="form-group row">
            <label for="city" class="col-sm-2 col-form-label">Cidade</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="city" name="city" value="{{ $curator['city'] }}" required="required">
            </div>
        </div>

        <div class="form-group row">
            <label for="state" class="col-sm-2 col-form-label">Estado</label>
            <div class="col-sm-10">
                <select class="form-control" id="state" name="state">
                    @foreach ($states as $key  => $state)
                    <option value="{{ $key }}"{{ ($key == $curator['state']) ? ' selected="selected" ': ''}}>{{ $state }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="bio" class="col-sm-2 col-form-label">Biografia</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="bio" name="bio" rows="5" required="required">{{ $curator['bio'] }}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="description" class="col-sm-2 col-form-label">Descrição</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" name="description" rows="5">{{ $curator['description'] }}</textarea>
            </div>
        </div>

        <h4>Comidas Favoritas</h4>
        <hr>

        <div class="row">
            <div class="col-sm-10 offset-sm-2">
                <div class="row">
                    @foreach($foods as $id => $food)
                    <div class="col-sm-6">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="foodCurator_{{ $id }}" name="foodCurator[{{ $id }}]"{{ (in_array($id, $foodsCurator)) ? ' checked = "checked"' : '' }}>
                            <label class="custom-control-label" for="foodCurator_{{ $id }}">{{ $food['name'] }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </div>

    </form>
@endsection
