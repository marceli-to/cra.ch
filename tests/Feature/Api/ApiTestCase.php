<?php

namespace Tests\Feature\Api;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The admin API on in-memory SQLite, logged in as an admin
 */
abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = new User();
        $admin->forceFill(['firstname' => 'A', 'name' => 'Admin', 'email' => 'admin@example.invalid', 'password' => 'x', 'role' => 'admin'])->save();
        $this->actingAs($admin, 'sanctum');
    }

    protected function image(array $attributes = []): Image
    {
        // No file: width/height stay empty
        return Image::create(array_merge([
            'uuid' => (string) \Str::uuid(),
            'name' => 'missing_' . uniqid() . '.jpg',
            'original_name' => 'x.jpg',
            'extension' => 'jpg',
            'size' => 1,
        ], $attributes));
    }
}
