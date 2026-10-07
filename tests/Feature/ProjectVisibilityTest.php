<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private function project(string $slug, bool $published): Project
    {
        $project = Project::create(['title' => ucfirst($slug), 'slug' => $slug, 'order' => 1]);

        if ($published) {
            $project->flag('isPublish');
        }

        return $project;
    }

    private function user(string $role): User
    {
        $user = new User();
        $user->forceFill(['firstname' => 'T', 'name' => 'U', 'email' => "{$role}@example.invalid", 'password' => 'x', 'role' => $role])->save();

        return $user;
    }

    public function testPublishedProjectsAreVisible()
    {
        $this->project('offen', true);

        $this->get('/projekt/offen')->assertOk()->assertSee('Offen');
    }

    public function testUnpublishedProjectsAre404ForVisitors()
    {
        $this->project('entwurf', false);

        $this->get('/projekt/entwurf')->assertNotFound();
    }

    public function testAdminsCanPreviewUnpublishedProjects()
    {
        $this->project('entwurf', false);

        $this->actingAs($this->user('admin'))->get('/projekt/entwurf')->assertOk()->assertSee('Entwurf');
    }

    public function testOtherLoggedInUsersCannot()
    {
        $this->project('entwurf', false);

        $this->actingAs($this->user('editor'))->get('/projekt/entwurf')->assertNotFound();
    }
}
