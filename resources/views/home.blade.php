@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="padding-bottom: 0;">
      
        <!-- Formulario para crear un nuevo post -->
    <div class="mb-4">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-lg rounded">
            @csrf
            <h4 class="card-title text-center mb-4">Crear un Nuevo Post</h4>

            <div class="form-group">
                <label for="content" class="font-weight-bold">Contenido</label>
                <textarea class="form-control" id="content" name="content" rows="4" required placeholder="Escribe aquí tu contenido..." style="resize: none;"></textarea>
            </div>

            <div class="form-group">
                <label for="image" class="font-weight-bold">Imagen (opcional)</label>
                <input type="file" class="form-control-file" id="image" name="image">
                <small class="form-text text-muted">Puedes subir una imagen para acompañar tu post.</small>
            </div>

            <button type="submit" class="btn btn-success btn-block mt-3">Crear Post</button>
        </form>
    </div>

    @if($posts->isEmpty())
        <div class="alert alert-info">No hay posts para mostrar.</div>
    @else
        @foreach($posts as $post)
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : asset('images/default-profile.png') }}" class="user-picture" alt="Imagen del usuario">
                        <h4 class="card-title ms-3" style="font-size: 1.5em;">{{ $post->user->name }}</h4>
                    </div>

                    <p class="card-text m-2">{{ $post->content }}</p>
                    
                    
                    @if($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" class="img-fluid mx-auto d-block" alt="Imagen del post">
                    @endif
                    
                    <p class="card-text"><small class="text-muted">{{ $post->created_at->diffForHumans() }}</small></p>

                    <!-- Sección de Likes -->
                    <div class="d-flex justify-content-center">
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-light" {{ $post->likedBy(auth()->user()) ? 'disabled' : '' }}>
                                👍
                            </button>
                        </form>

                        <form action="{{ route('posts.unlike', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light" {{ !$post->likedBy(auth()->user()) ? 'disabled' : '' }}>
                                👎
                            </button>
                        </form>

                        <span class="ms-3">{{ $post->likes()->count() }} Likes</span>
                    </div>

                    <!-- Mostrar los íconos de editar y eliminar solo si el usuario es el propietario del post -->
                    @if(Auth::check() && $post->user_id === Auth::id())
                        <div class="mt-2">
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-pencil-alt"></i> Editar
                            </a>
                            
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este post?')">
                                    <i class="fas fa-times"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Sección de comentarios -->
                <div class="card-footer">
            
                    @foreach($post->comments as $comment)
                        <div class="mb-2 d-flex align-items-start">
                            <img src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : asset('images/default-profile.png') }}" class="user-picture-small me-2" alt="Imagen del usuario">
                            <div>
                                <strong class="mx-2">{{ $comment->user->name }}:</strong>
                                <p>{{ $comment->content }}</p>
                            </div>
                        </div>
                    @endforeach

                    @if(Auth::check())
                        <form action="{{ route('comments.store', $post) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" name="content" rows="2" placeholder="Escribe un comentario..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary">Comentar</button>
                        </form>
                    @else
                        <p>Inicia sesión para comentar.</p>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Enlaces de paginación -->
        <div class="d-flex justify-content-center">
            {{ $posts->links() }} <!-- Esto generará los enlaces de paginación -->
        </div>
    @endif
</div>


@endsection