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
                                <h3 class="card-title text-xl mb-2">{{ $post->title }}</h3>
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <div class="badge badge-primary">{{ $post->category->name }}</div>
                                    <div class="flex items-center gap-2 text-sm text-base-content/70">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $post->user->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-base-content/70">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <p class="text-sm text-base-content/70 line-clamp-3 mb-4">
                                    {{ Str::limit(strip_tags($post->content), 200) }}
                                </p>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('news.show', $post->slug) }}" class="btn btn-outline btn-sm" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Preview
                            </a>
                            <button 
                                wire:click="approve({{ $post->id }})" 
                                wire:loading.attr="disabled"
                                class="btn btn-success btn-sm">
                                <span wire:loading.remove wire:target="approve({{ $post->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Approve
                                </span>
                                <span wire:loading wire:target="approve({{ $post->id }})" class="loading loading-spinner loading-sm"></span>
                            </button>
                            <button 
                                wire:click="reject({{ $post->id }})" 
                                wire:loading.attr="disabled"
                                class="btn btn-error btn-sm">
                                <span wire:loading.remove wire:target="reject({{ $post->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reject
                                </span>
                                <span wire:loading wire:target="reject({{ $post->id }})" class="loading loading-spinner loading-sm"></span>
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
                <h3 class="text-2xl font-bold mb-2">No pending posts to review</h3>
                <p class="text-base-content/70">All submissions have been reviewed</p>
            </div>
        </div>
    @endforelse

    @if($posts->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $posts->links() }}
        </div>
    @endif
</div>
