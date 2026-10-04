<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        \Illuminate\Support\Facades\Artisan::call('db:seed');
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
