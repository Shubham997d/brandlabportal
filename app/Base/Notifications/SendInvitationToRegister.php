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

class SendInvitationToRegister extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;
   
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
    private $dynamic;
    /**
     * @var array
     */
    public $groupId;

    /**
     *  @var int
     */
    private $authUser;

    /**
     * @param User  $user
     *
     * @return void
     */
    public $extraData;
    /**
     * @param array
     *
     */
    private $mailData;
    /**
     * @param array
     *
     */
    public $actionSlug;
    /**
     * @param string
     *
     */    
    public function __construct(User $authUser,$extraData)
    {
        $this->groupType = config('constants.notifications.group_type')[2];
        $this->authUser = $authUser;
        $this->groupId = $authUser->id;
        $this->extraData = $extraData;
        $this->actionSlug = config('constants.notifications.action_slug')[51];
        $this->visibleToAdmin = true;
        $this->visibleToUser = false;
        $this->extraData['authUser'] = $this->authUser;
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {   
        // return config('notification.channels');   
        if ($this->extraData['send_only_mail']) {
            return ['mail'];            
        }else{
            return ['database'];
        }   
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed                                          $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
   {  
        return (new MailMessage())
                        ->subject($this->authUser->username . ' has invited you to join '.config('app.name').'')
                        ->markdown('emails.invite',[                        
                            'authUserName'    => $this->authUser->username,
                            'company' => config('app.name') ?? null,
                        ]);                        
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
            'subject'     => $this->authUser->only(['id', 'name', 'username', 'avatar']),
            'activity_content' => getNotificationContent($this->actionSlug,'activity',$this->extraData),
            'notification_content_start'   => null,
            'notification_content_middle'  => null,
            'notification_content_end'     => null,
            'object_type' => 'user',
            'object_name' => 'SendInvitationToRegister',
            'object_id'   => $this->authUser->id,
            'extra_data'  => null,
            'notification_type' => 'basic',
            'has_clickable_notification_content' => false,
            'url'         => null,
            'object_status' => null
        ];
    }    
  
}
