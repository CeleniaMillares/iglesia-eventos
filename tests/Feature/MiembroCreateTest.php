<?php

namespace Tests\Feature;

use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MiembroCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretaria_can_create_an_active_member_with_profile_and_role(): void
    {
        Storage::fake('public');

        $secretaria = $this->userWithRole('Secretaría');
        $response = $this->actingAs($secretaria)->post(route('miembros.store'), [
            'nombre' => 'Nuevo Miembro',
            'email' => 'nuevo@example.com',
            'password' => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
            'telefono' => '22223333',
            'celular' => '77778888',
            'direccion' => 'Av. Central 123',
            'fecha_nacimiento' => '1990-05-10',
            'tipo_sangre' => 'O+',
            'numero_referencia' => 'REF-2026-01',
            'foto' => UploadedFile::fake()->image('miembro.png'),
            'contacto_emergencia' => 'Contacto de emergencia',
            'telefono_emergencia' => '33334444',
            'estado_civil' => 'casado',
            'conyuge' => 'Cónyuge',
            'hijos' => 2,
            'profesion' => 'Docente',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('miembros.index'));

        $user = User::where('email', 'nuevo@example.com')->firstOrFail();
        $miembro = Miembro::where('user_id', $user->id)->firstOrFail();

        $this->assertSame('activo', $user->estado);
        $this->assertSame('activo', $miembro->estado_miembro);
        $this->assertTrue($user->tieneRol('Miembro'));
        $this->assertTrue(Hash::check('StrongPass123', $user->password));
        $this->assertSame('O+', $miembro->tipo_sangre);
        $this->assertSame(2, $miembro->hijos);
        $this->assertSame($user->foto, $miembro->foto);
        Storage::disk('public')->assertExists($user->foto);
    }

    public function test_user_without_secretariat_or_administrator_role_cannot_create_a_member(): void
    {
        $member = $this->userWithRole('Miembro');
        Miembro::factory()->create(['user_id' => $member->id]);

        $this->actingAs($member)->get(route('miembros.create'))->assertForbidden();
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nombre' => $roleName]);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }
}
