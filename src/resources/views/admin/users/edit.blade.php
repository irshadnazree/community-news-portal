@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold">Edit User</h1>
</div>

<div class="card bg-base-100 shadow-xl max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Name</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input input-bordered" required>
            </div>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input input-bordered" required>
            </div>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Password (leave blank to keep current)</span>
                </label>
                <input type="password" name="password" class="input input-bordered">
            </div>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Role</span>
                </label>
                <select name="role" class="select select-bordered" required>
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                    <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Editor</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="form-control">
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>
@endsection

