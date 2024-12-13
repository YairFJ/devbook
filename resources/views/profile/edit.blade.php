@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Profile') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Sección para mostrar la imagen de perfil -->
            <div class="p-4 sm:p-8 bg-black dark:bg-gray-800 shadow sm:rounded-lg text-center">
                <div class="profile-container text-center">
                    @if (session('status'))
                        <div class="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                
                    <h2 class="user-name">{{ $user->name }}</h2>
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-picture">
                        <form method="POST" action="{{ route('profile.delete.picture') }}" class="mt-2">
                            @csrf
                            <x-primary-button class="bg-danger">{{ __('Delete Profile Picture') }}</x-primary-button>
                        </form>
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="profile-picture">
                    @endif
                </div>
            </div>

            <!-- Sección para actualizar la información del perfil -->
            <div class="p-4 sm:p-8 bg-black dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Sección para subir la imagen de perfil -->
            <div class="p-4 sm:p-8 bg-black dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-picture-form')
                </div>
            </div>

            <!-- Sección para actualizar la contraseña -->
            <div class="p-4 sm:p-8 bg-black dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Sección para eliminar el usuario -->
            <div class="p-4 sm:p-8 bg-black dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection