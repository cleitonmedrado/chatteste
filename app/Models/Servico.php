<?php

namespace App\Models;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
