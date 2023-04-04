<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
  protected $viewPath = 'pages.contact.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the homepage
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    $contact = Contact::with('publishedImages')->first();
    return view($this->viewPath . 'index', ['contact' => $contact]);
  }
}
