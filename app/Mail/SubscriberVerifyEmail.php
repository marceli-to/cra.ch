<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscriber;

class SubscriberVerifyEmail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(Subscriber $subscriber)
  {
    $this->subscriber = $subscriber;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->from(['address' => \Config::get('client.email.from')])
      ->subject('Anmeldung Newsletter')
      ->with(['subscriber' => $this->subscriber])
      ->markdown('mails.newsletter.verify');
  }
}
