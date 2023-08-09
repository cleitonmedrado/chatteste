<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Mensagens;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function exibirTrocaMensagens($id)
    {
        $servico = Servico::find($id);
        $user = auth()->user();

        if (!$servico) {
            // Lidar com o caso em que o serviço não é encontrado
            return redirect()->back()->with('error', 'Serviço não encontrado.');
        }

        $chat = Chat::where('servico_id', $servico->id)
            ->where(function ($query) use ($user, $servico) {
                $query->where('user_id_chat', $user->id)
                    ->orWhere('user_id_chat', $servico->user_id);
            })
            ->first();

        $mensagens = [];

        if ($chat) {
            $mensagens = Mensagens::where('chat_id', $chat->id)->get();
        }

        return view('chat.mensagens', compact('chat', 'mensagens', 'servico'));
    }


    public function enviarMensagem(Request $request)
    {
        $chat = Chat::find($request->input('chat_id'));
        $servico_id = $request->input('servico_id');

        if (!$chat) {
            $servico = Servico::find($servico_id);
            if (!$servico) {
                return back()->with('error', 'Serviço não encontrado.');
            }

            $chat = new Chat([
                'servico_id' => $servico_id,
                'user_id_chat' => auth()->id(),
            ]);

            $chat->save();
        }

        $id_destino = ($chat->servico->user_id == auth()->id()) ? $chat->user_id_chat : $chat->servico->user_id;

        if (!empty($request->input('mensagem'))) {
            $mensagem = new Mensagens([
                'chat_id' => $chat->id,
                'id_origem' => auth()->user()->id,
                'id_destino' => $id_destino,
                'mensagem' => $request->input('mensagem'),
                'url' => '',
                'tipo_anexo' => ''
            ]);

            if ($request->hasFile('anexo')) {
                $anexo = $request->file('anexo');
                $path = $anexo->store('anexos', 'public');
                $mensagem->url = Storage::url($path);

                $extensao = $anexo->getClientOriginalExtension();
                if (in_array($extensao, ['mp3', 'wav', 'ogg'])) {
                    $mensagem->tipo_anexo = 'audio';
                } elseif (in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $mensagem->tipo_anexo = 'imagem';
                } else {
                    $mensagem->tipo_anexo = 'arquivo';
                }
            }

            $mensagem->save();
        }
        return back();
    }


    public function responderMensagens(Chat $chat)
    {
        // Carregue as mensagens relacionadas a este chat, se necessário
        $mensagens = Mensagens::where('chat_id', $chat->id)->get();

        // Carregue o serviço associado ao chat
        $servico = $chat->servico;

        return view('chat.mensagens', compact('chat', 'mensagens', 'servico'));
    }

    public function listarTodosChats()
    {
        $user_id = auth()->id();

        $chats = Chat::select('chats.*')
            ->joinSub(function ($query) {
                $query->from('mensagens')
                    ->select('chat_id', DB::raw('max(created_at) as ultima_mensagem'))
                    ->groupBy('chat_id');
            }, 'ultimaMensagem', function ($join) {
                $join->on('chats.id', '=', 'ultimaMensagem.chat_id');
            })
            ->join('servicos', 'chats.servico_id', '=', 'servicos.id')
            ->join('users', 'servicos.user_id', '=', 'users.id')
            ->select('chats.*', 'users.name as nome_usuario', 'servicos.titulo as nome_servico')
            ->where('chats.user_id_chat', $user_id)
            ->orWhere('servicos.user_id', $user_id)
            ->orderByDesc('ultimaMensagem.ultima_mensagem')
            ->paginate(15);

        return view('chat.todos_chats', compact('chats'));
    }

    //************************************************************************************
    public function iniciarNovoChat($servicoId)
    {
        $user = auth()->user();
        $servico = Servico::find($servicoId);

        if (!$servico) {
            return redirect()->route('home')->with('error', 'Serviço não encontrado.');
        }

        // Verifica se já existe um chat em andamento
        $existingChat = Chat::where('servico_id', $servicoId)
            ->where(function ($query) use ($user, $servico) {
                $query->where('user_id_chat', $user->id)
                    ->orWhere('user_id_chat', $servico->user_id);
            })
            ->first();

        if (!$existingChat) {
            // Cria um novo chat
            $newChat = new Chat();
            $newChat->servico_id = $servico->id;
            $newChat->user_id_chat = $user->id;
            $newChat->save();

            return redirect()->route('troca.mensagens', $servico->id)->with('success', 'Novo chat iniciado.');
        }

        return redirect()->route('troca.mensagens', $servico->id)->with('error', 'Chat já existe.');
    }

    public function verMensagensServico($servico_id)
    {
        $servico = Servico::find($servico_id);
        $user_id = auth()->id();

        if (!$servico) {
            return back()->with('error', 'Serviço não encontrado.');
        }

        $chats = Chat::select('chats.*')
            ->joinSub(function ($query) {
                $query->from('mensagens')
                    ->select('chat_id', DB::raw('max(created_at) as ultima_mensagem'))
                    ->groupBy('chat_id');
            }, 'ultimaMensagem', function ($join) {
                $join->on('chats.id', '=', 'ultimaMensagem.chat_id');
            })
            ->join('servicos', 'chats.servico_id', '=', 'servicos.id')
            ->join('users', 'servicos.user_id', '=', 'users.id')
            ->select('chats.*', 'users.name as nome_usuario', 'servicos.titulo as nome_servico')
            ->where('chats.user_id_chat', $user_id)
            ->where('chats.servico_id', $servico_id)
            ->orWhere('servicos.user_id', $user_id)
            ->Where('servicos.id', $servico_id)
            ->orderByDesc('ultimaMensagem.ultima_mensagem')
            ->paginate(15);

        return view('chat.servico_chats', compact('chats'));
    }


}
