@extends('shop.parent')

@section('child_content')
<p>Welcome on shop!</p>

<ol>
@foreach($targets as $target)
    <li>
        <a href="{{route('shop.target', ['target' => $target->id])}}">
        {{ $target->name }}
        </a>
    </li>
@endforeach
</ol>
@endsection
