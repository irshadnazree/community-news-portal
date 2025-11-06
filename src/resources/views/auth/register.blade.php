@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="card bg-base-100 shadow-2xl w-full max-w-md">
        <div class="card-body p-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold mb-2">Create Account</h2>
                <p class="text-base-content/70">Join our community and start sharing news</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Full Name</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="Enter your full name"
                        class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('name') input-error @enderror" 
                        required>
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Email Address</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Enter your email"
                        class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('email') input-error @enderror" 
                        required>
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Password</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Create a password"
                        class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('password') input-error @enderror" 
                        required>
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Confirm Password</span>
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        placeholder="Confirm your password"
                        class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary" 
                        required>
                </div>

                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary btn-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Create Account
                    </button>
                </div>
            </form>

            <div class="divider my-6">OR</div>
            
            <div class="text-center">
                <p class="text-sm text-base-content/70 mb-2">
                    Already have an account?
                </p>
                <a href="{{ route('login') }}" class="btn btn-outline btn-block">
                    Sign In Instead
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

