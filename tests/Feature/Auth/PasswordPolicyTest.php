<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Tests\TestCase;

class PasswordPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_requires_mixed_case_and_numbers(): void
    {
        $this->post('/register', [
            'nombre' => 'Weak Password',
            'email' => 'weak@example.test',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ])->assertSessionHasErrors('password');
    }

    public function test_password_update_requires_mixed_case_and_numbers(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'password1',
                'password_confirmation' => 'password1',
            ])
            ->assertSessionHasErrorsIn('updatePassword', 'password');
    }

    public function test_password_reset_requires_mixed_case_and_numbers(): void
    {
        $user = User::factory()->create();
        $token = PasswordBroker::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ])->assertSessionHasErrors('password');
    }
}
