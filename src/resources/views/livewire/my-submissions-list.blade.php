<div>
    @forelse($posts as $post)
        <div class="card bg-base-100 shadow-xl mb-6 hover:shadow-2xl transition-shadow">
            <div class="card-body">
                <div class="flex flex-col lg:flex-row gap-6">
                    @if($post->cover_image)
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ Storage::url($post->cover_image) }}" 
                                alt="{{ $post->title }}" 
                                class="w-full lg:w-48 h-48 object-cover rounded-lg">
                        </div>
                    @endif
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <h3 class="card-title text-xl mb-2">
                                    <a href="{{ route('news.show', $post->slug) }}" class="hover:text-primary transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <div class="badge badge-primary">{{ $post->category->name }}</div>
                                    @if($post->status === 'pending')
                                        <span class="badge badge-warning badge-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Pending Review
                                        </span>
                                    @elseif($post->status === 'approved')
                                        <span class="badge badge-info badge-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Approved
                                        </span>
                                    @else
                                        <span class="badge badge-success badge-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            Published
                                        </span>
                                    @endif
                                    <div class="flex items-center gap-2 text-sm text-base-content/70">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('news.show', $post->slug) }}" class="btn btn-outline btn-sm" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                            <a href="{{ route('edit-news', $post->id) }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <button 
                                wire:click="delete({{ $post->id }})" 
                                wire:confirm="Are you sure you want to delete this post?"
                                wire:loading.attr="disabled"
                                class="btn btn-error btn-sm">
                                <span wire:loading.remove wire:target="delete({{ $post->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </span>
                                <span wire:loading wire:target="delete({{ $post->id }})" class="loading loading-spinner loading-sm"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body text-center py-16">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-2xl font-bold mb-2">No submissions yet</h3>
                <p class="text-base-content/70 mb-6">Start sharing your news with the community</p>
                <a href="{{ route('submit-news') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Submit Your First News
                </a>
            </div>
        </div>
    @endforelse

    @if($posts->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $posts->links() }}
        </div>
    @endif
</div>
