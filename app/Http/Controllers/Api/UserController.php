<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function __construct(User $user)
  {
    $this->user = $user;
  }

  /**
   * Get users info by authenticated user
   */
  public function find()
  {
    $user = $this->user->findOrFail(auth()->user()->id);
    return response()->json(['firstname' => $user->firstname, 'name' => $user->name]);
  }
}
