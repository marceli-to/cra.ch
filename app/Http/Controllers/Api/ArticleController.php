<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\DeleteAction;
use App\Actions\Content\SaveAction;
use App\Actions\Content\ToggleFlagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\DataCollection;
use App\Models\Article;

class ArticleController extends Controller
{
  /**
   * All articles, or only the published ones
   */
  public function get($publish = false)
  {
    return new DataCollection(Article::with('flags')->when($publish, fn ($query) => $query->flagged('isPublish'))->get());
  }

  public function find(Article $article)
  {
    return response()->json(['article' => $article]);
  }

  public function store(ArticleRequest $request)
  {
    $article = (new SaveAction)->execute(new Article, $request->fields(), ['isPublish' => $request->boolean('publish')]);
    return response()->json(['articleId' => $article->id]);
  }

  public function update(ArticleRequest $request, Article $article)
  {
    (new SaveAction)->execute($article, $request->fields(), ['isPublish' => $request->boolean('publish')]);
    return response()->json('successfully updated');
  }

  public function toggle(Article $article)
  {
    return response()->json((new ToggleFlagAction)->execute($article));
  }

  public function destroy(Article $article)
  {
    (new DeleteAction)->execute($article);
    return response()->json('successfully deleted');
  }
}
