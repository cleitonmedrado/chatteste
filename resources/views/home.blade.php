@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista de serviços</h1>
        <hr>
        @foreach($servicos as $servico)
            <div class="card mb-4">
                <div class="card-header">
                    {{ $servico->titulo }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <img class="img-thumbnail" src="{{ asset($servico->url) }}" alt="Imagem"
                                 style="max-width: 200px;">
                        </div>
                        <div class="col-sm-6">
                            <span>{{$servico->descricao}}</span>
                            <br>
                            @if($servico->user_id!= auth()->id())
                                <a class="btn btn-primary ml-2" href="{{ route('iniciar.novo.chat', $servico->id) }}">Iniciar
                                    Novo Chat</a>
                            @else
                                <a class="btn btn-secondary" href="{{ route('ver.mensagens.servico', $servico->id) }}">Ver
                                    Mensagens</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-lg-12">
            {!! $servicos->links('pagination::tailwind') !!}
        </div>
    </div>
@endsection
