<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Article;
use App\Http\Requests\ArticleStoreRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
  
  /**
   * Get a list of diaries
   * 
   * @return \Illuminate\Http\Response
   */
  public function get($publish = FALSE)
  {
    if ($publish)
    {
      return new DataCollection(Article::flagged('isPublish')->get());
    }
    return new DataCollection(Article::get());
  }

  /**
   * Get a single article
   * 
   * @param Article $article
   * @return \Illuminate\Http\Response
   */
  public function find(Article $article)
  {
    $article = Article::find($article->id);
    return response()->json(['article' => $article]);
  }

  /**
   * Store a newly created article
   *
   * @param  \Illuminate\Http\ArticleStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(ArticleStoreRequest $request)
  { 
    $article = Article::create([
      'date' =>$request->input('date'),
      'title' => $request->input('title'),
      'text' => $request->input('text'),
      'link' => $request->input('link'),
      'linkText' => $request->input('linkText'),
    ]);
    $this->handleFlag($article, 'isPublish', $request->input('publish'));
    return response()->json(['articleId' => $article->id]);
  }

  /**
   * Update a article for a given article
   *
   * @param Article $article
   * @param  \Illuminate\Http\ArticleStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update(Article $article, ArticleStoreRequest $request)
  {
    $article = Article::findOrFail($article->id);
    $article->date = $request->input('date');
    $article->title = $request->input('title');
    $article->text = $request->input('text');
    $article->link = $request->input('link');
    $article->linkText = $request->input('linkText');
    $article->save();
    $this->handleFlag($article, 'isPublish', $request->input('publish'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given article
   *
   * @param  Article $article
   * @return \Illuminate\Http\Response
   */
  public function toggle(Article $article)
  {
    if ($article->hasFlag('isPublish'))
    {
      $article->unflag('isPublish');
    }
    else
    {
      $article->flag('isPublish');
    } 
    return response()->json($article->hasFlag('isPublish'));
  }


  /**
   * Remove a article
   *
   * @param  Article $article
   * @return \Illuminate\Http\Response
   */
  public function destroy(Article $article)
  {
    $article->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Handle flags of a article
   *
   * @param Article $article
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Article $article, $flag, $value)
  {
    if ($value == 1)
    {
      $article->flag($flag);
    }
    else
    {
      $article->unflag($flag);
    }
    return $article->hasFlag($flag);
  }
}

