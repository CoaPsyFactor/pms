@extends('base')

@section('content')
    <ul class="list-group">
        @foreach($categories as $category)
            <li class="list-group-item">
                <a href="{{ route('blogCategory', ['categoryId' => $category->id, 'page' => null, 'limit' => null, 'lang' => app()->getLocale()])}}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection