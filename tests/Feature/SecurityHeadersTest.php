<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_headers_deny_frames_and_inline_scripts_and_enable_hsts_over_https(): void
    {
        $this->withoutVite();
        $original = app()->environment();
        app()->detectEnvironment(fn () => 'production');
        try {
            $response = $this->get('https://localhost/');
            $response->assertOk()->assertHeader('X-Frame-Options', 'DENY')->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $policy = $response->headers->get('Content-Security-Policy');
            $this->assertStringContainsString("script-src 'self' 'nonce-", $policy);
            $this->assertStringNotContainsString("script-src 'self' 'unsafe-inline'", $policy);
            $this->assertStringContainsString("object-src 'none'", $policy);
            $this->assertStringContainsString("connect-src 'self'", $policy);
        } finally {
            app()->detectEnvironment(fn () => $original);
        }
    }
}
