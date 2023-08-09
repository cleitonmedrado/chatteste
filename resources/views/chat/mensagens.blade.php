@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $servico->titulo }}</h1>
        <div class="mensagens-container">
            @foreach($mensagens as $mensagem)
                <div
                    class="mensagem {{ $mensagem->id_origem == auth()->id() ? 'mensagem-resposta' : 'mensagem-recebida' }}">
                    <div class="conteudo">
                        <div class="info">
                            <span
                                class="nome">{{ $mensagem->id_origem == auth()->id() ? 'Você' : $mensagem->usuarioOrigem->name }}</span>
                            <span class="hora"> &nbsp; {{ $mensagem->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="balao-mensagem">
                            @if($mensagem->tipo_anexo === 'audio')
                                <audio controls>
                                    <source src="{{ asset($mensagem->url) }}" type="audio/mpeg">
                                    Seu navegador não suporta o elemento de áudio.
                                </audio>
                            @elseif($mensagem->tipo_anexo === 'imagem')
                                <img src="{{ asset($mensagem->url) }}" alt="Imagem"
                                     class="mensagem-imagem">
                            @elseif($mensagem->tipo_anexo === 'arquivo')
                                <a href="{{ asset($mensagem->url) }}" target="_blank">Baixar Arquivo</a>
                            @endif
                            {{ $mensagem->mensagem }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="formulario-mensagem">
            <form action="{{ route('enviar.mensagem') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="chat_id" value="{{ $chat->id ?? null }}">
                <input type="hidden" name="servico_id" value="{{ $servico->id ?? null }}">
                <div class="input-group">
                    <textarea name="mensagem" class="form-control" placeholder="Digite sua mensagem..."
                              rows="3"></textarea>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
                <div class="custom-file mt-2">
                    <input type="file" class="custom-file-input" id="anexo" name="anexo">
                    <label class="custom-file-label" for="anexo">Escolher arquivo</label>
                </div>
            </form>
        </div>
    </div>
    <script>
        rolarAoFinal();
    </script>
@endsection
