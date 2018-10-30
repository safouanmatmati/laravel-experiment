@extends('shop.parent')

@section('child_content')
<label>tags :</label>
<ol>
@foreach($target->tags as $tag)
    <li>
        <a href="{{route('shop.tag', ['target' => $target->id, 'tag' => $tag->id])}}">
        {{ $tag->name }}
        </a>
    </li>
@endforeach
</ol>
@endsection
