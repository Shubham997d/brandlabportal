<?php

namespace App\Chat\Observers;

use App\Chat\Models\DirectMessage;
use App\Chat\Events\DirectMessageCreated;

class DirectMessageObserver
{
    /**
     * Handle the direct message "created" event.
     *
     * @param  \App\DirectMessage $directMessage
     * @return void
     */
    public function created(DirectMessage $directMessage)
    {   
        event(new DirectMessageCreated($directMessage));
    }

    /**
     * Handle the direct message "updated" event.
     *
     * @param  \App\DirectMessage $directMessage
     * @return void
     */
    public function updated(DirectMessage $directMessage)
    {
    }

    /**
     * Handle the direct message "deleted" event.
     *
     * @param  \App\DirectMessage $directMessage
     * @return void
     */
    public function deleted(DirectMessage $directMessage)
    {
    }

    /**
     * Handle the direct message "restored" event.
     *
     * @param  \App\DirectMessage $directMessage
     * @return void
     */
    public function restored(DirectMessage $directMessage)
    {
    }

    /**
     * Handle the direct message "force deleted" event.
     *
     * @param  \App\DirectMessage $directMessage
     * @return void
     */
    public function forceDeleted(DirectMessage $directMessage)
    {
    }
}
