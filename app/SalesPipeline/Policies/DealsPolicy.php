<?php

namespace App\SalesPipeline\Policies;

use App\Base\Models\User;
use App\SalesPipelines\Models\Deals;
use App\Authorization\Authorization;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class DealsPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {        
        
    }  
    
    
    public function accessAdminReport(User $user)
    {
        return (new Authorization($user))->userHasPermissionTo('access', 'admin-report-deal');
    }

    public function index(User $user) // all deals
    {
        return (new Authorization($user))->userHasPermissionTo('index', 'deal');
    }

    public function show(User $user) // one deal
    {
        return (new Authorization($user))->userHasPermissionTo('show', 'deal');
    }

    public function createDeals(User $user,$owner_id)
    {   

        $isDeleted = isset($owner_id) ? $user->where('id',$owner_id)->first()->isDeleted() : false;
        if ($isDeleted == true) {
            return false;
        }
        if(auth()->user()->isAdmin() == false && isset($owner_id) ){
            if((int) $owner_id != (int) auth()->user()->id) { // Don't let other user other than admin create the deals for other users
                return false;
            }
        }
        return (new Authorization($user))->userHasPermissionTo('create', 'deal'); 
    }

    public function updateDeals(User $user) // any change in deal 
    {
        return (new Authorization($user))->userHasPermissionTo('update', 'deal'); // Allow only sales team & admin for now
    }

    public function transferOwnership(User $user,$to_user_id) 
    {
        $user = $user->where('id',$to_user_id)->first();                
        if($user->isDeleted() == true){  // check if user is not deleted            
             return false;
        }       
        return (new Authorization(auth()->user()))->userHasPermissionTo('transferOwnership', 'deal'); 
    }

    public function updateInovice(User $user,$deals) // Allow only deal owenr, account department & admin for now
    {   
        if ($deals->owner_id == auth()->user()->id) {
                return true;
        }
        return (new Authorization($user))->userHasPermissionTo('updateInvoice', 'deal'); 
        
    }

    public function updateDealStatus(User $user,$deal) // Allow only deal owner & admin for now
    {
        if ($deal->owner_id === auth()->user()->id) { 
                return true;
        }
        return (new Authorization($user))->userHasPermissionTo('updateDealStatus', 'deal'); 
    }
}
