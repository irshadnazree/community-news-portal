@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="card bg-base-100 shadow-2xl w-full max-w-md">
        <div class="card-body p-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold mb-2">Welcome Back</h2>
                <p class="text-base-content/70">Sign in to your account to continue</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                
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
                        placeholder="Enter your password"
                        class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('password') input-error @enderror" 
                        required>
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="remember" class="checkbox checkbox-primary">
                        <span class="label-text">Remember me</span>
                    </label>
                </div>

                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary btn-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign In
                    </button>
                </div>
            </form>

            <div class="divider my-6">OR</div>
            
            <div class="text-center">
                <p class="text-sm text-base-content/70 mb-2">
                    Don't have an account?
                </p>
                <a href="{{ route('register') }}" class="btn btn-outline btn-block">
                    Create New Account
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

