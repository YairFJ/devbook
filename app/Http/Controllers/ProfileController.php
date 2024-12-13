<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(), // Asegúrate de que esto sea correcto
        ]);
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            // Eliminar la imagen anterior si existe
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }

            // Guardar la nueva imagen en public
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');

            // Actualizar el campo en la base de datos
            $user->profile_picture = $path;
            $user->save();
        }

        return redirect()->back()->with('status', 'Profile picture updated successfully.');
    }

    public function deleteProfilePicture(Request $request)
    {
        $user = Auth::user();

        if ($user->profile_picture) {
            // Eliminar la imagen del almacenamiento
            Storage::delete($user->profile_picture);
            
            // Establecer la imagen de perfil como nula
            $user->profile_picture = null; // O puedes establecer una referencia a la imagen por defecto
            $user->save();
        }

        return redirect()->back()->with('status', 'Profile picture deleted successfully.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
