<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagens extends Model
{
    use HasFactory;

    protected $table = 'mensagens';
    protected $fillable = [
        'chat_id',
        'id_origem',
        'id_destino',
        'mensagem',
        'url'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    public function usuarioOrigem()
    {
        return $this->belongsTo(User::class, 'id_origem');
    }
}
