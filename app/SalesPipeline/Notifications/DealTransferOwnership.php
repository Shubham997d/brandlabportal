<?php

namespace App\SalesPipeline\Notifications;

use Illuminate\Bus\Queueable;
use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\DealOrganisation;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notifiable;

class DealTransferOwnership extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    use Notifiable;

    public $groupType;
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
    public $groupId;
    /**
     * @var integer
     */
    public $authUser;
    /**
     * @var array
     */
    public $dealNewOwner;
    /**
     * @var array
     */
    public $dealOldOwner;
    /**
     * @var array
     */
    public $originalAttributes;
    /**
     * @var array
     */
    public $deals;
     /**
     * @var array
     */
    public $notificationContent;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($deals,User $authUser,User $dealOldOwner,User $dealNewOwner,$transferAll = false)
    {        
        $this->deals = $deals;
        $this->authUser = $authUser;
        $this->dealOldOwner = $dealOldOwner;
        $this->dealNewOwner = $dealNewOwner;
        $this->groupType = config('constants.notifications.group_type')[0];
        $this->actionSlug = config('constants.notifications.action_slug')[4];
        $this->visibleToAdmin = true;
        $this->visibleToUser = true;
        if ($transferAll) {
            $totalDealsCount = $this->deals->count();
            $otherDealsCount = $totalDealsCount - 1;
            $othersString = ($otherDealsCount == 1) ? 'other' : 'others ';
            $this->notificationContent['start'] = ($totalDealsCount > 1) ? $this->authUser->username.' has assigned this deal (<a href="'.url('deal/'.$this->deals[0]->deal_id).'" target="_blank">'.$this->deals[0]->title.'</a>) to you and ' : $this->authUser->username.' has assigned the deal (<a href="'.url('deal/'.$this->deals[0]->deal_id).'" target="_blank">'.$this->deals[0]->title.'</a>) to you';
            $this->notificationContent['middle'] = ($totalDealsCount > 1) ? $otherDealsCount.' '.$othersString : null;
            $this->notificationContent['activity_content'] = ($totalDealsCount > 1) ? $this->authUser->username.' has assigned this deal (<a href="'.url('deal/'.$this->deals[0]->deal_id).'" target="_blank">'.$this->deals[0]->title.'</a>) to '.$this->dealNewOwner->username.' and '.$this->notificationContent['middle'] : $this->authUser->username.' has assigned the deal (<a href="'.url('deal/'.$this->deals[0]->deal_id).'" target="_blank">'.$this->deals[0]->title.'</a>) to '.$this->dealNewOwner->username.$this->notificationContent['middle'];
            $this->notificationContent['extra'] = $this->deals->toArray();            
            $this->notificationContent['subject'] = ($totalDealsCount > 1) ? 'Multiple deals assigned' : 'A new deal assigned';
            
        }else{            
            $this->notificationContent['start'] =  ''.$this->authUser->username.' has assigned this deal (<a href="'.url('deal/'.$this->deals->id).'" target="_blank">'.$this->deals->deal_name.'</a>) to you';
            $this->notificationContent['middle'] = null;
            $this->notificationContent['extra'] = null;
            $this->notificationContent['activity_content'] = getNotificationContent($this->actionSlug,'activity',$this->deals);
            $this->notificationContent['subject'] = 'A new deal assigned';
        }
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return config('notification.channels');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */

    
    public function toDatabase($notifiable)
    {   
        
        return [
                'subject' => $this->authUser->only(['id', 'name', 'username', 'avatar']),
                'activity_content' => $this->notificationContent['activity_content'],
                'notification_content_start'   => $this->notificationContent['start'],
                'notification_content_middle'  => $this->notificationContent['middle'],
                'notification_content_end'     => null,
                'object_type' => 'deal',
                'object_name' => 'DealTransferOwnership',
                'object_id'   => null,
                'extra_data'  => $this->notificationContent['extra'],
                'notification_type' => 'basic',
                'has_clickable_notification_content' => false,
                'url'         => null,
                'object_status' => null,
        ];
       
    }

    public function toMail()
   {   
      $content = [
          'to_username' => $this->dealNewOwner->username,
          'middle_content' => $this->notificationContent['start'].$this->notificationContent['middle']
       ];

        return (new MailMessage())
                    ->subject($this->notificationContent['subject'])
                    ->markdown('emails.notification-comman-template',['mailData' => $content]);
    }
    
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'subject' => $this->authUser->only(['id', 'name', 'username', 'avatar']),
                'activity_content' => $this->notificationContent['activity_content'],
                'notification_content_start'   => $this->notificationContent['start'],
                'notification_content_middle'  => $this->notificationContent['middle'],
                'notification_content_end'     => null,
                'object_type' => 'deal',
                'object_name' => 'DealTransferOwnership',
                'object_id'   => null,
                'extra_data'  => $this->notificationContent['extra'],
                'notification_type' => 'basic',
                'has_clickable_notification_content' => false,
                'url'         => null,
                'object_status' => null,
            ],
            'date' => 'Just Now',
            'read_at' => null,       
        ]);
    }
}
