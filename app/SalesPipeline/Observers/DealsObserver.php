<?php

namespace App\SalesPipeline\Observers;

use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\DealOrganisation;
use Illuminate\Support\Facades\Notification;
use App\SalesPipeline\Notifications\DealTransferOwnership;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Base\Models\User;
use Carbon\Carbon;


class DealsObserver
{
    use Notifiable;
    /**
     * Handle the task "created" event.
     *
     * @param  \App\SalesPipeline $salespipeline
     * @return void
     */
    public function created(Deals $deal)
    {
        // $users = User::whereIn('id',[$deal->owner_id,$deal->getOriginal('owner_id')])->get();    // send the old deal owner and new deal owner email      
        // $oldOwner = User::where('id',$deal->getOriginal('owner_id'))->first();
        // $newOwner = User::where('id',$deal->owner_id)->first();
        // foreach ($users as $user) {            
        //     $user->notify(new DealTransferOwnership($deal, $oldOwner, $newOwner));
        // }
        
    }

    public function updated(Deals $deal)
    {           
        if($deal->wasChanged(['owner_id'])){ // deal owner ship changed            
            $newOwner = User::where('id',$deal->owner_id)->first();
            $oldOwner = User::where('id',$deal->getOriginal('owner_id'))->first();
            $newOwner->notify(new DealTransferOwnership($deal, auth()->user(),$oldOwner, $newOwner));
        }
    }

    // public function updateBothOwners(Deals $deal)
    // {           
    //     if($deal->wasChanged(['owner_id'])){ // deal owner ship changed
    //         $users = User::whereIn('id',[$deal->owner_id,$deal->getOriginal('owner_id')])->get();    // send the old deal owner and new deal owner email      
    //         $oldOwner = User::where('id',$deal->getOriginal('owner_id'))->first();
    //         $newOwner = User::where('id',$deal->owner_id)->first();
    //         foreach ($users as $user) {            
    //             $user->notify(new DealTransferOwnership($deal, $oldOwner, $newOwner));
    //         }
    //     }
    // }
}
