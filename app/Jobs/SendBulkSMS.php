<?php

namespace App\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkSMS extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;

    protected $url;

    /**
     * Create a new job instance.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $var = json_decode(file_get_contents($this->url), true);
    }
}
