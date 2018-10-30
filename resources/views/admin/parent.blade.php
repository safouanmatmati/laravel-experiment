@extends('layouts.app')

@section('custom_navbar')
<ul class="nav navbar-nav">
    <li><a href="{{ route('admin.product') }}">Products</a></li>
</ul>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if(Session::has($key))
                <p class="alert alert-{{ $key }}">{{ Session::get($key) }}</p>
            @endif
            @endforeach

            <div class="panel panel-default">
                @if (false == empty($bread_crumb))
                <div class="panel-heading">
                    <ol>
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">home</a></li>
                    @php
                        $last = $bread_crumb[count($bread_crumb)-1];
                        $href = route('admin.index');
                    @endphp

                      @foreach($bread_crumb as $part)
                          @if ($last == $part)
                          <li class="breadcrumb-item active" aria-current="page">{{ $part['name'] }}</li>
                          @else
                          <li class="breadcrumb-item"><a href="{{ ($href .= "/".$part['slug']) }}">{{ $part['name'] }}</a></li>
                          @endif
                      @endforeach
                    </ol>
                </div>
                @endif

                <div class="panel-body">
                    @section('child_content')
                    @show
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
