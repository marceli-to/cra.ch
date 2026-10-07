<?php

namespace Tests\Feature\Api;

use App\Models\About;
use App\Models\Article;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Diary;
use App\Models\Resume;
use App\Models\Service;
use App\Models\TeamMember;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The content modules: store, update, toggle, delete, validation. The
 * response bodies are what the Vue 2 admin reads.
 */
class ContentApiTest extends ApiTestCase
{
    /**
     * route, model, store payload, update payload, id key in the store
     * response, required field, whether it has a publish flag / images / delete
     */
    public static function modules(): array
    {
        return [
            'about' => ['about', About::class,
                ['description' => 'D', 'former_employees' => 'F', 'cooperation' => 'C', 'membership' => 'M'],
                ['description' => 'D2', 'former_employees' => 'F2', 'cooperation' => 'C2', 'membership' => 'M2'],
                'aboutId', 'description', true, true, true],
            'contact' => ['contact', Contact::class,
                ['address' => 'A', 'description' => 'D', 'maps_uri' => 'https://maps', 'imprint' => 'I'],
                ['address' => 'A2', 'description' => 'D2', 'maps_uri' => 'https://maps2', 'imprint' => 'I2'],
                'contactId', 'address', true, true, true],
            'service' => ['service', Service::class,
                ['column_one' => 'One', 'column_two' => 'Two'],
                ['column_one' => 'One2', 'column_two' => 'Two2'],
                'serviceId', null, true, true, true],
            'diary' => ['diary', Diary::class,
                ['description' => 'D'],
                ['description' => 'D2'],
                'diaryId', 'description', true, true, true],
            'article' => ['article', Article::class,
                ['date' => '2026', 'title' => 'T', 'text' => '<p>X</p>', 'link' => 'https://x', 'linkText' => 'L'],
                ['date' => '2027', 'title' => 'T2', 'text' => '<p>Y</p>', 'link' => 'https://y', 'linkText' => 'L2'],
                'articleId', 'text', true, false, true],
            'resume' => ['resume', Resume::class,
                ['team_member_id' => 1, 'periode' => '2020', 'description' => 'D'],
                ['periode' => '2021', 'description' => 'D2'],
                'resumeId', 'periode', true, false, true],
            'category' => ['category', Category::class,
                ['title' => 'Wohnen und Bauen'],
                ['title' => 'Umbau'],
                'categoryId', 'title', false, false, true],
        ];
    }

    #[DataProvider('modules')]
    public function testStore(string $route, string $model, array $store, array $update, string $idKey, ?string $required, bool $flag, bool $images, bool $delete)
    {
        $image = $images ? $this->image() : null;
        $payload = $store + ['publish' => 1, 'images' => $image ? [['id' => $image->id]] : []];

        $id = $this->postJson("/api/{$route}", $payload)->assertOk()->assertJsonStructure([$idKey])->json($idKey);

        $record = $model::findOrFail($id);
        foreach ($store as $field => $value) {
            $this->assertEquals($value, $record->$field, $field);
        }
        if ($route === 'category') {
            $this->assertSame('wohnen-und-bauen', $record->slug);
        }
        if ($flag) {
            $this->assertTrue($record->hasFlag('isPublish'));
        }
        if ($image) {
            $this->assertSame($model, $image->fresh()->imageable_type);
            $this->assertEquals($id, $image->fresh()->imageable_id);
        }
    }

    #[DataProvider('modules')]
    public function testUpdate(string $route, string $model, array $store, array $update, string $idKey, ?string $required, bool $flag, bool $images, bool $delete)
    {
        $id = $this->postJson("/api/{$route}", $store + ['publish' => 1, 'images' => []])->json($idKey);
        $image = $images ? $this->image() : null;

        $this->putJson("/api/{$route}/{$id}", $update + ['publish' => 0, 'images' => $image ? [['id' => $image->id]] : []])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $record = $model::findOrFail($id);
        foreach ($update as $field => $value) {
            $this->assertEquals($value, $record->$field, $field);
        }
        if ($route === 'resume') {
            $this->assertEquals(1, $record->team_member_id);
        }
        if ($route === 'category') {
            $this->assertSame('umbau', $record->slug);
        }
        if ($flag) {
            $this->assertFalse($record->hasFlag('isPublish'));
        }
        if ($image) {
            $this->assertEquals($id, $image->fresh()->imageable_id);
        }
    }

    #[DataProvider('modules')]
    public function testToggle(string $route, string $model, array $store, array $update, string $idKey, ?string $required, bool $flag, bool $images, bool $delete)
    {
        if (!$flag) {
            $this->get("/api/{$route}/state/1")->assertStatus(404);
            return;
        }

        $id = $this->postJson("/api/{$route}", $store + ['publish' => 0, 'images' => []])->json($idKey);

        $this->getJson("/api/{$route}/state/{$id}")->assertOk()->assertContent('true');
        $this->assertTrue($model::find($id)->hasFlag('isPublish'));
        $this->getJson("/api/{$route}/state/{$id}")->assertOk()->assertContent('false');
        $this->assertFalse($model::find($id)->hasFlag('isPublish'));
    }

    #[DataProvider('modules')]
    public function testDestroy(string $route, string $model, array $store, array $update, string $idKey, ?string $required, bool $flag, bool $images, bool $delete)
    {
        $id = $this->postJson("/api/{$route}", $store + ['publish' => 1, 'images' => []])->json($idKey);

        $this->deleteJson("/api/{$route}/{$id}")->assertOk()->assertExactJson(['successfully deleted']);
        $this->assertNull($model::find($id));
    }

    #[DataProvider('modules')]
    public function testValidation(string $route, string $model, array $store, array $update, string $idKey, ?string $required, bool $flag, bool $images, bool $delete)
    {
        $field = $required ?? 'column_one';
        $payload = array_merge($store, [$field => '', 'publish' => 1, 'images' => []]);
        if ($route === 'service') {
            $payload['column_two'] = '';
        }

        $this->postJson("/api/{$route}", $payload)
            ->assertStatus(422)
            ->assertJsonPath("errors.{$field}.0.field", $field);
        $this->assertSame(0, $model::count());
    }

    public function testTeamMembers()
    {
        $member = $this->postJson('/api/team-member', ['slug' => 'max', 'title' => 'Max', 'publish' => 1])
            ->assertOk()
            ->assertJsonPath('slug', 'max')
            ->assertJsonPath('title', 'Max')
            ->json();
        $this->assertTrue(TeamMember::find($member['id'])->hasFlag('isPublish'));

        $this->putJson("/api/team-member/{$member['id']}", ['slug' => 'other', 'title' => 'Max M.', 'publish' => 0])
            ->assertOk()
            ->assertExactJson(['successfully updated']);
        $record = TeamMember::find($member['id']);
        $this->assertSame('max', $record->slug);
        $this->assertSame('Max M.', $record->title);
        $this->assertFalse($record->hasFlag('isPublish'));

        $this->getJson("/api/team-member/state/{$member['id']}")->assertContent('true');
        $this->getJson("/api/team-member/{$member['id']}")->assertJsonPath('teamMember.title', 'Max M.')->assertJsonPath('teamMember.publish', 1);

        $this->postJson('/api/team-member', ['slug' => 'x', 'title' => ''])->assertStatus(422);
    }

    public function testFindAndListShapes()
    {
        $about = $this->postJson('/api/about', ['description' => 'D', 'publish' => 1, 'images' => [['id' => $this->image(['caption' => 'C'])->id]]])->json('aboutId');

        $this->getJson('/api/about')->assertOk()->assertJsonPath('data.0.id', $about)->assertJsonPath('data.0.publish', 1)->assertJsonPath('data.0.images.0.caption', 'C');
        $this->getJson("/api/about/{$about}")->assertOk()->assertJsonPath('about.id', $about)->assertJsonPath('about.images.0.caption', 'C');
        $this->getJson('/api/about/images')->assertOk()->assertJsonPath('data.0.caption', 'C');
        $this->getJson('/api/states')->assertOk()->assertExactJson(['data' => []]);
    }

    public function testResumeOrderAndTeamMemberList()
    {
        $a = $this->postJson('/api/resume', ['team_member_id' => 1, 'periode' => 'A', 'description' => 'A', 'publish' => 1])->json('resumeId');
        $b = $this->postJson('/api/resume', ['team_member_id' => 1, 'periode' => 'B', 'description' => 'B', 'publish' => 1])->json('resumeId');

        $this->postJson('/api/resume/order', ['resumes' => [['id' => $b, 'order' => 0], ['id' => $a, 'order' => 1]]])
            ->assertOk()->assertExactJson(['successfully updated']);

        $this->getJson('/api/resumes/1')->assertOk()->assertJsonPath('data.0.id', $b)->assertJsonPath('data.1.id', $a);
        $this->getJson('/api/resumes')->assertOk()->assertJsonPath('data.0.id', $b);
    }

    public function testCategoryOrder()
    {
        $a = $this->postJson('/api/category', ['title' => 'A'])->json('categoryId');
        $b = $this->postJson('/api/category', ['title' => 'B'])->json('categoryId');

        $this->postJson('/api/categories/order', ['categories' => [['id' => $b, 'order' => 0], ['id' => $a, 'order' => 1]]])
            ->assertOk()->assertExactJson(['successfully updated']);

        $this->getJson('/api/categories')->assertJsonPath('data.0.id', $b)->assertJsonPath('data.1.id', $a);
        $this->getJson("/api/category/{$a}")->assertJsonPath('title', 'A');
    }

    public function testArticleListOnlyPublished()
    {
        $this->postJson('/api/article', ['text' => 'on', 'publish' => 1]);
        $this->postJson('/api/article', ['text' => 'off', 'publish' => 0]);

        $this->getJson('/api/articles')->assertJsonCount(2, 'data');
        $this->getJson('/api/articles/1')->assertJsonCount(1, 'data')->assertJsonPath('data.0.text', 'on');
    }
}
