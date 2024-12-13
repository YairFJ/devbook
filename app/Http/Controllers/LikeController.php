<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // Dar like a un post
  
    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $user = Auth::user();

        // Verifica si el usuario ya le dio like al post
        if ($user->likedPosts()->where('post_id', $post->id)->exists()) {
            return back()->with('error', 'Ya has dado like a este post.');
        }

        // Agrega el like
        $user->likedPosts()->attach($post->id);
        return back()->with('success', 'Has dado like al post.');
    }
    // Quitar like a un post
    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        $user = Auth::user();
    
        // Verifica si el like existe
        if (!$user->likedPosts()->where('post_id', $post->id)->exists()) {
            return back()->with('error', 'No has dado like a este post.');
        }
    
        // Elimina el like
        $user->likedPosts()->detach($post->id);
        return back()->with('success', 'Has quitado el like del post.');
    }
}