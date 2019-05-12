<?php

namespace App\Jobs;

use App\Mail\verificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class mailVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $name;
    public $email;
    public $link;
    public function __construct($name,$email,$link)
    {
        $this->name = $name;
        $this->email = $email;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new verificationMail($this->name,$this->link);
        Mail::to($this->email)->send($email);
    }
}
