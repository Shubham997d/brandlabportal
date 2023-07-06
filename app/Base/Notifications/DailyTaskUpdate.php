<?php

namespace App\Base\Notifications;

use App\Base\Models\User;
use App\Base\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Base\Mail\TaskUpdateMail;
use Illuminate\Support\Facades\Mail;

class DailyTaskUpdate extends Notification implements ShouldQueue, ShouldBroadcast
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
    public $actionSlug;
    /**
     * @var string
     */
    private $groupId;

    /**
     * @var \App\Base\Models\User
     */
    private $user;

    /**
     * @param Group $group
     * @param User  $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->groupType = config('constants.notifications.group_type')[1];
        $this->groupId = $user->id;
        $this->user = $user;
        $this->actionSlug = config('constants.notifications.action_slug')[6];
        $this->visibleToAdmin = false;
        $this->visibleToUser = true;   
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {   
        return config('notification.channels');        
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
                    ->subject('Reminder to Update Portal')
                    ->markdown('emails.task-update-mail',['value' => $this->user]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toDatabase()
    {
        return [
            'subject'     => $this->user->only(['id', 'name', 'username', 'avatar']),
            'activity_content' => null,
            'notification_content_start'   => 'Reminder: Update your tasks and deals',
            'notification_content_middle'  => null,
            'notification_content_end'     => null,
            'object_type' => 'user',
            'object_name' => 'dailyTaskUpdate',
            'object_id'   => $this->user->id,
            'extra_data'  => null,
            'notification_type' => 'basic',
            'has_clickable_notification_content' => false,
            'url'         => null,
            'object_status' => null,
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
                'subject'     => $this->user->only(['id', 'name', 'username', 'avatar']),
                'activity_content' => null,
                'notification_content_start'   => 'Remider to update your tasks and deals',
                'notification_content_middle'  => null,
                'notification_content_end'     => null,
                'object_type' => 'user',
                'object_name' => 'dailyTaskUpdate',
                'object_id'   => $this->user->id,
                'extra_data'  => null,
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
