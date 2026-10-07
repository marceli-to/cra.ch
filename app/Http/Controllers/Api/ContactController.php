<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\DeleteAction;
use App\Actions\Content\SaveAction;
use App\Actions\Content\ToggleFlagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\DataCollection;
use App\Models\Contact;

class ContactController extends Controller
{
  public function get()
  {
    return new DataCollection(Contact::with('images', 'flags')->get());
  }

  /**
   * All contact images (grid image picker)
   */
  public function getImages()
  {
    return response()->json(['data' => Contact::with('images')->get()->flatMap->images->values()]);
  }

  public function find(Contact $contact)
  {
    return response()->json(['contact' => $contact->load('images')]);
  }

  public function store(ContactRequest $request)
  {
    $contact = (new SaveAction)->execute(new Contact, $request->fields(), ['isPublish' => $request->boolean('publish')], $request->input('images', []));
    return response()->json(['contactId' => $contact->id]);
  }

  public function update(ContactRequest $request, Contact $contact)
  {
    (new SaveAction)->execute($contact, $request->fields(), ['isPublish' => $request->boolean('publish')], $request->input('images', []));
    return response()->json('successfully updated');
  }

  public function toggle(Contact $contact)
  {
    return response()->json((new ToggleFlagAction)->execute($contact));
  }

  public function destroy(Contact $contact)
  {
    (new DeleteAction)->execute($contact);
    return response()->json('successfully deleted');
  }
}
