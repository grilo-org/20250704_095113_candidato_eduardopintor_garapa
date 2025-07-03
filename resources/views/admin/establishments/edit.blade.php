@extends('admin.layouts.app')

@section('content')

    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $message)
            <li>
                {{$message}}
            </li>
            @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('experiencias.update', ['id' => $id]) }}" enctype="multipart/form-data" id="editor">
        <div class="row page-title">
            <div class="col-sm-8">
                <h3>Dados do Estabelecimento</h3>
            </div>    
            <label for="status" class="col-sm-3 col-form-label">Ativo</label>
            <div class="col-sm-1 text-right">
                <div class="switch_box box_1">
                    <input type="checkbox" class="switch_1" name="status" id="status" value="1" @if($establishment['status'] == 1) checked="checked" @endif>
                </div>
            </div>
        </div>

        @csrf

        @if(Auth::user()->hasRole('admin'))
        <div class="form-group row">
            <label for="curatorId" class="col-sm-2 col-form-label">Curadores</label>
            <div class="col-sm-10">
                <select class="form-control select2" multiple="multiple" id="curatorId" name="curatorId[]" required="required">
                    <option value="">Selecione o curador</option>
                    @foreach ($curators as $curator)
                    <option value="{{ $curator->id }}"{{ ( in_array($curator->id, $establishmentCurators) ) ? ' selected ': ''}}>{{ $curator->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{ $establishment->name }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="office" class="col-sm-2 col-form-label">Endereço</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="street" name="street" value="{{ $establishment->street }}" required>
            </div>
            <label for="office" class="col-sm-1 col-form-label">Número</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="number" name="number" value="{{ $establishment->number }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="neighborhood" class="col-sm-2 col-form-label">Bairro</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="neighborhood" name="neighborhood" value="{{ $establishment->neighborhood }}" required>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="state" class="col-sm-2 col-form-label">Estado</label>
            <div class="col-sm-2">
                <select class="form-control" id="state" name="state" required>
                    <option value="">Selecione</option>
                    @foreach ($states as $key  => $state)
                    <option value="{{ $state->abbreviation}}"{{ ($state->abbreviation == $establishment->state) ? ' selected ': ''}}>{{ $state->abbreviation }}</option>
                    @endforeach
                </select>
            </div>

            <label for="city" class="col-sm-1 col-form-label">Cidade</label>
            <div class="col-sm-7">
                <select class="form-control" id="city" name="city" required>
                    @foreach ($cities as $city)
                    <option value="{{ $city->name}}"{{ ($city->name == $establishment->city) ? ' selected ': ''}}>{{ $city->name }}</option>
                    @endforeach
                </select>                
            </div>
        </div>

        <div class="form-group row">
            <label for="phone" class="col-sm-2 col-form-label">Telefone</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $establishment->phone ?? '' }}">
            </div>

            <label for="zipcode" class="col-sm-2 col-form-label">CEP</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="zipcode" name="zipcode" value="{{ $establishment->zipcode }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $establishment->latitude }}" required>
            </div>

            <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $establishment->longitude }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="office" class="col-sm-2 col-form-label">Site</label>
            <div class="col-sm-10">
                <input type="url" class="form-control" id="site_url" name="site_url" value="{{ $establishment->site_url ?? '' }}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-2 text-right">Redes Sociais</div>
            <label for="urlFacebook" class="col-sm-2 col-form-label text-right">https://facebook.com/</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="facebook_url" name="facebook_url" value="{{ $establishment->facebook_url ?? '' }}">
            </div>

            <label for="instagram" class="col-sm-2 col-form-label text-right">https://instagram.com/</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="instagram_url" name="instagram_url" value="{{ $establishment->instagram_url ?? '' }}">
            </div>            
        </div>

        <div class="form-group row">
            <label for="latitude" class="col-sm-2 col-form-label">Categorias</label>
            <div class="col-sm-10">
                <select class="form-control" name="category_id" id="category_id">
                    <option>Selecione a categoria</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ ($establishment->category_id == $category->id) ? 'selected' : '' }} >{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="form-group row">
            <label for="pic_url" class="col-sm-2 col-form-label">Imagem</label>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-6" id="image_preview">
                        <img src="{{url('/storage')}}/{{$imagePath}}/{{$establishment->photo->filename ?? ''}}" class="img-thumbnail">
                    </div>
                    <div class="col-lg-10 col-md-6 col-6">
                        <div class="upload-btn-wrapper">
                            <button class="btn btn-primary">Escolha a imagem</button>
                            <input type="hidden" name="image" value="{{$establishment->photo->filename ?? ''}}"/>
                            <input type="file" id="image" name="image"/>
                            <p class="small">Formatos aceitos: jpeg, jpg e png.</p>
                        </div>
                        <div class="image_data">
                            {{--@foreach ($establishment['photos'] as $photo)--}}
                            {{--<input type="hidden" id="image" name="image[]" value="{{ $photo }}">--}}
                            {{--@endforeach--}}
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm"><strong>Avaliações:</strong> {{ $establishment['reviews']['total'] }}</div>
            <div class="col-sm"><strong>Voltaria: </strong> {{ $establishment['reviews']['wouldComeBack'] }}</div>
            <div class="col-sm"><strong>Não Voltaria:</strong> {{ $establishment['reviews']['wouldNotComeBack'] }}</div>
            <div class="col-sm"><strong>Quer ir:</strong> {{ $establishment['wantToGo'] }}</div>
        </div>
        <hr>

        <p>
            <h3>Filtros</h3>
        </p>

        @foreach ($filters as $key => $filter)
        <p>
            <h5>{{ $filter['name'] }}</h5>
        </p>

        <div class="row">
        @php
            $i = 0;
        @endphp
            @foreach ($filter['data'] as $subfilter)
        @php
            $checked = '';

            if(in_array($subfilter['id'],  $filtersSelecteds[$key])) {
                $checked = 'checked="checked"';
            }
        @endphp

        <div class="col-sm-3">
            <div class="custom-control custom-{{ $key == 'priceRanges' ? 'radio' : 'checkbox' }}">
            <input type="{{ $key == 'priceRanges' ? 'radio' : 'checkbox' }}" class="custom-control-input" name="{{ $key }}[]" id="{{ $key }}_{{ $i }}" value="{{ $subfilter['id'] }}" {{ $checked }}>
                <label class="custom-control-label" for="{{ $key }}_{{ $i }}">{{ $subfilter['name'] }}</label>
            </div>
        </div>

        @php
            $i++;
            @endphp
        @endforeach
    </div>
    @endforeach

    <hr>

    <div class="col-sm-12 text-right">
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>

</form>
@endsection
