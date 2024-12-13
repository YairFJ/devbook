<div class="card mb-3" id="post-{{ $post->id }}">
    <div class="card-body text-center">
        <h4 class="card-title" style="font-size: 1.5em;">{{ $post->user->name }}</h4>
        <p class="card-text">{{ $post->content }}</p>
        
        @if($post->image_path)
            <img src="{{ asset('storage/' . $post->image_path) }}" class="img-fluid mx-auto d-block" alt="Imagen del post">
        @endif
        
        <p class="card-text"><small class="text-muted">{{ $post->created_at->diffForHumans() }}</small></p>
    </div>

    <!-- Sección de comentarios -->
    <div class="card-footer">
        <h5>Comentarios</h5>
        @foreach($post->comments as $comment)
            <div class="mb-2">
                <strong>{{ $comment->user->name }}:</strong>
                <p>{{ $comment->content }}</p>
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