@extends('layouts.master')
@section('content')
    <div id="result"></div>
    <div class="row text-center">
        @if(!is_null($products) && count($products)>0)
            @foreach($products as $key=>$value)
                <div class="card mr-3 mt-2">
                    <div style="width: 18rem;">
                        <img class="card-img-top" src="{{asset(('assets/images/macbook.jpeg'))}}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">{{$value['name']}}</h5>
                            <p class="card-text">Price : {{$value['price'].'$'}}</p>
                            <p class="card-text">Shipping Cost : {{$value['price'] - $value['price']*SHIPPING_CHARGE_PERCENTAGE.'$'}}</p>
                            <a href="#" class="btn btn-primary addToCart" data-id="{{$value['id']}}">Add to Cart</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="container">
                <div class="alert alert-warning">No product found</div>
            </div>
        @endif
    </div>
@endsection
@section('script')
    <script>
        // A $( document ).ready() block.
        $( document ).ready(function() {
            $('#cartCounter').append("<?php echo $cartCount?>");

            $('.addToCart').click(function (e) {
                e.preventDefault();
                let productId = e.target.dataset.id;
                addToCart(productId);

                console.log(e.target.dataset.id,'==')
            });

            function addToCart(productId) {
                $.ajax({
                    method: 'POST',
                    url:'{{route('addToCart')}}',
                    data:{
                        product_id: productId,
                        "_token":"{{ csrf_token() }}"
                    }
                }).done(function(data) {
                    if(data.success){
                        $("#result").html('<div class="alert alert-success"><button type="button" class="close">×</button>'+data.message+'</div>');
                        window.setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                                $(this).remove();
                            });
                        }, 5000);
                        $('.alert .close').on("click", function(e){
                            $(this).parent().fadeTo(500, 0).slideUp(500);
                        });
                        let cartCount =  parseInt($('#cartCounter').text())+1;
                        $('#cartCounter').text(cartCount);
                    }else{
                        $("#result").html('<div class="alert alert-danger"><button type="button" class="close">×</button>'+data.message+'</div>');
                        window.setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                                $(this).remove();
                            });
                        }, 5000);
                        $('.alert .close').on("click", function(e){
                            $(this).parent().fadeTo(500, 0).slideUp(500);
                        });
                    }
                    console.log(data);
                }).fail(function(error) {
                    console.log(error);
                });
            }

        });
    </script>
@endsection
