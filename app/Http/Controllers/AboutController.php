<?php
namespace App\Http\Controllers;
use App\Actions\Site\GetDiary;
use App\Actions\Site\GetTeam;

class AboutController extends Controller
{
  public function team(GetTeam $action)
  {
    return view('pages.about.team', $action->execute());
  }

  public function diary(GetDiary $action)
  {
    return view('pages.about.diary', $action->execute());
  }
}
