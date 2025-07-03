@extends('admin.layouts.app')

@section('content')
    <div class="row justify-content">
        <div class="col-sm-12">

            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <a href="{{ route('usuarios.export') }}" class="btn btn-success"><i class="fas fa-download"></i> Exportar</a>
                    </p>
                </div>
            </div>

            @if($users)
            <table class="table table-striped" id="table_list">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome Completo</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Cidade</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="col-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td><a href="mail:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td>{{ ($user->city) ? $user->city : '-' }}</td>
                        @if($user->status == 'a')
                        <td align="center"><i class="fas fa-check-square fa-lg text-success" title="Ativo"></i></td>
                        @else
                        <td align="center"><i class="fas fa-window-close fa-lg text-danger" title="Inativo"></i></td>
                        @endif
                        <td>
                            <a href="{{ route('usuarios.show', ['id' => $user->id]) }}" class="btn btn-sm btn-edit btn-primary" title="View"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-info">Nenhum usuário cadastrado</div>
            @endif
        </div>
    </div>
@endsection
