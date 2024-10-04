@extends('layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Projects</h1>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Create New Project</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
                <tr>
                    <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a></td>
                    <td>{{ ucfirst($project->status) }}</td>
                    <td>
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
