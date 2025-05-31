<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Content;

class ContentStatusNotification extends Notification
{
    use Queueable;

    protected $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function via($notifiable)
    {
        return ['mail']; // or ['database', 'mail'] as you want
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->content->status);
        return (new MailMessage)
            ->subject("Your content status has changed to {$status}")
            ->line("Your content titled '{$this->content->title}' has been {$status}.")
            ->action('View Content', url("/contents/{$this->content->id}"));
    }
}
