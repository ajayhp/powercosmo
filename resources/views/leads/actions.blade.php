@foreach($leads as $lead)
    <div class="lead">
        <h4>{{ $lead->name }}</h4>
        <button class="edit-lead-btn" data-id="{{ $lead->id }}">Edit</button>
        <button class="post-update-btn" data-id="{{ $lead->id }}">Post Update</button>
        <button class="view-updates-btn" data-id="{{ $lead->id }}">View Updates</button>
    </div>
@endforeach
