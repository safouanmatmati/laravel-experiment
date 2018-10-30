@extends('admin.parent')

@section('child_content')
<div>Welcome on Product backoffice !</div>

<div>
    <a class="btn btn-small btn-info pull-right"  href="{{ route('admin.product.create')}}">Add</a>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">name</th>
      <th scope="col">brand</th>
      <th scope="col">price</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
      @foreach($products as $product)
      <tr>
        <th scope="row">{{$product->id}}</th>
        <th scope="row">{{$product->name}}</th>
        <th scope="row">{{$product->brand->name}}</th>
        <th scope="row">{{$product->price}} €</th>
        <th scope="row">
            <td>
                <a class="btn btn-small btn-info" href="{{ route('admin.product.edit', ['product' => $product->id]) }}">Edit</a>

                {{ Form::open(['url' => route('admin.product.delete', ['product' => $product->id]), 'class' => 'pull-right']) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', ['class' => 'btn btn-small btn-warning']) }}
                {{ Form::close() }}
            </td>
        </th>
      </tr>
      @endforeach
  </tbody>
</table>
@endsection
