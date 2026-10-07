<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\State;

class StateController extends Controller
{
  public function get()
  {
    return new DataCollection(State::orderBy('title')->get());
  }
}
