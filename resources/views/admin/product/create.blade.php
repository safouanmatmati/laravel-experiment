@extends('admin.parent')

@section('child_content')
<h1>Create a Product</h1>

<!-- if there are creation errors, they will show here -->
@if ($errors->any())
    <div class="alert alert-danger">
        {{ Html::ul($errors->all()) }}
    </div>
@endif

{{ Form::model(
    $product,
    [
        'url' => [route('admin.product.store', ['product' => $product->id])],
        'method' => 'post',
        'files' => true
    ])
}}
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('brand', 'Brand') }}
        {{ Form::select('brand', $brands, null, ['placeholder' => 'Pick a brand...','class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('tags', 'Tag') }}
        {{ Form::select(
            'tags',
            $tags,
            null,
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

    {{ Form::submit('Create the product!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@endsection
