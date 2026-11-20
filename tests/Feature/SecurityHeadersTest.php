<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Cookie;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_responses_include_content_security_and_frame_options_headers(): void
    {
        $response = $this->get('/login');

        $response->assertOk()
            ->assertHeader(
                'Content-Security-Policy',
                "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; style-src 'self' https://fonts.bunny.net; font-src 'self' https://fonts.bunny.net"
            )
            ->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_session_cookie_is_http_only(): void
    {
        $response = $this->get('/login');

        $sessionCookie = collect($response->baseResponse->headers->getCookies())
            ->first(fn (Cookie $cookie) => $cookie->getName() === config('session.cookie'));

        $this->assertNotNull($sessionCookie);
        $this->assertTrue($sessionCookie->isHttpOnly());
    }
}
