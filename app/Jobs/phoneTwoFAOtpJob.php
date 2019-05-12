<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Nexmo\Laravel\Facade\Nexmo;

class phoneTwoFAOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $name;
    public $phone;
    public $code;

    public function __construct($name,$phone,$code)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Nexmo::message()->send([
            'to'   => '+88'.$this->phone,
            'from' => 'Dictator',
            'text' => 'Hi '.$this->name.', Your verification code is '.$this->code.' Thank you for joining. '
        ]);
    }
}
