@extends('admin.layouts.app')

@section('content')
    <div class="row justify-content">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <a href="{{ route('comidas.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Nova</a>
                    </p>
                </div>
                <div class="col-sm-6 text-right">
                    <strong>Total:</strong> {{ count($foods) }} pratos
                </div>
            </div>

            @if($foods)
            <table class="table table-striped" id="table_list">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Comida</th>
                        <th scope="col">Estabelecimento</th>
                        <th scope="col" class="col-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foods as $food)
                    <tr>
                        <td>{{ $food->id }}</td>
                        <td>{{ $food->food_name }}</td>
                        <td>{{ $food->establishment->name }}</td>
                        <td>
                            <a href="{{ route('comidas.edit', ['id' => $food->id]) }}" class="btn btn-sm btn-edit btn-success" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                            <a href="{{ route('comidas.destroy', ['id' => $food->id]) }}" class="btn btn-sm btn-destroy btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-info">Nenhum curador cadastrado</div>
            @endif
        </div>
    </div>
@endsection
