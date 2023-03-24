<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Page;
use App\Models\Image;
use App\Http\Requests\PageStoreRequest;
use Illuminate\Http\Request;

class PageController extends Controller
{
  /**
   * Get a list of pages
   * 
   * @param String $constraint
   * @return \Illuminate\Http\Response
   */
  public function get($constraint = NULL)
  {
    if ($constraint == 'publish')
    {
      return new DataCollection(Page::published()->get());
    }
    return new DataCollection(Page::get());
  }

  /**
   * Get a single page
   * 
   * @param Page $page
   * @return \Illuminate\Http\Response
   */
  public function find(Page $page)
  {
    $page = Page::with('articles.images', 'articles.galleries', 'images')->find($page->id);
    return response()->json($page);
  }

  /**
   * Store a newly created page
   *
   * @param  \Illuminate\Http\PageStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(PageStoreRequest $request)
  {
    $page = Page::create([
      'slug' => \AppHelper::slug($request->input('title')),
      'title' => $request->input('title'),
      'subtitle' => $request->input('text'),
      'text' => $request->input('text'),
      'publish' => $request->input('publish'),
    ]);
    $page->save();
    $this->handleImages($page, $request->input('images'));
    return response()->json(['pageId' => $page->id]);
  }

  /**
   * Update a page for a given page
   *
   * @param Page $page
   * @param  \Illuminate\Http\PageStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update(Page $page, PageStoreRequest $request)
  {
    $page = Page::findOrFail($page->id);
    $page->update($request->all());
    $page->save();
    $this->handleImages($page, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given page
   *
   * @param  Page $page
   * @return \Illuminate\Http\Response
   */
  public function toggle(Page $page)
  {
    $page->publish = $page->publish == 0 ? 1 : 0;
    $page->save();
    return response()->json($page->publish);
  }

  /**
   * Remove a page
   *
   * @param  Page $page
   * @return \Illuminate\Http\Response
   */
  public function destroy(Page $page)
  {
    Page::destroy($page->id);
    return response()->json('successfully deleted');
  }

  /**
   * Handle associated images
   *
   * @param Page $page
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Page $page, $images = NULL)
  {
    if ($images)
    {
      foreach($images as $image)
      {
        $img = Image::findOrFail($image['id']);
        $img->imageable_id = $page->id;
        $img->imageable_type = Page::class;
        $img->save();
      }
    }
  }
}
