@extends('layouts.app')

@section('basket')
<ul class="nav navbar-nav navbar-right">
    <li>
        <a href="#">
        Basket <small>(1 article)</small>
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offtag-1">
            <div class="panel panel-default">
                @if (false == empty($bread_crumb))
                <div class="panel-heading">
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Home</a></li>

                        @php
                            $last = $bread_crumb[count($bread_crumb)-1];
                            $href = route('shop.index');
                        @endphp

                        @foreach($bread_crumb as $part)
                            @if ($last == $part)
                            <li class="breadcrumb-item active" aria-current="page">{{ $part->name }}</li>
                            @else
                            <li class="breadcrumb-item"><a href="{{ ($href .= "/".$part->id) }}">{{ $part->name  }}</a></li>
                            @endif
                        @endforeach
                      </ol>
                    </nav>
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
