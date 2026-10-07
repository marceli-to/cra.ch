<?php
namespace App\Actions\Site;
use App\Models\Contact;

class GetContact
{
  public function execute(): array
  {
    return [
      'contact' => Contact::with('publishedImages')->first(),
    ];
  }
}
