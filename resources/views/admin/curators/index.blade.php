@extends('admin.layouts.app')

@section('content')
    <div class="row justify-content">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <a href="{{ route('curadores.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Novo</a>
                        <a href="{{ route('curadores.export') }}" class="btn btn-success"><i class="fas fa-download"></i> Exportar</a>
                    </p>
                </div>
                <div class="col-sm-6 text-right">
                    <strong>Total:</strong> {{ count($curators) }} curadores
                </div>
            </div>

            @if($curators)
            <table class="table table-striped" id="table_list">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">avatar</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Profissão</th>
                        <th scope="col">Facebook</th>
                        <th scope="col">Instagram</th>
                        <th scope="col">Cidade/UF</th>
                        <th scope="col" class="col-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($curators as $curator)
                    <tr>
                        <td>{{ $curator->id }}</td>
                        <td>
                            @if(strpos($curator->picture_url, 'firebasestorage') || strpos($curator->picture_url, 'placeholder'))
                                <img src="{{$curator->picture_url}}" class="img-thumbnail" width="75">
                            @else
                                <img src="{{url('/storage')}}/{{$imagePath}}/{{$curator->picture_url}}" class="img-thumbnail" width="75">
                            @endif
                        </td>
                        <td>{{ $curator->name }}</td>
                        <td>{{ $curator->email }}</td>
                        <td>{{ $curator->occupation }}</td>
                        <td><a href="https://facebook.com/{{ $curator->facebook_url }}" target="_blank">{{ $curator->facebook_url }}</a></td>
                        <td><a href="https://instagram.com/{{ str_replace('@', '', $curator->instagram_url) }}" target="_blank">{{ $curator->instagram_url }}</a></td>
                        <td>{{ $curator->city }}/{{ $curator->state }}</td>
                        <td>
                            <a href="{{ route('curadores.edit', ['id' => $curator->id]) }}" class="btn btn-sm btn-edit btn-success" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                            <a href="{{ route('curadores.destroy', ['id' => $curator->id]) }}" class="btn btn-sm btn-destroy btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></a>
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
