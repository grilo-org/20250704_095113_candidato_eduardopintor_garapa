@extends('admin.layouts.app')

@section('content')
    <form method="post" action="{{ route('comidas.store') }}" id="editor">
        @csrf
        <div class="form-group row">
            <label for="food_name" class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="food_name" name="food_name" required="required">
            </div>
        </div>

        <div class="form-group row">
            <label for="establishment_id" class="col-sm-2 col-form-label">Estabelecimento</label>
            <div class="col-sm-10">
                <select class="form-control select2" id="establishment_id" name="establishment_id" required="required">
                    <option value="">Selecione o estabelecimento</option>
                    @foreach ($establishments as $establishment)
                    <option value="{{ $establishment->id }}">{{ $establishment->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-right">
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </div>

    </form>
@endsection
