<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ServicoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/troca-mensagens/{chat}', [ChatController::class, 'exibirTrocaMensagens'])->name('troca.mensagens');
Route::post('/enviar-mensagem', [ChatController::class, 'enviarMensagem'])->name('enviar.mensagem');
Route::get('/responder-mensagens/{chat}', [ChatController::class, 'responderMensagens'])->name('responder.mensagens');
Route::get('/todos-chats', [ChatController::class, 'listarTodosChats'])->name('todos.chats');
Route::get('/iniciar-novo-chat/{servicoId}', [ChatController::class, 'iniciarNovoChat'])->name('iniciar.novo.chat');
Route::get('/ver-mensagens/{servico_id}', [ChatController::class, 'verMensagensServico'])->name('ver.mensagens.servico');

Route::get('/servico', [ServicoController::class, 'index'])->name('servicos');
Route::get('/editar/{id}', [ServicoController::class, 'editar'])->name('editar.servico');
Route::post('/salvar', [ServicoController::class, 'salvar'])->name('salvar.servico');
Route::get('/novo', [ServicoController::class, 'novo'])->name('novo.servico');



