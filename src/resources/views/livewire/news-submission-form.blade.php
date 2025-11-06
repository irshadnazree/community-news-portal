<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <form wire:submit="save" class="space-y-6">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-lg">Title</span>
                </label>
                <input 
                    type="text" 
                    wire:model="title" 
                    class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('title') input-error @enderror" 
                    placeholder="Enter a compelling news title">
                @error('title') 
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label> 
                @enderror
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-lg">Category</span>
                </label>
                <select wire:model="category_id" class="select select-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('category_id') select-error @enderror">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') 
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label> 
                @enderror
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-lg">Content</span>
                </label>
                <textarea 
                    wire:model="content" 
                    class="textarea textarea-bordered h-64 focus:outline-none focus:ring-2 focus:ring-primary @error('content') textarea-error @enderror" 
                    placeholder="Write your news article content here..."></textarea>
                @error('content') 
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label> 
                @enderror
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-lg">Cover Image</span>
                    <span class="label-text-alt">Optional</span>
                </label>
                
                @if($existing_image)
                    <div class="mb-4">
                        <div class="card bg-base-200 shadow-sm">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ Storage::url($existing_image) }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg">
                                    <div>
                                        <p class="font-medium">Current Image</p>
                                        <p class="text-sm text-base-content/70">Upload a new image to replace</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <input 
                    type="file" 
                    wire:model="cover_image" 
                    accept="image/*" 
                    class="file-input file-input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('cover_image') file-input-error @enderror">
                
                @error('cover_image') 
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label> 
                @enderror
                
                @if($cover_image)
                    <div class="mt-4">
                        <div class="card bg-base-200 shadow-sm">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $cover_image->temporaryUrl() }}" alt="Preview" class="w-32 h-32 object-cover rounded-lg">
                                    <div>
                                        <p class="font-medium">Image Preview</p>
                                        <p class="text-sm text-base-content/70">This will be your cover image</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-control mt-8">
                <button type="submit" class="btn btn-primary btn-lg btn-block" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">
                        @if($postId)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update News
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Submit News
                        @endif
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <span class="loading loading-spinner loading-sm mr-2"></span>
                        Saving...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
