@extends('layouts.app')

@section('title', 'My Submissions')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl lg:text-5xl font-bold mb-2">My Submissions</h1>
    <p class="text-base-content/70 text-lg">Manage your news articles and track their status</p>
</div>

@livewire('my-submissions-list')
@endsection

