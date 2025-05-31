<?php

namespace App\Services;

use App\Jobs\ProcessContentMedia;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContentStatusNotification;

class ContentService
{
    public function store(Request $request, $user)
    {
        // Store thumbnail immediately
        $thumbnail      = $request->file('thumbnail');
        $thumbFilename  = uniqid('thumb_') . '.' . $thumbnail->getClientOriginalExtension();
        $thumbPath      = $thumbnail->storeAs('public/contents/thumbnails', $thumbFilename);
        $thumbnailUrl   = Storage::url($thumbPath);

        // Prepare temp directory for media && we will delete the file after uploading properly by Job
        $tempDir = storage_path('app/temp_uploads');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Move media file to temp directory
        $media        = $request->file('media');
        $originalName = $media->getClientOriginalName();
        $tempFilename = uniqid() . '_' . $originalName;
        $tempFilePath = $tempDir . '/' . $tempFilename;
        $media->move($tempDir, $tempFilename);

        $content = Content::create([
            'user_id'        => $user->id,
            'title'          => $request->title,
            'content'        => $request->content,
            'thumbnail_path' => $thumbnailUrl,
            'status'         => 'pending',
        ]);

        // Dispatch the job with content and temp media file path
        ProcessContentMedia::dispatch($content, $tempFilePath, $originalName);

        return $content;
    }


    public function approve($content)
    {
        $content->update([
            'status'       => 'approved',
            'published_at' => now()
        ]);

        // Send notification to content owner
        $content->user->notify(new ContentStatusNotification($content));

        return $content;
    }

    public function reject(Content $content)
    {
        $content->update([
            'status'       => 'rejected',
            'rejected_at'  => now()
        ]);

        // Send notification to content owner
        $content->user->notify(new ContentStatusNotification($content));

        return $content;
    }
}
