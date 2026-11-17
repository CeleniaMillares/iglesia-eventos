<?php

namespace Tests\Feature;

use Tests\TestCase;

class SessionConfigurationTest extends TestCase
{
    public function test_session_lifetime_is_thirty_minutes(): void
    {
        $this->assertSame(30, config('session.lifetime'));
    }
}
