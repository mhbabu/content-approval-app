<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Content;
use App\Notifications\ContentStatusNotification;
use Illuminate\Support\Facades\Notification;

class SendContentPublishedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function handle(): void
    {
        $this->content->user->notify(new ContentStatusNotification($this->content));
    }
}


