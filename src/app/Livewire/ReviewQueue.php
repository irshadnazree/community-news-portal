<?php

namespace App\Livewire;

use App\Models\NewsPost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ReviewQueue extends Component
{
    use AuthorizesRequests;

    public function approve($postId)
    {
        $post = NewsPost::findOrFail($postId);
        Gate::authorize('approve', $post);

        $post->status = 'published';
        $post->published_at = now();
        $post->save();

        session()->flash('message', 'Post approved and published successfully!');
    }

    public function reject($postId)
    {
        $post = NewsPost::findOrFail($postId);
        Gate::authorize('reject', $post);

        $post->status = 'pending';
        $post->save();

        session()->flash('message', 'Post rejected and set back to pending.');
    }

    public function render()
    {
        $posts = NewsPost::where('status', 'pending')
            ->with(['user', 'category'])
            ->latest()
            ->paginate(10);

        return view('livewire.review-queue', compact('posts'));
    }
}
