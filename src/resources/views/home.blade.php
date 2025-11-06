@extends('layouts.app')

@section('title', 'Home - Community News Portal')

@section('content')
<!-- Hero Section -->
<div class="hero bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl mb-8 p-8 lg:p-12">
    <div class="hero-content text-center">
        <div class="max-w-2xl">
            <h1 class="text-4xl lg:text-5xl font-bold mb-4">Latest Community News</h1>
            <p class="text-lg opacity-80 mb-6">Stay informed with the latest updates from our community</p>
            <div class="flex justify-center gap-2">
                @livewire('social-share-buttons', [
                    'pageUrl' => url('/'),
                    'pageTitle' => 'Community News Portal',
                    'pageType' => 'home'
                ])
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card bg-base-100 shadow-xl mb-8">
    <div class="card-body">
        <form method="GET" action="{{ route('home') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="form-control flex-1">
                <div class="join w-full">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search news articles..." 
                        class="input input-bordered join-item flex-1 focus:outline-none focus:ring-2 focus:ring-primary">
                    <button type="submit" class="btn btn-primary join-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                </div>
            </div>
            <div class="form-control lg:w-64">
                <select name="category" class="select select-bordered w-full">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<!-- News Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @forelse($posts as $post)
        <article class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300 overflow-hidden group">
            @if($post->cover_image)
                <figure class="relative overflow-hidden">
                    <img 
                        src="{{ Storage::url($post->cover_image) }}" 
                        alt="{{ $post->title }}" 
                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-4 right-4">
                        <div class="badge badge-primary badge-lg shadow-lg">{{ $post->category->name }}</div>
                    </div>
                </figure>
            @else
                <div class="bg-gradient-to-br from-primary/20 to-secondary/20 h-48 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <div class="absolute top-4 right-4">
                        <div class="badge badge-primary badge-lg shadow-lg">{{ $post->category->name }}</div>
                    </div>
                </div>
            @endif
            <div class="card-body">
                <h2 class="card-title line-clamp-2 group-hover:text-primary transition-colors">
                    <a href="{{ route('news.show', $post->slug) }}" class="hover:underline">
                        {{ $post->title }}
                    </a>
                </h2>
                <p class="text-sm text-base-content/70 line-clamp-3 mb-4">
                    {{ Str::limit(strip_tags($post->content), 120) }}
                </p>
                <div class="card-actions justify-between items-center pt-4 border-t border-base-300">
                    <div class="flex items-center gap-4 text-xs text-base-content/60">
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>{{ number_format($post->views_count) }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $post->published_at?->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('news.show', $post->slug) }}" class="btn btn-primary btn-sm">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </article>
    @empty
        <div class="col-span-full">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body text-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold mb-2">No news posts found</h3>
                    <p class="text-base-content/70 mb-6">Try adjusting your search or filter criteria</p>
                    @auth
                        <a href="{{ route('submit-news') }}" class="btn btn-primary">Submit Your First News</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Join Our Community</a>
                    @endauth
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($posts->hasPages())
    <div class="flex justify-center mt-8">
        <div class="join">
            {{ $posts->links() }}
        </div>
    </div>
@endif
@endsection

