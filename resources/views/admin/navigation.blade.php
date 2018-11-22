@extends('admin')

@section('sidebarTitle')
@endsection

@section('sidebarContent')
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">

    </script>
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ trans('admin.panelTitle') }} : {{ trans('admin.navigation.navigation') }}
        </div>
        <div class="panel-body">
            <ul class="list-group">
                @foreach($navigationLinks as $navigationLink)
                    <li class="list-group-item">
                        {{ implode(' > ', $navigationLink->path) }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection