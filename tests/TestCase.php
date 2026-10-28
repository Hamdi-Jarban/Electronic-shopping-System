<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // HTTP tests do not carry browser CSRF cookies/tokens; keep session/auth middleware active.
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }
}
