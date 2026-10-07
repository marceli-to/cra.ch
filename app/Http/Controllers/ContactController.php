<?php
namespace App\Http\Controllers;
use App\Actions\Site\GetContact;

class ContactController extends Controller
{
  public function index(GetContact $action)
  {
    return view('pages.contact.index', $action->execute());
  }
}
