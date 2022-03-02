<?php

namespace App\Observers;

use App\Models\Reading;
use App\Models\Notification;

class ReadingObserver
{
    /**
     * Handle the reading "created" event.
     *
     * @param  \App\Reading  $reading
     * @return void
     */
    public function created(Reading $reading)
    {
        //
        $notification = new Notification();
        $notification->title = $reading->meter_id;

        $notification->save();


    }

    /**
     * Handle the reading "updated" event.
     *
     * @param  \App\Reading  $reading
     * @return void
     */
    public function updated(Reading $reading)
    {
        //
    }

    /**
     * Handle the reading "deleted" event.
     *
     * @param  \App\Reading  $reading
     * @return void
     */
    public function deleted(Reading $reading)
    {
        //
    }

    /**
     * Handle the reading "restored" event.
     *
     * @param  \App\Reading  $reading
     * @return void
     */
    public function restored(Reading $reading)
    {
        //
    }

    /**
     * Handle the reading "force deleted" event.
     *
     * @param  \App\Reading  $reading
     * @return void
     */
    public function forceDeleted(Reading $reading)
    {
        //
    }
}
