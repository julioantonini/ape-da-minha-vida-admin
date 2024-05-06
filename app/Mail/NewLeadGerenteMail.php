<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeadGerenteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $gerenteName;
    public $userName;

    public function __construct($gerenteName, $userName)
    {
      $this->gerenteName = $gerenteName;
      $this->userName = $userName;
    }


    public function build()
    {
        return $this->subject('Chegou um lead para sua equipe - '.config('app.app_title'))->view('mail.new-lead-gerente')->with(['gerenteName' => $this->gerenteName, 'userName' => $this->userName]);
    }
}
