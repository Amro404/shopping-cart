@extends("layouts.app")

@section("content")
	<div class="container">
		<div class="row">
			
			@if($cart)
				<div class="col-md-8">
					@foreach($cart->items as $id => $product)
						<div class="card md-2">
							<div class="card-body">
								<h5 class="card-title">{{ $product['title'] }}</h5>
								<div class="card-text">
									${{ $product['price'] }}
									
			                       	<input type="number" value="{{ $product['qty'] }}" class="quantity" />
									<a href="" data-id="{{ $id }}" class="btn btn-secondary btn-sm ml-2 update-cart">Change</a>
									<a href="" data-id="{{ $id }}" class="btn btn-danger btn-sm remove-from-cart">Remove</a>

								</div>
							</div>
						</div>
					@endforeach

						<p><strong>Total: ${{ $cart->totalPrice }}</strong></p>
				</div>
				<div class="col-md-4">
					<div class="card bg-primary text-white">
		                	<div class="card-body">
		                    <h3 class="card-titel">
		                        Your Cart
		                        <hr>    
		                    </h3>
		                    <div class="card-text">
		                        <p>
		                        Total Amount is ${{$cart->totalPrice}}
		                        </p>
		                        <p>
		                        Total Quantities is {{ $cart->totalQty }}
		                        </p>
		                        <a href="{{ route('cart.checkout', ['amount' => $cart->totalPrice]) }}" class="btn btn-info">Chekout</a>
		                    </div>
		                </div>
	            	</div>
				</div>
			@else
				<p>There is no items in the cart</p>
			@endif
		</div>
	</div>
@endsection
	
@section('scripts')
	   <script type="text/javascript">
 
        $(".update-cart").click(function (e) {


           e.preventDefault();
 
           var ele = $(this);
 			
            $.ajax({
               url: '{{ url('update-cart') }}',
               method: "patch",
               data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), quantity: $(".quantity").val()},
               success: function (response) {
                   window.location.reload();
               }
            });
        });
 
        $(".remove-from-cart").click(function (e) {
            e.preventDefault();
 
            var ele = $(this);
 	
            if(confirm("Are you sure")) {
                $.ajax({
                    url: '{{ url('remove-from-cart') }}',
                    method: "DELETE",
                    data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id")},
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        });
 
    </script>
@endsection