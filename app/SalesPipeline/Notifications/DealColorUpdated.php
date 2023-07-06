<?php

namespace App\SalesPipeline\Notifications;

use Illuminate\Bus\Queueable;
use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\DealOrganisation;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notifiable;

class DealColorUpdated extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    use Notifiable;

    public $groupType;
    /**
     * @var string
     */  
    public $groupId;
    /**
     * @var string
     */
    public $visibleToAdmin;
    /**
     * @var integer
     */ 
    public $visibleToUser;
    /**
     * @var integer
     */  
    public $actionSlug;
    /**
     * @var string
     */ 
    public $originalAttributes;
    /**
     * @var string
     */
    public $dealOrganisationItems;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($dealOrganisationItems, User $user)
    {        
        $this->dealOrganisationItems = $dealOrganisationItems->toArray();
        $this->user = $user;    
        $this->groupType = config('constants.notifications.group_type')[0];
        $this->actionSlug = config('constants.notifications.action_slug')[5];
        $this->visibleToAdmin = true;
        $this->visibleToUser = true;      
        // $this->originalAttributes = $this->dealOrganisationItems->getOriginal();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */

    
    public function toDatabase($notifiable)
    {   
        $this->dealOrganisationItems = (is_array($this->dealOrganisationItems) && count($this->dealOrganisationItems) >= 0 ) ? DealOrganisation::hydrate($this->dealOrganisationItems) : $this->dealOrganisationItems;
        $totalDealsCount = (int) $this->dealOrganisationItems->count();
        $otherDealsCount = $totalDealsCount - 1;
        $othersString = ($otherDealsCount == 1) ? 'other' : 'others ';
        $notification_content_start = ($totalDealsCount > 1) ? '<a href="'.url('deal/'.$this->dealOrganisationItems->first()->deal_id).'" target="_blank">'.$this->dealOrganisationItems->first()->title.'</a> and ' : '<a href="'.url('deal/'.$this->dealOrganisationItems->first()->deal_id).'" target="_blank">'.$this->dealOrganisationItems->first()->title.'</a> has turned '.$this->dealOrganisationItems->first()->dealColorLabel();
        $notification_content_middle = ($totalDealsCount > 1) ? $otherDealsCount.' '.$othersString : null;
        $notification_content_end = ($totalDealsCount > 1) ? 'have turned '.$this->dealOrganisationItems->first()->dealColorLabel() : null;

        if($totalDealsCount > 0) { // meaning data exists            
            return [
                'subject'     => $this->user->only(['id', 'name', 'username', 'avatar']),
                'activity_content' => getNotificationContent($this->actionSlug,'activity',['content' => $notification_content_start.$notification_content_middle.$notification_content_end ]),
                'notification_content_start'   => $notification_content_start,
                'notification_content_middle'  => $notification_content_middle,
                'notification_content_end'     => $notification_content_end,
                'object_type' => 'deal',
                'object_name' => $this->dealOrganisationItems->first()->title,
                'object_id'   => $this->dealOrganisationItems->first()->deal_id,
                'extra_data'  => $this->dealOrganisationItems->toArray(),
                'notification_type' => 'group',
                'has_clickable_notification_content' => ($totalDealsCount > 1) ? true : false,
                'url'         => url('deal/'.$this->dealOrganisationItems->first()->deal_id),
                'object_status' => null,
            ];           

        }

       
    }

    public function toMail()
   {   
        // return (new MailMessage())
        //             ->subject('Reminder to Update Portal')
        //             ->markdown('emails.task-update-mail',['value' => $this->user]);
    }

    
    
    public function toBroadcast($notifiable)
    {
        $this->dealOrganisationItems = (is_array($this->dealOrganisationItems) && count($this->dealOrganisationItems) >= 0 ) ? DealOrganisation::hydrate($this->dealOrganisationItems) : $this->dealOrganisationItems;
        $totalDealsCount = (int) $this->dealOrganisationItems->count();
        $otherDealsCount = $totalDealsCount - 1;
        $othersString = ($otherDealsCount == 1) ? 'other' : 'others ';
        $notification_content_start = ($totalDealsCount > 1) ? '<a href="'.url('deal/'.$this->dealOrganisationItems->first()->deal_id).'" target="_blank">'.$this->dealOrganisationItems->first()->title.'</a> and ' : '<a href="'.url('deal/'.$this->dealOrganisationItems->first()->deal_id).'" target="_blank">'.$this->dealOrganisationItems->first()->title.'</a> has turned '.$this->dealOrganisationItems->first()->dealColorLabel();
        $notification_content_middle = ($totalDealsCount > 1) ? $otherDealsCount.' '.$othersString : null;
        $notification_content_end = ($totalDealsCount > 1) ? 'have turned '.$this->dealOrganisationItems->first()->dealColorLabel() : null;

        if($totalDealsCount > 0) { // meaning data exists
            return new BroadcastMessage([
                'data' => [
                    'subject'     => $this->user->only(['id', 'name', 'username', 'avatar']),
                    'activity_content' => getNotificationContent($this->actionSlug,'activity',['content' => $notification_content_start.$notification_content_middle.$notification_content_end ]),
                    'notification_content_start'   => $notification_content_start,
                    'notification_content_middle'  => $notification_content_middle,
                    'notification_content_end'     => $notification_content_end,
                    'object_type' => 'deal',
                    'object_name' => $this->dealOrganisationItems->first()->title,
                    'object_id'   => $this->dealOrganisationItems->first()->deal_id,
                    'extra_data'  => $this->dealOrganisationItems->toArray(),
                    'notification_type' => 'group',
                    'has_clickable_notification_content' => ($totalDealsCount > 1) ? true : false,
                    'url'         => url('deal/'.$this->dealOrganisationItems->first()->deal_id),
                    'object_status' => null,
                ],
                'date' => 'Just Now',
                'read_at' => null,
            ]);    
        }
    }
}
