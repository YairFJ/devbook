<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use App\Models\User;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Like;


class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Obtener los IDs de amigos aceptados
        $friendIds = $user->friends()
            ->where('friendships.status', FriendshipStatus::ACCEPTED->value)
            ->pluck('friend_id');

        // Recuperar los posts de amigos aceptados
        $posts = Post::whereIn('user_id', $friendIds)->paginate(10);

        // Obtener solicitudes pendientes
        $pendingRequests = Friendship::where('friend_id', $user->id)
            ->where('status', FriendshipStatus::PENDING->value)
            ->with('user')
            ->get();

        // Obtener amigos aceptados
        $friends = $user->friends()
            ->where('friendships.status', FriendshipStatus::ACCEPTED->value)
            ->get();

        // Obtener amigos inactivos
        $inactiveFriends = Friendship::where('user_id', $user->id)
            ->where('status', FriendshipStatus::INACTIVE->value)
            ->with('friend') // Asegúrate de cargar la relación con el amigo
            ->get();

        // Pasar datos a la vista
        return view('friends.index', compact('pendingRequests', 'friends', 'posts', 'inactiveFriends'));
    }

    /**
     * Enviar una solicitud de amistad.
     */
    public function store(Request $request)
    {
        $request->validate([
            'friendId' => 'required|integer', // Asegúrate de que sea un número entero
        ]);
    
        try {
            $user = Auth::user();
    
            // Verificar si el usuario intenta enviarse una solicitud a sí mismo
            if ($user->id == $request->friendId) {
                return redirect()->back()->with('error', 'No puedes enviarte una solicitud de amistad a ti mismo.');
            }
    
            // Verificar si el ID del amigo existe
            $friend = User::find($request->friendId);
            if (!$friend) {
                return redirect()->back()->with('error', 'El ID de amigo ingresado no es válido.');
            }
    
            // Verificar si ya son amigos o si la solicitud ya fue enviada
            if ($user->friends()->where('friend_id', $request->friendId)->exists() ||
                Friendship::where('user_id', $user->id)
                        ->where('friend_id', $request->friendId)
                        ->where('status', FriendshipStatus::PENDING->value)
                        ->exists()) {
                return redirect()->back()->with('error', 'Ya son amigos o la solicitud fue enviada.');
            }
    
            // Crear la relación de amistad
            Friendship::create([
                'user_id' => $user->id,
                'friend_id' => $request->friendId,
                'status' => FriendshipStatus::PENDING->value,
            ]);
    
            // Crear la relación inversa (como pendiente)
            Friendship::create([
                'user_id' => $request->friendId,
                'friend_id' => $user->id,
                'status' => FriendshipStatus::PENDING->value,
            ]);
    
            return redirect()->back()->with('success', 'Solicitud de amistad enviada.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al enviar la solicitud: ' . $e->getMessage());
        }
    }

    public function reactivateFriendship($friendshipId)
    {
        try {
            $user = Auth::user();

            // Buscar la relación de amistad por ID
            $friendship = Friendship::find($friendshipId);

            // Verificar que la amistad existe y que pertenece al usuario
            if (!$friendship || $friendship->user_id !== $user->id) {
                return redirect()->back()->with('error', 'Amistad no encontrada.');
            }

            // Verificar que la amistad esté inactiva
            if ($friendship->status !== FriendshipStatus::INACTIVE->value) {
                return redirect()->back()->with('error', 'La amistad no está inactiva.');
            }

            // Cambiar el estado a activo
            $friendship->status = FriendshipStatus::ACCEPTED->value;
            $friendship->save();

            return redirect()->back()->with('success', 'Amistad reactivada.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al reactivar la amistad: ' . $e->getMessage());
        }
}

    /**
     * Aceptar una solicitud de amistad.
     */
    public function acceptRequest($friendshipId)
{
    try {
        $friendship = Friendship::findOrFail($friendshipId);

        // Verifica que el usuario autenticado sea el destinatario
        if ($friendship->friend_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para aceptar esta solicitud.');
        }

        // Cambia el estado a aceptada
        $friendship->status = FriendshipStatus::ACCEPTED->value;
        $friendship->save();

        // Actualizar la relación inversa a aceptada
        Friendship::where('user_id', $friendship->user_id)
            ->where('friend_id', Auth::id())
            ->update(['status' => FriendshipStatus::ACCEPTED->value]);

        return redirect()->back()->with('success', 'Solicitud de amistad aceptada.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al aceptar la solicitud: ' . $e->getMessage());
    }
}

    /**
     * Rechazar una solicitud de amistad.
     */
    public function declineRequest($friendshipId)
    {
        try {
            $friendship = Friendship::findOrFail($friendshipId);
            $friendship->delete(); // Elimina la solicitud

            return redirect()->back()->with('success', 'Solicitud de amistad rechazada.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar a un amigo.
     */
 
     public function removeFriend($friendId)
    {
        try {
            $user = Auth::user();

            // Buscar la relación de amistad
            $friendship = Friendship::where('user_id', $user->id)
                ->where('friend_id', $friendId)
                ->first();

            if (!$friendship) {
                return redirect()->back()->with('error', 'No tienes amistad con este usuario.');
            }

            // Marcar la amistad como inactiva
            $friendship->status = FriendshipStatus::INACTIVE->value; // Cambiar el estado a inactivo
            $friendship->save();

            // Eliminar todos los comentarios del amigo
            Comment::where('user_id', $friendId)->delete();

            // Eliminar todos los likes del amigo en los posts del usuario
            Like::where('user_id', $friendId)->delete();

            return redirect()->back()->with('success', 'Amistad marcada como inactiva, comentarios y likes eliminados.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la amistad: ' . $e->getMessage());
        }
    }
}