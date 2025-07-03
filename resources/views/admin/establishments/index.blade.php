@extends('admin.layouts.app')

@section('content')
    <div class="row justify-content">
        <div class="col-sm-12">
            <p>
            </p>
            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <a href="{{ route('experiencias.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Nova</a>
                        @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('experiencias.export') }}" class="btn btn-success"><i class="fas fa-download"></i> Exportar</a>
                        @endif
                    </p>
                </div>
                <div class="col-sm-6 text-right">
                    <strong>Total:</strong> {{ count($establishments) }} experiências
                </div>
            </div>

            @if($establishments)
            <table class="table table-striped" id="table_list">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Cidade/UF</th>
                        <th scope="col">Avaliações</th>
                        <th scope="col">Voltaria</th>
                        <th scope="col">Não Voltaria</th>
                        <th scope="col">Quer ir</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="col-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($establishments as $establishment)
                    <tr>
                        <td>{{ $establishment->id }}</td>
                        <td>{{ $establishment->name }}</td>
                        <td>{{ $establishment->category }}</td>
                        <td>{{ $establishment->city }}/{{ $establishment->state }}</td>
                        <td>{{ $establishment['reviews']['total'] }}</td>
                        <td>{{ $establishment['reviews']['wouldComeBack'] }}</td>
                        <td>{{ $establishment['reviews']['wouldNotComeBack'] }}</td>
                        <td>{{ $establishment['wantToGo'] }}</td>
                        <td align="center">
                            @if(isset($establishment->status)  && $establishment->status == 1)
                            <i class="fas fa-check-square fa-lg text-success" title="Ativo"></i>
                            @else
                            <i class="fas fa-window-close fa-lg text-danger" title="Inativo"></i>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('experiencias.edit', ['id' => $establishment->id]) }}" class="btn btn-sm btn-edit btn-success" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                            <a href="{{ route('experiencias.destroy', ['id' => $establishment->id]) }}" class="btn btn-sm btn-destroy btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-info">Nenhum estabelecimento cadastrado</div>
            @endif
        </div>
    </div>
@endsection
