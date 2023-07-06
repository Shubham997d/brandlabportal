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
use App\Base\Models\User as UserModel;

class RequestAcceptOrDenied extends Notification implements ShouldQueue, ShouldBroadcast
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
    private $user;
    /**
     * @param User  $user
     *
     * @return void
     */
    public $authUser;
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
    public function __construct(User $user, $dynamic, $extraData = null)
    {
        $this->groupType = config('constants.notifications.group_type')[2];
        $this->groupId = $user->id;
        $this->user = $user;
        $this->dynamic = $dynamic;
        $this->actionSlug = $dynamic['actionSlug'];
        $this->extraData = $extraData;        
        $this->visibleToAdmin = true;
        $this->visibleToUser = true;
        $this->authUser = isset(auth()->user()->id) ? auth()->user() : UserModel::where('email',explode(',', env('MAIL_ADDRESSES'))[0])->first();  // set scott as the main user if auth not set   
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {   
        return config('notification.channels');      
        // return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed                                          $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
   {  
        $name = config('company_name') ?? config('app.name'); 
        return (new MailMessage())
                        ->subject($this->dynamic['mailData']['subject'])
                        ->markdown('emails.request-accept-or-denied',['mailData' => $this->dynamic['mailData']]);                        
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
            'notification_content_start'   => getNotificationContent($this->actionSlug,'notification',$this->extraData),
            'notification_content_middle'  => null,
            'notification_content_end'     => null,
            'object_type' => 'user',
            'object_name' => 'RequestAcceptOrDenied',
            'object_id'   => $this->authUser->id,
            'extra_data'  => null,
            'notification_type' => 'basic',
            'has_clickable_notification_content' => false,
            'url'         => null,
            'object_status' => null
        ];
    }

    /**
     * @param  mixed $notifiable
     * @return void
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'subject'   => $this->authUser->only(['id', 'name', 'username', 'avatar']),
                'activity_content' => getNotificationContent($this->actionSlug,'activity',$this->extraData),
                'notification_content_start'   => getNotificationContent($this->actionSlug,'notification',$this->extraData),
                'notification_content_middle'  => null,
                'notification_content_end'     => null,
                'object_type' => 'user',
                'object_name' => 'RequestAcceptOrDenied',
                'object_id'   => $this->authUser->id,
                'extra_data'  => null,
                'notification_type' => 'basic',
                'has_clickable_notification_content' => false,
                'url'         => null,
                'object_status' => null
            ],
            'date' => 'Just Now',
            'read_at' => null,
        ]);
    }
}
