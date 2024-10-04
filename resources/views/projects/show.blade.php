@extends('layout')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3>{{ $project->title }}</h3>
            </div>
            <div class="card-body">
                <h5 class="card-title">Description</h5>
                <p class="card-text">{{ $project->description }}</p>
                <p class="card-text"><strong>Client:</strong> {{ $project->client->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Freelancer:</strong> {{ $project->freelancer->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Status:</strong> {{ ucfirst($project->status) }}</p>

                <h5 class="mt-4">Work Logs</h5>
                @if($project->workLogs->isEmpty())
                    <p class="text-muted">No work logs available for this project.</p>
                @else
                    <div class="list-group">
                        @foreach($project->workLogs as $workLog)
                            <div class="list-group-item">
                                <p><strong>Start Time:</strong> {{ $workLog->start_time }}</p>
                                <p><strong>End Time:</strong> {{ $workLog->end_time ?? 'N/A' }}</p>
                                <p><strong>Hours Logged:</strong> {{ $workLog->hours_logged }}</p>
                                <p><strong>Keyboard Activity:</strong> {{ $workLog->keyboard_activity }}</p>
                                <p><strong>Mouse Activity:</strong> {{ $workLog->mouse_activity }}</p>
                                
                                <h6>Screenshots:</h6>
                                @if($workLog->screenshots->isEmpty())
                                    <p class="text-muted">No screenshots available.</p>
                                @else
                                    <ul class="list-unstyled">
                                        @foreach($workLog->screenshots as $screenshot)
                                            <li>
                                            <a href="{{ asset('storage/' . $screenshot->screenshot_path) }}" target="_blank" class="text-decoration-none">
    Screenshot taken at {{ $screenshot->captured_at }}
</a>

                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-3">Back to Projects</a>
    </div>
@endsection
