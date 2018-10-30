@extends('admin.parent')

@section('child_content')
<h1>Edit a Product</h1>

<!-- if there are creation errors, they will show here -->
@if ($errors->any())
    <div class="alert alert-danger">
        {{ Html::ul($errors->all()) }}
    </div>
@endif

{{ Form::model(
    $product,
    [
        'url' => [route('admin.product.update', ['product' => $product->id])],
        'method' => 'patch',
        'files' => true
    ])
}}
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('brand', 'Brand') }}
        {{ Form::select('brand', $brands, $product->brand->id, ['placeholder' => 'Pick a brand...','class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('tags', 'Tag') }}
        {{ Form::select(
            'tags',
            $all_tags,
            $tags,
            [
                'placeholder' => 'Pick a tag...',
                'class' => 'form-control',
                'multiple' => true,
                'name'=>'tags[]'
            ]
        ) }}
    </div>

    <div class="form-group">
        {{ Form::label('price', 'Price') }}
        {{ Form::number('price', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::textarea('description', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('previews', 'Previews') }}
        {{ Form::file('previews', ['name'=>'previews[]']) }}
        {{ Form::file('previews', ['name'=>'previews[]']) }}
        {{ Form::file('previews', ['name'=>'previews[]']) }}
    </div>

    {{ Form::submit('Edit the product!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@if (false == empty($previews))
    @foreach($previews as $path => $file_name)

    <div class="preview_form">
    {{ Form::open(['url' => route('admin.product.preview.delete', [
        'product' => $product->id,
        'preview' => $file_name
    ]), 'class' => 'pull-right']) }}
        <div class="form-group">
            <div>
                <img src="{{url('/uploads/'.$path)}}" alt="preview" class="img-thumbnail">
            </div>
            {{ Form::hidden('_method', 'DELETE') }}
            {{ Form::submit('Remove', ['class' => 'btn btn-small btn-warning']) }}
        </div>
    {{ Form::close() }}
    </div>

    @endforeach
@endif

@endsection
