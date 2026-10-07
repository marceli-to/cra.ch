<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Project;
use App\Models\State;

class ProjectApiTest extends ApiTestCase
{
    private function payload(array $data = []): array
    {
        return array_merge([
            'title' => 'Haus am See',
            'text' => '<p>Text</p>',
            'text_services' => 'S',
            'text_info' => 'I',
            'type' => 'Neubau',
            'location' => 'Zürich',
            'periode' => '2020 – 2022',
            'state_id' => null,
            'category_ids' => [],
            'publish' => 1,
            'has_detail_page' => 1,
            'images' => [],
        ], $data);
    }

    public function testStore()
    {
        $category = Category::create(['title' => 'Wohnen', 'slug' => 'wohnen']);
        $state = State::create(['title' => 'Realisiert']);
        $image = $this->image();

        $id = $this->postJson('/api/project', $this->payload([
            'state_id' => $state->id,
            'category_ids' => [$category->id],
            'images' => [['id' => $image->id]],
        ]))->assertOk()->json('projectId');

        $project = Project::findOrFail($id);
        $this->assertSame('haus-am-see', $project->slug);
        $this->assertSame('Zürich', $project->location);
        $this->assertEquals($state->id, $project->state_id);
        $this->assertEquals([$category->id], $project->categories->pluck('id')->all());
        $this->assertTrue($project->hasFlag('isPublish'));
        $this->assertTrue($project->hasFlag('hasDetailPage'));
        $this->assertSame(Project::class, $image->fresh()->imageable_type);
        $this->assertEquals($id, $image->fresh()->imageable_id);
    }

    public function testSlugsStayUnique()
    {
        $this->postJson('/api/project', $this->payload());
        $second = $this->postJson('/api/project', $this->payload())->json('projectId');

        // Suffix: the number of projects incl. deleted ones
        $this->assertSame('haus-am-see-1', Project::find($second)->slug);
    }

    public function testUpdate()
    {
        $a = Category::create(['title' => 'A', 'slug' => 'a']);
        $b = Category::create(['title' => 'B', 'slug' => 'b']);
        $id = $this->postJson('/api/project', $this->payload(['category_ids' => [$a->id]]))->json('projectId');

        $this->putJson("/api/project/{$id}", $this->payload(['location' => 'Bern', 'category_ids' => [$b->id], 'publish' => 0, 'has_detail_page' => 0]))
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $project = Project::find($id);
        $this->assertSame('haus-am-see', $project->slug);
        $this->assertSame('Bern', $project->location);
        $this->assertEquals([$b->id], $project->categories->pluck('id')->all());
        $this->assertFalse($project->hasFlag('isPublish'));
        $this->assertFalse($project->hasFlag('hasDetailPage'));

        $this->putJson("/api/project/{$id}", $this->payload(['title' => 'Haus am Berg']))->assertOk();
        $this->assertSame('haus-am-berg', Project::find($id)->slug);
    }

    public function testValidation()
    {
        $this->postJson('/api/project', $this->payload(['title' => '']))
            ->assertStatus(422)
            ->assertJsonPath('errors.title.0', 'Titel wird benötigt');
        $this->assertSame(0, Project::count());
    }

    public function testCopy()
    {
        $category = Category::create(['title' => 'A', 'slug' => 'a']);
        $id = $this->postJson('/api/project', $this->payload(['category_ids' => [$category->id]]))->json('projectId');

        $name = uniqid() . '_copy-test.jpg';
        $path = storage_path('app/public/uploads/' . $name);
        file_put_contents($path, 'x');
        $image = $this->image(['name' => $name, 'caption' => 'C', 'imageable_type' => Project::class, 'imageable_id' => $id]);
        $copyPath = null;

        try {
            $this->getJson("/api/project/copy/{$id}")->assertOk()->assertExactJson(['successfully copied']);

            $copy = Project::where('id', '!=', $id)->firstOrFail();
            $this->assertSame('Haus am See Kopie', $copy->title);
            $this->assertSame('haus-am-see-kopie', $copy->slug);
            $this->assertFalse($copy->hasFlag('isPublish'));
            $this->assertTrue($copy->hasFlag('hasDetailPage'));
            $this->assertEquals([$category->id], $copy->categories->pluck('id')->all());

            $copied = $copy->images()->firstOrFail();
            $this->assertSame('C', $copied->caption);
            $this->assertNotSame($name, $copied->name);
            $this->assertStringEndsWith('_copy-test.jpg', $copied->name);
            $copyPath = storage_path('app/public/uploads/' . $copied->name);
            $this->assertFileExists($copyPath);
            $this->assertSame($id, $image->fresh()->imageable_id);
        } finally {
            @unlink($path);
            $copyPath && @unlink($copyPath);
        }
    }

    public function testToggleOrderDestroy()
    {
        $a = $this->postJson('/api/project', $this->payload(['title' => 'A', 'publish' => 0]))->json('projectId');
        $b = $this->postJson('/api/project', $this->payload(['title' => 'B']))->json('projectId');

        $this->getJson("/api/project/state/{$a}")->assertOk()->assertContent('true');
        $this->assertTrue(Project::find($a)->hasFlag('isPublish'));

        $this->postJson('/api/projects/order', ['projects' => [['id' => $b, 'order' => 0], ['id' => $a, 'order' => 1]]])
            ->assertOk()->assertExactJson(['successfully updated']);
        $this->getJson('/api/projects')->assertJsonPath('data.0.id', $b)->assertJsonPath('data.1.id', $a);

        $this->deleteJson("/api/project/{$a}")->assertOk()->assertExactJson(['successfully deleted']);
        $this->assertSoftDeleted('projects', ['id' => $a]);
    }

    public function testListsAndFind()
    {
        State::create(['title' => 'Z']);
        State::create(['title' => 'A']);
        $category = Category::create(['title' => 'K', 'slug' => 'k']);
        $on = $this->postJson('/api/project', $this->payload(['title' => 'On', 'category_ids' => [$category->id]]))->json('projectId');
        $this->postJson('/api/project', $this->payload(['title' => 'Off', 'publish' => 0]));

        $this->getJson('/api/projects')->assertOk()->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.publish', 1)
            ->assertJsonPath('data.0.has_detail_page', 1)
            ->assertJsonPath('data.0.category_ids', [$category->id])
            ->assertJsonPath('data.0.abstract', 'Text')
            ->assertJsonPath('data.0.preview', 'Text')
            ->assertJsonPath('data.0.images', []);
        $this->getJson('/api/projects/1')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', $on);

        $this->getJson("/api/project/{$on}")->assertOk()
            ->assertJsonPath('project.id', $on)
            ->assertJsonPath('project.categories.0.id', $category->id)
            ->assertJsonPath('project.grids', [])
            ->assertJsonPath('categories.0.title', 'K')
            ->assertJsonPath('states.0.title', 'A');
    }
}
