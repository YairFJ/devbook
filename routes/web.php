<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

// Ruta de bienvenida, accesible para todos
Route::get('/', function () {
    return view('welcome'); // Esta vista es accesible sin autenticación
})->name('welcome');

// Ruta para el dashboard, protegida por middleware de autenticación
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Agrupación de rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home'); 
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
  
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{postId}/like', [LikeController::class, 'store'])->name('posts.like');
    Route::delete('/posts/{postId}/like', [LikeController::class, 'destroy'])->name('posts.unlike');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');

    // Enviar una solicitud de amistad
    Route::post('/send-friend-request', [FriendController::class, 'store'])->name('friends.store');

    // Aceptar una solicitud de amistad
    Route::post('/accept-friend-request/{friendshipId}', [FriendController::class, 'acceptRequest'])->name('friends.accept');
 
    // Rechazar una solicitud de amistad
    Route::post('/decline-friend-request/{friendshipId}', [FriendController::class, 'declineRequest'])->name('friends.decline');

    // Reactivar amistad
    Route::put('/friends/reactivate/{friendshipId}', [FriendController::class, 'reactivateFriendship'])->name('friends.reactivate');

    // Eliminar un amigo
    Route::delete('/remove-friend/{friendId}', [FriendController::class, 'removeFriend'])->name('friends.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');
    Route::post('/profile/delete-picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.delete.picture');
});

// Requiere la autenticación
require __DIR__.'/auth.php';