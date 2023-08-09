@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar serviço</h1>
        <hr>
        <form action="{{ route("salvar.servico") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <input type="hidden" class="form-control" name="id"
                       value="{{ $servico->id ?? '' }}"/>
                @if(!$servico)
                    <div class="col-sm-3">
                        <span>ID</span>
                        <input type="text" class="form-control"
                               value="{{ $servico->id ?? '' }}" disabled/>
                    </div>
                @endif
                <div class="{{ $servico->id ? 'col-sm-9' : 'col-sm-12'}}">
                    <span>Titulo</span>
                    <input type="text" class="form-control" name="titulo"
                           value="{{ $servico->titulo ?? '' }}"/>
                </div>
            </div>
            <div class="row mb-3">
                <span>Descrição do serviço</span>
                <div class="col-sm-12">
                    <textarea rows="10" style="min-width: 100%"
                              name="descricao">{{ $servico->descricao ?? '' }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <label for="atual_imagem">Imagem Atual:</label>
                    <br>
                    @if($servico->url)
                        <img class="img-thumbnail" src="{{ asset($servico->url) ?? '' }}" alt="Imagem Atual"
                             style="max-width: 200px;">
                    @else
                        <p>Nenhuma imagem atual.</p>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="nova_imagem">Selecione uma nova imagem:</label>
                <input type="file" name="nova_imagem" id="nova_imagem" class="form-control-file">
            </div>
            <br><br>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{ route('servicos') }}" class="btn btn-danger">Voltar</a>
            </div>
        </form>
    </div>
@endsection
