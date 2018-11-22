<div class="col-md-10 col-md-offset-1">
    @foreach($posts as $post)
        <div class="panel panel-info">
            <div class="panel-heading">
                {{ $post->title }}
            </div>
            <div class="panel-body">
                {{ $post->content }}
            </div>
            <div class="panel-footer">
            <span class="badge">
                {{ $post->created_at }}
            </span>
                <span class="badge pull-right">
                Author: {{ $post->user->name}}
            </span>
            </div>
        </div>
    @endforeach
</div>