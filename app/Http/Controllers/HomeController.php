<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post; // Asegúrate de importar el modelo Post
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Obtener amigos del usuario autenticado
        $friends = $user->friends;

        // Obtener posts del usuario y de sus amigos
        $posts = Post::with(['user', 'likes'])
                    ->whereIn('user_id', $friends->pluck('id')->merge([$user->id])) // Incluir el ID del usuario
                    ->latest() // Obtener los más recientes
                    ->paginate(10);

        // Pasar los posts a la vista
        return view('home', compact('posts'));
    }
}