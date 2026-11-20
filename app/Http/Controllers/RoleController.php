<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    private const BASE_ROLES = [
        'Administrador',
        'Pastor Principal',
        'Secretaría',
        'Comité de Eventos',
        'Miembro',
    ];

    public function index(): View
    {
        $roles = Role::withCount('users')->orderBy('nombre')->paginate(15);

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:50', 'unique:roles,nombre'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function show(Role $role): View
    {
        $role->load('users');

        return view('roles.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:50', Rule::unique('roles', 'nombre')->ignore($role->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        if (in_array($role->nombre, self::BASE_ROLES, true) && $validated['nombre'] !== $role->nombre) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede renombrar un rol base del sistema.');
        }

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->exists()) {
            $mensaje = in_array($role->nombre, self::BASE_ROLES, true)
                ? 'No se puede eliminar un rol base que tiene usuarios asignados.'
                : 'No se puede eliminar un rol que tiene usuarios asignados.';

            return redirect()->route('roles.index')->with('error', $mensaje);
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
