@extends('layouts.app')

@section('content')

<p>Details page</p>
<div class="container">
    <p><strong>Post Title:</strong> {{ $discussion->post_title }}</p> <hr>
    <p><strong>Description:</strong> {{ $discussion->description }}</p> <hr>
    <p><strong>Brief:</strong> {{ $discussion->brief }}</p> <hr>
    <p><strong>Written by:</strong> {{ $discussion->user->name }}</p>

    {{-- Display tags --}}
    <div class="tags">
        Tags:
        <ul>
            @foreach ($discussion->tags as $tag)
                <li>{{ $tag->name }}</li>
            @endforeach
        </ul>
    </div>

    <div class="comments">
        <ul class="list-group">
            @foreach ($discussion->comments as $comment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $comment->created_at->diffForHumans() }} 

                        by 
                        @if($comment->user->isAdmin())
                            <i class="fas fa-crown" title="Administrator"></i>
                        @else
                            <i class="fas fa-user" title="User"></i>
                        @endif
                        {{ $comment->user->name }}
                    
                        </strong> {{-- Show the comment author --}}
                            {{ $comment->body }}
                            <p>Likes: {{ $comment->likers_count }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        {{-- Like Button --}}
                        <form action="{{ route('comment.like', ['comment' => $comment->id]) }}" method="POST" class="mr-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-thumbs-up"></i> Like
                            </button>
                        </form>
                        {{-- Unlike Button --}}
                        <form action="{{ route('comment.unlike', ['comment' => $comment->id]) }}" method="POST" class="mr-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-thumbs-down"></i> Unlike
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-secondary btn-sm report-btn" data-toggle="modal" data-target="#reportModal" data-comment-id="{{ $comment->id }}">Report</button>
                    </div>
                </li>
            @endforeach      
        </ul>
    </div>

    {{-- Comment Form --}}
    <div class="card">
        <div class="card-block">
            <form method="POST" action="{{ route('discussions.comments.store', ['discussion' => $discussion->id]) }}">
                @csrf
                <div class="form-group">
                    <textarea name="body" placeholder="Your comment here." class="form-control"></textarea>
                </div>

                {{-- Display errors or messages --}}
                @if(session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Report Comment Modal -->
<!-- Report Comment Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="POST" id="reportForm" action="{{ route('report.comment', ['comment' => $commentId]) }}"> 
                <input type="hidden" name="comment_id" value="{{ $commentId }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reason">Reason for Reporting:</label>
                        <textarea class="form-control" id="reason" name="reason" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.report-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            // Update the form's action to include the comment_id in the URL
            const formAction = `/report/comment/${commentId}`;
            document.getElementById('reportForm').setAttribute('action', formAction);
        });
    });
});
</script>

@endpush
@endsection
