<?php
namespace App\Http\Controllers;
use App\Actions\Site\GetService;

class ServiceController extends Controller
{
  public function index(GetService $action)
  {
    return view('pages.service.index', $action->execute());
  }
}
