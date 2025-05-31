<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Content\ContentListDataTable;
use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Services\ContentService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    protected $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

     public function index(ContentListDataTable $contentListDataTable)
    {
        return $contentListDataTable->render('admin.content.index');
    }
    
    public function create()
    {
        return view("admin.content.create");
    }

    public function store(Request $request)
    {
         $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string|max:1000',
            'thumbnail' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
            'media'     => 'required|file|mimes:jpg,jpeg,png,mp3,wav,mp4,mov,avi|max:51200', // 50MB max
        ]);

        $this->contentService->store($request, auth()->user());
        Toastr::success('Content created successfully.');
        return redirect()->back();
    }

    public function approve($id)
    {
        $content = Content::findOrFail($id);
        if(!$content){
            Toastr::error('Content not found.');
        }
        $this->contentService->approve($content);
        Toastr::success('Content approved successfully.');
        return redirect()->back();
    }

    public function reject($id)
    {
        $content = Content::findOrFail($id);
        if(!$content){
            Toastr::error('Content not found.');
        }
        Toastr::success('Content rejected successfully.');
        $this->contentService->reject($content);
        return redirect()->back();
    }
}
