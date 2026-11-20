<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    private const PRIMARY_ADMIN_EMAIL = 'admin@iglesia.com';

    public function index(): View
    {
        $usuarios = User::with('roles')->orderBy('created_at', 'desc')->paginate(15);

        return view('usuarios.index', compact('usuarios'));
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'miembro']);

        return view('usuarios.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('nombre')->get();
        $user->load('roles');

        return view('usuarios.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['integer', Rule::exists('roles', 'id')],
        ]);

        $roles = array_map('intval', $validated['roles']);
        $adminRoleId = Role::where('nombre', 'Administrador')->value('id');

        if (
            $adminRoleId
            && ($user->is(auth()->user()) || $this->esAdministradorPrincipal($user))
            && ! in_array((int) $adminRoleId, $roles, true)
        ) {
            $roles[] = (int) $adminRoleId;
        }

        $user->update([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
        ]);
        $user->roles()->sync(array_unique($roles));

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function cambiarEstado(User $user): RedirectResponse
    {
        abort_if($this->esProtegido($user), 403, 'Este usuario no puede ser desactivado.');

        $nuevoEstado = $user->estado === 'activo' ? 'inactivo' : 'activo';
        $user->update(['estado' => $nuevoEstado]);

        return back()->with('success', 'Estado del usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($this->esProtegido($user), 403, 'Este usuario no puede ser eliminado.');

        $foto = $user->foto;
        DB::transaction(function () use ($user): void {
            $user->roles()->detach();
            $user->delete();
        });

        if ($foto) {
            Storage::disk('public')->delete($foto);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    private function esProtegido(User $user): bool
    {
        return $user->is(auth()->user())
            || $this->esAdministradorPrincipal($user)
            || $this->esUnicoAdministrador($user);
    }

    private function esAdministradorPrincipal(User $user): bool
    {
        return $user->email === self::PRIMARY_ADMIN_EMAIL;
    }

    private function esUnicoAdministrador(User $user): bool
    {
        if (! $user->tieneRol('Administrador')) {
            return false;
        }

        return User::whereHas('roles', function ($query): void {
            $query->where('nombre', 'Administrador');
        })->count() <= 1;
    }
}
