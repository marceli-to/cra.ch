<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    // CSRF (incl. the upload exemption) is skipped under PHPUnit; checked
    // over HTTP instead, see .rewrite/06-progress.md (step 3)

    public function testApiAnswersJsonWhenNotLoggedIn()
    {
        $this->get('/api/images')->assertStatus(401)->assertJson(['message' => 'Unauthenticated.']);
    }

    public function testAdminNeedsLogin()
    {
        $this->get('/administration')->assertRedirect('/login');
    }

    public function testAdminNeedsTheAdminRole()
    {
        $user = new User();
        $user->role = 'editor';
        $user->email_verified_at = now();

        $this->actingAs($user, 'sanctum')->get('/administration')->assertForbidden();
    }

    public function testLoggedInAdminsAreSentFromLoginToTheAdmin()
    {
        $user = new User();
        $user->role = 'admin';

        $this->actingAs($user)->get('/login')->assertRedirect('/administration');
    }

    public function testApiIsThrottledAt200PerMinute()
    {
        $this->actingAs(new User(), 'sanctum');

        $this->getJson('/api/states')->assertHeader('X-RateLimit-Limit', 200);
    }
}
