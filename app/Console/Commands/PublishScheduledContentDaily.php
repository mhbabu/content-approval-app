<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;
use App\Jobs\SendContentPublishedNotification;

class PublishScheduledContentDaily extends Command
{
    protected $signature   = 'content:publish-daily';
    protected $description = 'Publish pending content scheduled before now and send notifications';

    public function handle(): void
    {
        $now = now();

        // Use chunking for memory efficiency
        Content::where('status', 'pending')
            ->whereDate('published_at', '<=', $now->toDateString())
            ->whereTime('published_at', '<=', $now->toTimeString())
            ->chunk(100, function ($contents) {
                foreach ($contents as $content) {
                    $content->update(['status' => 'approved']);
                    SendContentPublishedNotification::dispatch($content);
                    $this->info("Published & notified: {$content->title}");
                }
            });

        $this->info("Scheduled publishing task completed.");
    }
}
