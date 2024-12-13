@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Editar Post</h1>

    <!-- Formulario para editar el post -->
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="content">Contenido</label>
            <textarea class="form-control" id="content" name="content" rows="3" required>{{ $post->content }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="image">Imagen (opcional)</label>
            @if ($post->image_path)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Imagen del post" class="img-fluid">
                </div>
            @endif
            <input type="file" class="form-control-file" id="image" name="image">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Post</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
