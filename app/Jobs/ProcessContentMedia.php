<?php

namespace App\Jobs;

use App\Models\Content;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessContentMedia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $content;
    public $mediaTempPath;
    public $mediaOriginalName;

    public function __construct(Content $content, $mediaTempPath, $mediaOriginalName)
    {
        $this->content           = $content;
        $this->mediaTempPath     = $mediaTempPath;
        $this->mediaOriginalName = $mediaOriginalName;
    }

    public function handle(): void
    {
        if (!file_exists($this->mediaTempPath)) {
            return;
        }

        $extension  = pathinfo($this->mediaOriginalName, PATHINFO_EXTENSION);
        $filename   = uniqid('media_') . '.' . $extension;
        $storedPath = "public/contents/{$filename}";
        $contents   = file_get_contents($this->mediaTempPath);
        Storage::put($storedPath, $contents);

        // Update content record
        $this->content->update([
            'media_path'  => Storage::url($storedPath),
            'is_uploaded' => true,
        ]);
        
        @unlink($this->mediaTempPath);
    }
}


