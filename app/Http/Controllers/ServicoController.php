<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $servicos = Servico::where('user_id', auth()->id())->paginate();
        return view('servicos/list', compact('servicos'));
    }

    public function editar($id)
    {
        $servico = Servico::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$servico) {
            return redirect()->route('servicos');
        }
        return view('servicos/create', compact('servico'));
    }

    public function salvar(Request $request)
    {
        if ($request->id) {
            $servico = Servico::find($request->id);
        } else {
            $servico = new Servico();
            $servico->user_id = auth()->id();
        }
        try {
            $servico->titulo = $request->titulo;
            $servico->descricao = $request->descricao;
            $imagem = str_replace('/storage', 'public', $servico->url);
            if ($request->hasFile('nova_imagem')) {
                // Deletar a imagem atual se existir
                if (Storage::exists($imagem)) {
                    Storage::delete($imagem);
                }
                $novaImagem = $request->file('nova_imagem');
                $caminhoNovaImagem = $novaImagem->store('imagens/servicos', 'public');
                // Atualizar o caminho da nova imagem no banco de dados
                $servico->url = Storage::url($caminhoNovaImagem);
            }
            $servico->save();
            session()->flash('success', 'Salvo com sucesso!');
            return redirect()->route('servicos');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocorreu um erro ao salvar.');
            return back(); // Redirecionar de volta à página anterior
        }

    }

    public function novo()
    {
        $servico = new Servico();
        return view('servicos.create', ['servico' => $servico]);
    }

}
