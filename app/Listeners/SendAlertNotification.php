<?php

namespace App\Listeners;

use App\Events\AlertExpiration;
use App\Mail\SendMailAlert;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAlertNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AlertExpiration  $event
     * @return void
     */
    public function handle(AlertExpiration $event)
    {
        foreach ($event->domains as $domain) {
            $expirationDate = Carbon::createFromFormat('Y-m-d H:i:s', $domain['expiration_date']);
            $today = Carbon::now();
            if ($expirationDate->diff($today)->days < 30) {
                $domain['expiration_days'] = $expirationDate->diff($today)->days;
                Mail::to("john.doe@lorem.com")
                    ->send(new SendMailAlert($domain));
            }
        }
    }
}
