@extends('layouts.app')
@section('title', 'Add New Content')
@section('content')
<div class="px-4">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-plus-circle"></i> {{ __('Create Content') }}</h5>
            </div>

            <form method="POST" action="{{ route('contents.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row mb-3">

                        <div class="col-md-4 mb-2">
                            <label for="title" class="form-label required">Title *</label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                class="form-control" 
                                placeholder="Title" 
                                maxlength="191" 
                                required 
                                autofocus
                                value="{{ old('title') }}"
                            >
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="thumbnail" class="form-label">Thumbnail *</label>
                            <input 
                                type="file" 
                                name="thumbnail" 
                                id="thumbnail" 
                                accept="image/*" 
                                class="form-control"
                                required
                            >
                            <small class="form-text text-muted">Supported formats: JPG, PNG, GIF</small>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="media" class="form-label">Media *</label>
                            <input 
                                type="file" 
                                name="media" 
                                id="media" 
                                class="form-control" 
                                required
                            >
                            <small class="form-text text-muted">Upload video, audio, or other supported media</small>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="content" class="form-label required">Content *</label>
                            <textarea 
                                name="content" 
                                id="content" 
                                class="form-control" 
                                placeholder="Content" 
                                maxlength="1000" 
                                rows="5" 
                                required
                            >{{ old('content') }}</textarea>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <div class="row mb-0">
                        <div class="col-md-6 text-start">
                            <a href="{{ route('contents.index') }}" class="btn btn-secondary btn-sm">{{ __('Back') }}</a>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
