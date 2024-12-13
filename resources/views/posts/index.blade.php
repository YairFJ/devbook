<!-- resources/views/posts/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
</head>
<body>
    <h1>Lista de Posts</h1>

    @if ($posts->isEmpty())
        <p>No hay posts disponibles.</p>
    @else
        <ul>
            @foreach ($posts as $post)
                <li>
                    <strong>{{ $post->user->name }}</strong>
                    <p>{{ $post->content }}</p>
                    @if ($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Imagen del post">
                    @endif
                    <p>{{ $post->likes->count() }} Likes</p>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>