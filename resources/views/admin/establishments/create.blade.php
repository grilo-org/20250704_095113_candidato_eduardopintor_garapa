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

    <form method="post" action="{{ route('experiencias.store') }}" enctype="multipart/form-data" id="editor">
        <div class="row page-title">
            <div class="col-sm-8">
                <h3>Dados do Estabelecimento</h3>
            </div>    
            <label for="status" class="col-sm-3 col-form-label">Ativo</label>
            <div class="col-sm-1 text-right">
                <div class="switch_box box_1">
                    <input type="checkbox" class="switch_1" name="status" id="status" value="1" checked="checked">
                </div>
            </div>
        </div>

        @csrf

        @if(Auth::user()->hasRole('admin'))
        <div class="form-group row">
            <label for="curatorId" class="col-sm-2 col-form-label">Curador</label>
            <div class="col-sm-10">
                <select class="form-control select2" multiple="multiple" id="curatorId" name="curatorId[]" required="required">
                    <option value="" disabled>Selecione o curador</option>
                    @foreach ($curators as $curator)
                        <option value="{{ $curator->id }}">{{ $curator->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

         <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="office" class="col-sm-2 col-form-label">Endereço</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="street" name="address[street]" value="{{old("address.street")}}" required>
            </div>
            <label for="office" class="col-sm-1 col-form-label">Número</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="number" name="address[number]" value="{{old("address.number")}}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="neighborhood" class="col-sm-2 col-form-label">Bairro</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="neighborhood" name="address[neighborhood]" value="{{old("address.neighborhood")}}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="state" class="col-sm-2 col-form-label">Estado</label>
            <div class="col-sm-2">
                <select class="form-control" id="state" name="state">
                    <option value="">Selecione</option>
                    @foreach ($states as $state)
                    <option value="{{ $state->abbreviation}}">{{ $state->abbreviation }}</option>
                    @endforeach                    
                </select>
            </div>

            <label for="city" class="col-sm-1 col-form-label">Cidade</label>
            <div class="col-sm-7">
                <select class="form-control" id="city" name="city" required="required" disabled="disabled">
                    <option value="">Selecione o estado primeiro</option>
                </select>                
            </div>
        </div>

        <div class="form-group row">
            <label for="phone" class="col-sm-2 col-form-label">Telefone</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="phone" name="address[phone]" value="{{old("address.phone")}}" >
            </div>

            <label for="zipcode" class="col-sm-2 col-form-label">CEP</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="zipcode" name="address[zipcode]" value="{{old("address.zipcode")}}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="latitude" name="geo[latitude]" value="{{old("geo.latitude")}}" required>
            </div>

            <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="longitude" name="geo[longitude]" value="{{old("geo.longitude")}}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="site_url" class="col-sm-2 col-form-label">Site</label>
            <div class="col-sm-10">
                <input type="url" class="form-control" id="site_url" name="site_url" value="{{old('site_url')}}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-2 text-right">Redes Sociais</div>
            <label for="facebook_url" class="col-sm-2 col-form-label text-right">https://facebook.com/</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="facebook_url" name="facebook_url" value="{{old('facebook_url')}}">
            </div>

            <label for="instagram_url" class="col-sm-2 col-form-label text-right">https://instagram.com/</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="instagram_url" name="instagram_url" value="{{old('instagram_url')}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="latitude" class="col-sm-2 col-form-label">Categorias</label>
            <div class="col-sm-10">
                <select class="form-control" name="category_id" id="category_id" required>
                    <option value="" selected disabled>Selecione a categoria</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="pic_url" class="col-sm-2 col-form-label">Imagem</label>
            <div class="col-sm-10">
                <div class="row">
                    {{--<div class="col-lg-2 col-md-6 col-6" id="image_preview">--}}
                        {{--<img src="//via.placeholder.com/600x400?text=imagem" class="img-thumbnail">--}}
                    {{--</div>--}}
                    <div class="col-lg-10 col-md-6 col-6">
                        <div class="upload-btn-wrapper">
                            <button class="btn btn-primary">Escolha a imagem</button>
                            <input type="hidden" name="image" value="https://via.placeholder.com/600x400&text=imagem"/>
                            <input type="file" id="image" name="image"/>
                            <p class="small">Formatos aceitos: jpeg, jpg e png.</p>
                        </div>
                        {{--<div class="progress">--}}
                            {{--<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
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

            <div class="col-sm-3">
                <div class="custom-control custom-{{ $key == 'priceRanges' ? 'radio' : 'checkbox' }}">
                    <input type="{{ $key == 'priceRanges' ? 'radio' : 'checkbox' }}" class="custom-control-input" name="{{ $key }}[]" id="{{ $key }}_{{ $i }}" value="{{ $subfilter['id'] }}">
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
