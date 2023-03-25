<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
  
  /**
   * Get a list of states
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(State::orderBy('title', 'ASC')->get());
  }
}

