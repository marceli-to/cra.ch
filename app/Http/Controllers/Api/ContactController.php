<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\ContactStoreRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Contact::with('images')->get());
  }
  
  /**
   * Get the about images
   *
   * @return \Illuminate\Http\Response
   */
  public function getImages()
  {
    $about = Contact::with('images')->get();
    $images = [];
    foreach ($about as $a)
    {
      foreach ($a->images as $image)
      {
        $images[] = $image;
      }
    }
    return response()->json(['data' => $images]);
  }

  /**
   * Display the specified resource.
   *
   * @param  Contact $contact
   * @return \Illuminate\Http\Response
   */
  public function find(Contact $contact)
  {
    return response()->json(['contact' => Contact::with('images')->find($contact->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\ContactStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ContactStoreRequest $request)
  {
    $contact = Contact::create([
      'address' => $request->input('address'),
      'description' => $request->input('description'),
      'maps_uri' => $request->input('maps_uri'),
      'imprint' => $request->input('imprint'),
    ]);
    $this->handleFlag($contact, 'isPublish', $request->input('publish'));
    $this->handleImages($contact, $request->input('images'));
    return response()->json(['contactId' => $contact->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\ContactStoreRequest  $request
   * @param  Contact $contact
   * @return \Illuminate\Http\Response
   */
  public function update(ContactStoreRequest $request, Contact $contact)
  {
    $contact = Contact::findOrFail($contact->id);
    $contact->address = $request->input('address');
    $contact->description = $request->input('description');
    $contact->maps_uri = $request->input('maps_uri');
    $contact->imprint = $request->input('imprint');
    $contact->save();
    $this->handleFlag($contact, 'isPublish', $request->input('publish'));
    $this->handleImages($contact, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given contact
   *
   * @param  Contact $contact
   * @return \Illuminate\Http\Response
   */
  public function toggle(Contact $contact)
  {
    if ($contact->hasFlag('isPublish'))
    {
      $contact->unflag('isPublish');
    }
    else
    {
      $contact->flag('isPublish');
    } 
    return response()->json($contact->hasFlag('isPublish'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  Contact $contact
   * @return \Illuminate\Http\Response
   */
  public function destroy(Contact $contact)
  {
    $contact->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Handle flags of a contact
   *
   * @param Contact $contact
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Contact $contact, $flag, $value)
  {
    if ($value == 1)
    {
      $contact->flag($flag);
    }
    else
    {
      $contact->unflag($flag);
    }
    return $contact->hasFlag($flag);
  }
  /**
   * Handle associated images
   *
   * @param Contact $contact
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Contact $contact, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $contact->id;
      $i->imageable_type = Contact::class;
      $i->save();
    }
  }
}