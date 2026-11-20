<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use LogicException;
use Tests\TestCase;

class UserSeederSecurityTest extends TestCase
{
    public function test_development_user_seeder_is_blocked_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Development users cannot be seeded in production.');

        (new UserSeeder())->run();
    }
}
