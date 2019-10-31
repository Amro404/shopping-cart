@extends("layouts.app")

@section("content")
	<div class="container">
		@if(session()->has("success"))
			<div class="alert alert-success">{{ session()->get("success") }}</div>
		@endif
		<section>
			<div class="row">

				@foreach($products as $product)
				<div class="col-md-4">
					<div class="card mb-2" style="width: 18rem;">
					  <img class="card-img-top" src="{{ $product->image }}" alt="Card image cap">
					  <div class="card-body">
					    <h5 class="card-title">{{ $product->title }}</h5>
					    <p class="card-text">$ {{ $product->price }}</p>
					    <a href="{{ route('cart.add', ['product' => $product->id]) }}" class="btn btn-primary">Buy</a>
					  </div>
					</div>
				</div>

				@endforeach

			</div>
		</section>

		{{ $products->links() }}

	</div>
@endsection