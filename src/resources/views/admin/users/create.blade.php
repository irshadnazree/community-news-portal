@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold">Create User</h1>
</div>

<div class="card bg-base-100 shadow-xl max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Name</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered" required>
            </div>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered" required>
            </div>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Password</span>
                </label>
                <input type="password" name="password" class="input input-bordered" required>
            </div>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Role</span>
                </label>
                <select name="role" class="select select-bordered" required>
                    <option value="user">User</option>
                    <option value="editor">Editor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-control">
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>
@endsection

