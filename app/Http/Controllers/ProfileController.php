<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $foto = $request->file('foto')?->store('fotos', 'public');
        $userData = array_intersect_key($validated, array_flip([
            'nombre',
            'email',
            'telefono',
            'celular',
            'direccion',
        ]));

        if ($foto) {
            $userData['foto'] = $foto;
        }

        $user->fill($userData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($miembro = $user->miembro) {
            $miembroData = array_intersect_key($validated, array_flip([
                'tipo_sangre',
                'numero_referencia',
                'contacto_emergencia',
                'telefono_emergencia',
                'estado_civil',
                'conyuge',
                'hijos',
                'profesion',
            ]));

            if ($foto) {
                $miembroData['foto'] = $foto;
            }

            $miembro->update($miembroData);
        }

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
