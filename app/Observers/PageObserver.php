<?php
namespace App\Observers;
use App\Models\Page;
use App\Services\Media;

class PageObserver {

  /**
   * Handle the Page "created" event.
   *
   * @param  \App\Models\Page  $page
   * @return void
   */
  public function created(Page $page)
  {
    //
  }

  /**
   * Handle the Page "updated" event.
   *
   * @param  \App\Models\Page  $page
   * @return void
   */
  public function updated(Page $page)
  {
    //
  }

  /**
   * Handle the Page "deleting" event.
   *
   * @param  \App\Models\Page  $page
   * @return void
   */
  public function deleting(Page $page)
  {
    $page->images()->delete();
    $page->articles()->delete();
  }

  /**
   * Handle the Page "deleted" event.
   *
   * @param  \App\Models\Page  $page
   * @return void
   */
  public function deleted(Page $page)
  {
    //
  }

  /**
   * Handle the Page "restored" event.
   *
   * @param  \App\Models\Page  $page
   * @return void
   */
  public function restored(Page $page)
  {
    //
  }

  /**
   * Handle the Page "force deleted" event.
   *
   * @param  \App\Models\Page  $page
   * @return void
   */
  public function forceDeleted(Page $page)
  {
    //
  }
}
