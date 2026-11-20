<?php

namespace App\Http\Controllers;

use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MiembroController extends Controller
{
    public function create(): View
    {
        return view('miembros.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'telefono' => ['nullable', 'string', 'max:20'],
            'celular' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'tipo_sangre' => ['nullable', 'string', 'max:10'],
            'numero_referencia' => ['nullable', 'string', 'max:255', 'unique:miembros,numero_referencia'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'contacto_emergencia' => ['nullable', 'string', 'max:255'],
            'telefono_emergencia' => ['nullable', 'string', 'max:255'],
            'estado_civil' => ['nullable', 'string', 'max:255'],
            'conyuge' => ['nullable', 'string', 'max:255'],
            'hijos' => ['nullable', 'integer', 'min:0'],
            'profesion' => ['nullable', 'string', 'max:255'],
        ]);

        $foto = $request->file('foto')?->store('miembros', 'public');

        try {
            DB::transaction(function () use ($validated, $foto): void {
                $user = User::create([
                    'nombre' => $validated['nombre'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'estado' => 'activo',
                    'telefono' => $validated['telefono'] ?? null,
                    'celular' => $validated['celular'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                    'foto' => $foto,
                ]);

                $role = Role::firstOrCreate(
                    ['nombre' => 'Miembro'],
                    ['descripcion' => 'Miembro activo de la congregación']
                );
                $user->roles()->attach($role);

                Miembro::create([
                    'user_id' => $user->id,
                    'telefono' => $validated['telefono'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                    'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                    'estado_miembro' => 'activo',
                    'tipo_sangre' => $validated['tipo_sangre'] ?? null,
                    'numero_referencia' => $validated['numero_referencia'] ?? null,
                    'foto' => $foto,
                    'contacto_emergencia' => $validated['contacto_emergencia'] ?? null,
                    'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
                    'estado_civil' => $validated['estado_civil'] ?? null,
                    'conyuge' => $validated['conyuge'] ?? null,
                    'hijos' => $validated['hijos'] ?? 0,
                    'profesion' => $validated['profesion'] ?? null,
                ]);
            });
        } catch (\Throwable $exception) {
            if ($foto) {
                Storage::disk('public')->delete($foto);
            }

            throw $exception;
        }

        return redirect()->route('miembros.index')->with('success', 'Miembro creado y activado correctamente.');
    }

    public function index(): View
    {
        $miembros = User::whereHas('roles', function ($query): void {
            $query->where('nombre', 'Miembro');
        })->with('miembro')->orderBy('nombre')->paginate(15);

        return view('miembros.index', compact('miembros'));
    }

    public function show(User $user): View
    {
        $this->abortIfNotMiembro($user);
        $user->load(['roles', 'miembro']);

        return view('miembros.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->abortIfNotMiembro($user);
        $user->load('miembro');

        return view('miembros.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->abortIfNotMiembro($user);
        $miembro = $user->miembro()->firstOrFail();

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telefono' => ['nullable', 'string', 'max:20'],
            'celular' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'tipo_sangre' => ['nullable', 'string', 'max:10'],
            'numero_referencia' => ['nullable', 'string', 'max:255', Rule::unique('miembros', 'numero_referencia')->ignore($miembro->id)],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'contacto_emergencia' => ['nullable', 'string', 'max:255'],
            'telefono_emergencia' => ['nullable', 'string', 'max:255'],
            'estado_civil' => ['nullable', 'string', 'max:255'],
            'conyuge' => ['nullable', 'string', 'max:255'],
            'hijos' => ['nullable', 'integer', 'min:0'],
            'profesion' => ['nullable', 'string', 'max:255'],
        ]);

        $fotoNueva = $request->file('foto')?->store('miembros', 'public');
        $fotoAnterior = $user->foto;

        DB::transaction(function () use ($validated, $user, $miembro, $fotoNueva): void {
            $user->update([
                'nombre' => $validated['nombre'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'] ?? null,
                'celular' => $validated['celular'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'foto' => $fotoNueva ?? $user->foto,
            ]);

            $miembro->update([
                'telefono' => $validated['telefono'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'tipo_sangre' => $validated['tipo_sangre'] ?? null,
                'numero_referencia' => $validated['numero_referencia'] ?? null,
                'foto' => $fotoNueva ?? $miembro->foto,
                'contacto_emergencia' => $validated['contacto_emergencia'] ?? null,
                'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
                'estado_civil' => $validated['estado_civil'] ?? null,
                'conyuge' => $validated['conyuge'] ?? null,
                'hijos' => $validated['hijos'] ?? 0,
                'profesion' => $validated['profesion'] ?? null,
            ]);
        });

        if ($fotoNueva && $fotoAnterior) {
            Storage::disk('public')->delete($fotoAnterior);
        }

        return redirect()->route('miembros.index')->with('success', 'Miembro actualizado correctamente.');
    }

    public function cambiarEstado(User $user): RedirectResponse
    {
        $this->abortIfNotMiembro($user);
        abort_if($user->is(auth()->user()), 403, 'No puedes cambiar tu propio estado.');

        $nuevoEstado = $user->estado === 'activo' ? 'inactivo' : 'activo';

        DB::transaction(function () use ($user, $nuevoEstado): void {
            $user->update(['estado' => $nuevoEstado]);
            $user->miembro()->update(['estado_miembro' => $nuevoEstado]);
        });

        return back()->with('success', 'Estado del miembro actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->abortIfNotMiembro($user);
        abort_if($user->is(auth()->user()), 403, 'No puedes eliminar tu propia cuenta.');

        $fotos = array_filter([$user->foto, $user->miembro?->foto]);
        $user->delete();

        foreach (array_unique($fotos) as $foto) {
            Storage::disk('public')->delete($foto);
        }

        return redirect()->route('miembros.index')->with('success', 'Miembro eliminado correctamente.');
    }

    public function verificar(User $user)
    {
        $this->abortIfNotMiembro($user);

        DB::transaction(function () use ($user): void {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);
            $miembro = $user->miembro()->lockForUpdate()->firstOrFail();

            $user->update(['estado' => 'activo']);
            $miembro->update(['estado_miembro' => 'activo']);
        });

        return back()->with('success', 'Miembro verificado y activado exitosamente.');
    }

    private function abortIfNotMiembro(User $user): void
    {
        abort_unless($user->tieneRol('Miembro'), 404);
    }
}
