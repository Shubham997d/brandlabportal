<?php

namespace App\Base\Notifications;

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

class DynamicNotification extends Notification implements ShouldQueue, ShouldBroadcast
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
    public $model;
     /**
     * @var array
     */
    public $dynamicData;
    /**
    * @var array
    */

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($model,$dynamicData = [],$user = null)
    {        
        $this->model = $model;
        $this->dynamicData = $dynamicData;   
        $this->authUser = isset($user) ? $user : auth()->user();
        $this->groupType = $this->dynamicData['group_type'];
        $this->actionSlug = $this->dynamicData['action_slug'];
        $this->visibleToAdmin = $this->dynamicData['visible_to_admin'];
        $this->visibleToUser = $this->dynamicData['visible_to_user'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'activity_content' => getNotificationContent($this->actionSlug,'activity',$this->model),
            'notification_content_start'   => null,
            'notification_content_middle'  => null,
            'notification_content_end'     => null,
            'object_type' => $this->actionSlug,
            'object_name' => 'Dynamic',
            'object_id'   => null,
            'extra_data'  => null,
            'notification_type' => 'basic',
            'has_clickable_notification_content' => false,
            'url'         => null,
            'object_status' => null,
        ];
       
    }  

    
}
