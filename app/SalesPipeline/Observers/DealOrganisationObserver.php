<?php

namespace App\SalesPipeline\Observers;

use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\DealOrganisation;
use Illuminate\Support\Facades\Notification;
use App\SalesPipeline\Notifications\DealColorUpdated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Base\Models\User;
use Carbon\Carbon;


class DealOrganisationObserver
{
    use Notifiable;
    /**
     * Handle the task "created" event.
     *
     * @param  \App\SalesPipeline $salespipeline
     * @return void
     */
    public function created(DealOrganisation $dealOrganisation)
    {
        
    }

    public function updated(DealOrganisation $dealOrganisation)
    {           
        // if($dealOrganisation->wasChanged('deal_color') == true) { //only if color is changed
        //     $dealOwner = $dealOrganisation->owner();
        //     $user = User::where('id',$dealOwner->owner_id)->first();
        //     $user->notify(new DealColorUpdated($dealOrganisation, $user));
        // }
    }
}
