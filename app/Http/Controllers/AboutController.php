<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Diary;
use App\Models\About;
use App\Models\Resume;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class AboutController extends BaseController
{
  protected $viewPath = 'pages.about.';

  public function __construct()
  {
    parent::__construct();
  }
  
  /**
   * Show the team page
   *
   * @return \Illuminate\Http\Response
   */

   public function team()
   {
     $about = About::first();
     $teamMembers = TeamMember::with('resumes')->flagged('isPublish')->orderBy('id')->get();
     return view($this->viewPath . 'team', ['about' => $about, 'teamMembers' => $teamMembers]);
   }

  /**
   * Show the diary page
   *
   * @return \Illuminate\Http\Response
   */

  public function diary()
  {
    $diary = Diary::with('grids.gridItems.image')->flagged('isPublish')->first();
    return view($this->viewPath . 'diary', ['diary' => $diary]);
  }
}
