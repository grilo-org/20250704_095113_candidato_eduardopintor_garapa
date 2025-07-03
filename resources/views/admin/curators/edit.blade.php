@extends('admin.layouts.app')

@section('content')
    <h4>Dados Pessoais</h4>
    <hr>

    <form method="post" action="{{ route('curadores.update', ['id' => $id]) }}" enctype="multipart/form-data" id="editor">
        @csrf
                
        <input type="hidden" id="description" name="description" value="{{ (isset($curator['description'])) ? $curator['description'] : ''}}">

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{ $curator['name'] }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="occupation" class="col-sm-2 col-form-label">Profissão</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="occupation" name="occupation" value="{{ $curator['occupation'] }}" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-2 text-right">Redes Sociais</div>
            <label for="facebook_url" class="col-sm-2 col-form-label text-left">https://www.facebook.com/</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="facebook_url" name="facebook_url" value="{{ (isset($curator['facebook_url'])) ? $curator['facebook_url'] : '' }}">
            </div>

            <label for="instagram_url" class="col-sm-2 col-form-label text-left">https://www.instagram.com/</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="instagram_url" name="instagram_url" value="{{ (isset($curator['instagram_url'])) ? str_replace('@', '', $curator['instagram_url']) : '' }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="site_url" class="col-sm-2 col-form-label">Site</label>
            <div class="col-sm-10">
                <input type="url" class="form-control" id="site_url" name="site_url" value="{{ (isset($curator['site_url'])) ? $curator['site_url'] : '' }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="state" class="col-sm-2 col-form-label">Estado</label>
            <div class="col-sm-2">
                <select class="form-control" id="state" name="state">
                    <option value="">Selecione</option>
                    @foreach ($states as $state)
                    <option value="{{ $state->abbreviation}}"{{ ($state->abbreviation == $curator['state']) ? ' selected ': ''}}>{{ $state->abbreviation }}</option>
                    @endforeach
                </select>
            </div>
            
            <label for="city" class="col-sm-1 col-form-label">Cidade</label>
            <div class="col-sm-7">
                <select class="form-control" id="city" name="city">
                    @foreach ($cities as $city)
                    <option value="{{ $city->name}}"{{ ($city->name == $curator['city']) ? ' selected ': ''}}>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="biography" class="col-sm-2 col-form-label">Biografia</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="biography" name="biography" rows="5" required>{{ $curator['biography'] }}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="picture_url" class="col-sm-2 col-form-label">Imagem</label>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-6" id="image_preview">
                        @if(strpos($curator->picture_url, 'firebasestorage') || strpos($curator->picture_url, 'placeholder'))
                            <img src="{{$curator->picture_url}}" class="img-thumbnail">
                        @else
                            <img src="{{url('/storage')}}/{{$imagePath}}/{{$curator->picture_url}}" class="img-thumbnail" width="75">
                        @endif
                    </div>
                    <div class="col-lg-8 col-md-6 col-6 custom-file-upload">
                        <div class="upload-btn-wrapper">
                            <button class="btn btn-primary">Escolha a imagem</button>
                            <input type="hidden" name="image" value="{{$curator->picture_url}}"/>
                            <input type="file" id="image" name="image"/>
                            <p class="small">Formatos aceitos: jpeg, jpg e png.</p>
                        </div>
                        {{--<div class="image_data">--}}
                            {{--<input type="hidden" id="image" name="image" value="{{ $curator['picture_url'] }}" required>--}}
                        {{--</div>--}}
                        {{--<div class="progress">--}}
                            {{--<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>

        
        <h4>Comidas Favoritas</h4>
        <hr>

        <p>Digite o nome, no campo abaixo, para buscar o prato:</p>
        <div class="row">
            <div class="col-sm-12">
                <select class="form-control select2-foods" name="foodCurator[]" multiple="multiple">
                    @foreach($foods as $food)
                        <option value="{{ $food->id }}" {{ (in_array($food->id, $foodsCurator)) ? ' selected = "selected"' : '' }}>{{$food->food_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <p>&nbsp;</p>

        <h4>Acesso ao Sistema Administrativo</h4>
        <hr>
        
        <p>Dados de ascesso ao curador para este sistema:</p>

        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" value="{{ (isset($curator['email'])) ? $curator['email'] : '' }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Senha</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" value="{{ (isset($curator['password'])) ? $curator['password'] : '' }}">
                <small>Deixe em branco para manter a mesma senha.</small>
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

