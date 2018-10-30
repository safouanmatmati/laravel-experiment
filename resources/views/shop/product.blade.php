@extends('shop.parent')

@section('child_content')
<div class="product">
    <div class="col-md-6">

        @if (false == empty($previews))
        <div id="product_carousel" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            @foreach($previews as $index => $path)
            <li data-target="#product_carousel" data-slide-to="{{$index}}" {{$index == 0 ? 'class="active"' : ''}}></li>
            @endforeach
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            @foreach($previews as $index => $path)
            <div class="item {{$index == 0 ? 'active' : ''}}">
              <img src="{{url('/uploads/'.$path)}}" alt="preview" />

              <div class="carousel-caption">
                ...
              </div>
            </div>
            @endforeach
          </div>

          <!-- Controls -->
          <a class="left carousel-control" href="#product_carousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#product_carousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        @else
            <p>Image indisponible</p>
        @endif
    </div>

     <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
               <div>
                   <h3>{{$product->name}}</h3>
                </div>
                <div>
                    <h4>{{$product->brand->name}}</h4>
                 </div>
            </div>

            <div class="col-md-6">
                <div class="price">{{$product->price}} €</div>
            </div>
        </div>
        <div class="row">

              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist" id="product_tabs">
                <li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab">Description</a></li>
                <li role="presentation"><a href="#delivery" aria-controls="delivery" role="tab" data-toggle="tab">Delivery</a></li>
                <li role="presentation"><a href="#guaranties" aria-controls="guaranties" role="tab" data-toggle="tab">Guaranties</a></li>
              </ul>

              <!-- Tab panes -->
              <div class="tab-content col-md-12">
                <div role="tabpanel" class="tab-pane active" id="description">
                    <p>{{$product->description}}</p>
                </div>
                <div role="tabpanel" class="tab-pane" id="delivery">
                    <p>Livraions sous 3 jours.</p>
                </div>
                <div role="tabpanel" class="tab-pane" id="guaranties">
                    <p>Satisfait ou remboursé.</p>
                </div>
              </div>
        </div>

        <form>
            <div class="form-group row">
              <label for="number" class="col-md-4 col-form-label">Quantity :</label>
              <div class="col-md-4">
                <input class="form-control" type="number" value="1" min="1" id="number">
              </div>
              <div class="col-md-4">
                  <button type="button" class="pull-right btn btn-secondary">Add</button>
              </div>
            </div>
        </form>
     </div>
  </div>
@endsection
