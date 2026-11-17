<?php

namespace Tests\Feature;

use App\Models\Miembro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'nombre' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->nombre);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_profile_and_member_details_and_photo_can_be_updated(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $miembro = Miembro::factory()->create(['user_id' => $user->id]);
        $photo = UploadedFile::fake()->image('perfil.png');

        $this->actingAs($user)
            ->patch('/profile', [
                'nombre' => $user->nombre,
                'email' => $user->email,
                'telefono' => '22223333',
                'celular' => '77778888',
                'direccion' => 'Calle de prueba',
                'foto' => $photo,
                'tipo_sangre' => 'O+',
                'numero_referencia' => 'REF-001',
                'contacto_emergencia' => 'Contacto de emergencia',
                'telefono_emergencia' => '33334444',
                'estado_civil' => 'casado',
                'conyuge' => 'Cónyuge de prueba',
                'hijos' => 2,
                'profesion' => 'Docente',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();
        $miembro->refresh();

        $this->assertSame('22223333', $user->telefono);
        $this->assertSame('77778888', $user->celular);
        $this->assertSame('Calle de prueba', $user->direccion);
        $this->assertNotNull($user->foto);
        $this->assertSame($user->foto, $miembro->foto);
        $this->assertSame('O+', $miembro->tipo_sangre);
        $this->assertSame('REF-001', $miembro->numero_referencia);
        $this->assertSame(2, $miembro->hijos);
        Storage::disk('public')->assertExists($user->foto);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'nombre' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
