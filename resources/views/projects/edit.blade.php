@extends('layout')

@section('content')
    <h1>Edit Project</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $project->title) }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="freelancer_id" class="form-label">Assign Freelancer</label>
            <select name="freelancer_id" id="freelancer_id" class="form-select">
                <option value="">Select a Freelancer</option>
                @foreach($freelancers as $id => $name)
                    <option value="{{ $id }}" {{ old('freelancer_id', $project->freelancer_id) == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="open" {{ $project->status == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

