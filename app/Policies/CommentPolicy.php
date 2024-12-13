<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Permitir que los usuarios puedan ver cualquier comentario
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true; // Permitir que los usuarios puedan ver un comentario específico
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Permitir que los usuarios puedan crear comentarios
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Permitir que el usuario actualice el comentario solo si es el autor
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Permitir que el usuario elimine el comentario solo si es el autor
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        // Puedes permitir la restauración de un comentario bajo una condición similar o según tu lógica
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        // Permitir que el usuario elimine permanentemente el comentario solo si es el autor
        return $user->id === $comment->user_id;
    }
}
