<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
  /**
   * Name of the logged-in user (admin header)
   */
  public function find()
  {
    return response()->json(['firstname' => auth()->user()->firstname, 'name' => auth()->user()->name]);
  }
}
