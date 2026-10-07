<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\Grid;
use App\Models\GridItem;
use App\Models\Home;
use App\Models\Project;

/**
 * Grid rows (grids) and their slots (grid_items) on home, projects and the
 * diary
 */
class GridApiTest extends ApiTestCase
{
    private function project(): Project
    {
        return Project::create(['title' => 'P', 'slug' => 'p']);
    }

    public function testStoreRowWithEmptyItems()
    {
        $project = $this->project();

        $this->postJson('/api/grid', ['layout' => '1-1_1', 'items' => 3, 'model' => ['id' => $project->id, 'name' => 'Project']])
            ->assertOk();

        $grid = Grid::with('gridItems')->firstOrFail();
        $this->assertSame('1-1_1', $grid->layout);
        $this->assertSame(Project::class, $grid->gridable_type);
        $this->assertEquals($project->id, $grid->gridable_id);
        $this->assertEquals([0, 1, 2], $grid->gridItems->pluck('position')->all());
        $this->assertCount(1, $project->grids);
    }

    public function testOrderRows()
    {
        $a = Grid::create(['layout' => '1', 'gridable_type' => Home::class, 'gridable_id' => 1]);
        $b = Grid::create(['layout' => '1', 'gridable_type' => Home::class, 'gridable_id' => 1]);

        $this->postJson('/api/grid/order', ['items' => [['id' => $b->id, 'order' => 0], ['id' => $a->id, 'order' => 1]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $this->assertEquals(1, $a->fresh()->order);
        $this->assertEquals(0, $b->fresh()->order);
    }

    public function testDestroyRowWithItems()
    {
        $grid = Grid::create(['layout' => '1-1', 'gridable_type' => Home::class, 'gridable_id' => 1]);
        GridItem::create(['grid_id' => $grid->id, 'position' => 0]);
        GridItem::create(['grid_id' => $grid->id, 'position' => 1]);

        $this->deleteJson("/api/grid/{$grid->id}")->assertOk()->assertExactJson(['successfully deleted']);

        $this->assertSame(0, Grid::count());
        $this->assertSame(0, GridItem::count());
    }

    public function testSetAndResetItem()
    {
        $project = $this->project();
        $image = $this->image();
        $grid = Grid::create(['layout' => '1-1', 'gridable_type' => Home::class, 'gridable_id' => 1]);
        $item = GridItem::create(['grid_id' => $grid->id, 'position' => 0]);

        $this->postJson('/api/grid-item', ['id' => $item->id, 'position' => 1, 'image_id' => $image->id, 'project_id' => $project->id, 'page' => null])
            ->assertOk()
            ->assertJsonPath('id', $item->id)
            ->assertJsonPath('image_id', $image->id)
            ->assertJsonPath('isProject', true);

        $item = $item->fresh();
        $this->assertEquals(1, $item->position);
        $this->assertEquals($image->id, $item->image_id);
        $this->assertEquals($project->id, $item->project_id);

        $article = Article::create(['text' => 'T']);
        $this->postJson('/api/grid-item', ['id' => $item->id, 'position' => 1, 'article_id' => $article->id])->assertOk();
        $item = $item->fresh();
        $this->assertEquals($article->id, $item->article_id);
        $this->assertNull($item->image_id);
        $this->assertNull($item->project_id);

        $this->putJson("/api/grid-item/{$item->id}")->assertOk()->assertExactJson(['successfully deleted']);
        $item = $item->fresh();
        $this->assertNull($item->article_id);
        $this->assertEquals(1, $item->position);
    }

    public function testHome()
    {
        $home = new Home();
        $home->id = 1;
        $home->save();
        $grid = Grid::create(['layout' => '1', 'gridable_type' => Home::class, 'gridable_id' => 1]);
        GridItem::create(['grid_id' => $grid->id, 'position' => 0, 'image_id' => $this->image(['caption' => 'C'])->id]);

        $this->getJson('/api/home')->assertOk()
            ->assertJsonPath('home.id', 1)
            ->assertJsonPath('home.grids.0.grid_items.0.image.caption', 'C')
            ->assertJsonPath('home.grids.0.grid_items.0.caption', 'C');
    }
}
