@extends('layouts.app')

@section('title', $post->title)

@push('meta')
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($post->content), 200) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    @if($post->cover_image)
        <meta property="og:image" content="{{ asset('storage/' . $post->cover_image) }}">
    @endif
@endpush

@section('content')
<article class="max-w-4xl mx-auto">
    <!-- Cover Image -->
    @if($post->cover_image)
        <div class="card bg-base-100 shadow-xl mb-6 overflow-hidden">
            <figure class="relative">
                <img 
                    src="{{ Storage::url($post->cover_image) }}" 
                    alt="{{ $post->title }}" 
                    class="w-full h-64 lg:h-96 object-cover">
                <div class="absolute top-4 left-4">
                    <div class="badge badge-primary badge-lg shadow-lg">{{ $post->category->name }}</div>
                </div>
            </figure>
        </div>
    @else
        <div class="card bg-gradient-to-br from-primary/10 to-secondary/10 shadow-xl mb-6">
            <div class="card-body py-8">
                <div class="badge badge-primary badge-lg mb-4">{{ $post->category->name }}</div>
            </div>
        </div>
    @endif

    <!-- Article Header -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <div class="avatar placeholder">
                    <div class="bg-neutral text-neutral-content rounded-full w-12">
                        <span class="text-lg">{{ substr($post->user->name, 0, 1) }}</span>
                    </div>
                </div>
                <div>
                    <div class="font-semibold">{{ $post->user->name }}</div>
                    <div class="text-sm text-base-content/60 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $post->published_at?->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
            
            <h1 class="text-3xl lg:text-4xl font-bold mb-6 leading-tight">{{ $post->title }}</h1>

            <!-- Stats and Actions -->
            <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-base-300">
                <div class="stat bg-base-200 rounded-lg px-4 py-2">
                    <div class="stat-figure text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div class="stat-title text-xs">Views</div>
                    <div class="stat-value text-lg">{{ number_format($post->views_count) }}</div>
                </div>
                <div>
                    @livewire('news-like-button', ['post' => $post])
                </div>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body prose prose-lg max-w-none">
            <div class="whitespace-pre-wrap text-base leading-relaxed">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
    </div>

    <!-- Share Section -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h3 class="card-title mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                </svg>
                Share this article
            </h3>
            <div class="flex flex-wrap gap-2">
                @livewire('social-share-buttons', [
                    'pageUrl' => request()->fullUrl(),
                    'pageTitle' => $post->title,
                    'pageType' => 'news',
                    'newsPostId' => $post->id
                ])
            </div>
        </div>
    </div>
</article>
@endsection

