@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2>Gestión de Amigos</h2>

    <!-- Mostrar mensajes de éxito o error -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario para enviar una solicitud de amistad -->
    <div class="mb-4">
        <form action="{{ route('friends.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="friendId">ID del Amigo</label>
                <input type="number" class="form-control" id="friendId" name="friendId" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Solicitud de Amistad</button>
        </form>
    </div>

    <!-- Solicitudes Pendientes -->
    <h4>Solicitudes Pendientes</h4>
    @if($pendingRequests->isEmpty())
        <div class="alert alert-info">No tienes solicitudes pendientes.</div>
    @else
        <ul class="list-group">
            @foreach($pendingRequests as $request)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $request->user->name }}
                    <div>
                        <form action="{{ route('friends.accept', $request->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Aceptar</button>
                        </form>
                        <form action="{{ route('friends.decline', $request->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Lista de Amigos -->
    <h4>Lista de Amigos</h4>
    @if($friends->isEmpty())
        <div class="alert alert-info">No tienes amigos.</div>
    @else
        <ul class="list-group">
            @foreach($friends as $friend)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $friend->name }}
                    <form action="{{ route('friends.destroy', $friend->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este amigo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar Amigo</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Lista de Amigos Inactivos -->
    <h4>Amigos Inactivos</h4>
    @if($inactiveFriends->isEmpty())
        <div class="alert alert-info">No tienes amigos inactivos.</div>
    @else
        <ul class="list-group">
            @foreach($inactiveFriends as $friendship)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $friendship->friend->name }} <!-- Muestra el nombre del amigo -->
                    <form action="{{ route('friends.reactivate', $friendship->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning btn-sm">Reactivar Amistad</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif

</div>
@endsection