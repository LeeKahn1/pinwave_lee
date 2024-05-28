<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\User;

class PinLiked extends Notification
{
    use Queueable;

    private $likerId;
    /**
     * Create a new notification instance.
     */
    public function __construct($pin, $likerId)
    {
        $this->pin = $pin;
        $this->likerId = $likerId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $liker = User::find($this->likerId);

        return [
            'message' => 'Your pin titled "' . $this->pin->title . '" was liked by ' . $liker->username,
            'pin_id' => $this->pin->id,
            'liker_id' => $this->likerId,
            // add other data if needed
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
