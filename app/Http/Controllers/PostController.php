<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Importar el trait para autorizar

class PostController extends Controller
{
    use AuthorizesRequests; // Usar el trait para habilitar $this->authorize()

    // Mostrar todos los posts
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Obtener los IDs de los amigos del usuario actual
            $friendsIds = $user->friends()->pluck('friend_id')->toArray();
            $friendsIds[] = $user->id;  // Agregar al propio usuario para mostrar sus posts también
    
            // Obtener los posts de los amigos del usuario
            $posts = Post::with('user')->withCount('likes')
                          ->whereIn('user_id', $friendsIds)
                          ->latest()
                          ->paginate(10);
    
            return view('posts.index', compact('posts'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // Mostrar el formulario para crear un nuevo post
    public function create()
    {
        return view('posts.create');
    }

    // Almacenar un nuevo post
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = new Post();
        $post->content = $request->input('content');
        $post->user_id = Auth::id();  // El post pertenece al usuario autenticado

        // Manejar la subida de imagen
        if ($request->hasFile('image')) {
            $post->image_path = $request->file('image')->store('images', 'public');
        }

        $post->save();
        return redirect()->route('home')->with('success', 'Post creado con éxito.');
    }

    // Mostrar un post específico
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // Mostrar el formulario para editar un post (solo si el usuario tiene permisos)
    public function edit(Post $post)
    {
        // Verificar que el usuario actual es el propietario del post
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    // Actualizar un post
    // app/Http/Controllers/PostController.php

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post->content = $request->input('content');

        if ($request->hasFile('image')) {
            // Guardar nueva imagen si es necesario
            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image_path = $imagePath;
        }

        $post->save();

        return redirect()->route('home')->with('success', 'Post actualizado exitosamente.');
    }


    // Eliminar un post
    public function destroy(Post $post)
    {
        // Verificar que el usuario actual es el propietario del post
        $this->authorize('delete', $post);

        $post->delete();
        return back()->with('success', 'Post eliminado.');
    }
}
