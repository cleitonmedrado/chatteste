@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Meus serviços</h1>
        <div>
            <a href="{{ route('novo.servico') }}">
                Novo serviço
            </a>
        </div>
        <hr>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <table class="table table-responsive table-striped">
            <thead>
            <th>Id</th>
            <th>Titulo</th>
            <th class="text-center">Opções</th>
            </thead>
            <tbody>
            @foreach($servicos as $servico)

                <tr>
                    <td>{{ $servico->id }}</td>
                    <td>{{ $servico->titulo }}</td>
                    <td class="text-center">
                        <a href="{{ route('editar.servico', $servico->id) }}"
                                               class="btn btn-outline-success">Editar</a>
                        <a class="btn btn-outline-secondary" href="{{ route('ver.mensagens.servico', $servico->id) }}">Ver
                            Mensagens</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="col-lg-12">
            {!! $servicos->links('pagination::tailwind') !!}
        </div>
    </div>
@endsection
