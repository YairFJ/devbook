<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment; // Asegúrate de importar el modelo Comment

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'friend_id', 'status'];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    // Evento de eliminación
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($friendship) {
            // Eliminar todos los comentarios del amigo que se está eliminando
            Comment::where('user_id', $friendship->friend_id)->delete();
        });
    }
}