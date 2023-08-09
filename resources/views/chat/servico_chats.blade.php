@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Serviços Chats</h1>

        <table class="table">
            <thead>
            <tr>
                <th>Data</th>
                <th>Com quem</th>
                <th>Serviço</th>
                <th>Opções</th>
            </tr>
            </thead>
            <tbody>
            @foreach($chats as $chat)
                <tr>
                    <td>{{ date('d/m/Y H:i:s', strtotime($chat->created_at)) }}</td>
                    <td>{{ $chat->user_id_chat == auth()->id() ? $chat->servico->user->name : $chat->user->name }}</td>
                    <td>{{ $chat->servico->titulo }}</td>
                    <td>
                        <a href="{{ route('responder.mensagens', $chat->id) }}">abrir chat</a>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

        {{ $chats->links('pagination::tailwind') }} <!-- Links de paginação -->
    </div>
@endsection
