<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExistingUserNotification extends Notification
{
    use Queueable;

    private $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($project)
    {
        $this->project = $project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('You are assigned to the project')
                    ->line('You are assigned to the project')
                    ->line('Accept invitation by pressing button below')
                    ->action('Accept Invitation', route('admin.projects.acceptInvitation', $this->project))
                    ->line('Thank you for using our application!');
    }
}
