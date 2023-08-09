<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $fillable = [
        'servico_id',
        'user_id_chat',
    ];

    public function chats()
    {
        return $this->hasMany(Servico::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_chat');
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagens::class, 'chat_id');
    }

    public function ultimaMensagem()
    {
        return $this->hasOne(Mensagens::class, 'chat_id')->latest();
    }
}
