<?php

namespace App\Jobs;

use App\Mail\emailOtpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class emailTwoFAOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $name;
    public $email;
    public $code;

    public function __construct($name,$email,$code)
    {
        $this->name = $name;
        $this->email = $email;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email  = new emailOtpMail($this->name,$this->code);
        Mail::to($this->email)->send($email);
    }
}
