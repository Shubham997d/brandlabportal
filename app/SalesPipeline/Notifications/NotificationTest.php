<?php

namespace App\SalesPipeline\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notifiable;

class NotificationTest extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    use Notifiable;

    public $message;
    /**
     * @var string    */  
   

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, User $user)
    {        
        $this->message = $message;
        $this->user = $user;            
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'subject'     => $this->user->only(['id', 'name', 'username', 'avatar']),
                'notification_content_start'   => $this->message,
                'notification_content_middle'  => null,
                'notification_content_end'     => null,
                'object_type' => 'deal',
                'object_name' => 'Working',
                'object_id'   => null,
                'extra_data'  => null,
                'notification_type' => 'group',
                'has_clickable_notification_content' => false,
                'url'         => 'Test',
                'object_status' => null,
            ],
            'date' => 'Just Now',
            'read_at' => null,         
        ]);    
    }
}

   