<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeadInfoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    public function __construct($lead)
    {
      $this->lead = $lead;
    }

    public function build()
    {
        return $this->subject('Novo lead - '.config('app.app_title'))->view('mail.new-lead-info')->with(['lead' => $this->lead]);
    }
}
