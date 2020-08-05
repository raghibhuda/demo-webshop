@extends('layouts.master')
@section('content')
<div class="row">
    <div class="container">
        @if(!is_null($products) && count($products)>0)
        <ul class="list-group cartItems">
            @foreach($products as $key=>$value)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-item="{{$value['product_id']}}">
                {{$value['name']}}
                <span class="mr-1">Price:{{$value['price']}}</span>
                <span class="mr-1">Shipping Cost:{{$value['price'] - $value['price']*SHIPPING_CHARGE_PERCENTAGE.'$'}}</span>
                <span class="mr-1">Total Amount:{{$value['price']+$value['price']*SHIPPING_CHARGE_PERCENTAGE.'$'}}</span>
                <button class="btn btn-danger btn-sm removeFromCart" data-id="{{$value['product_id']}}">Remove</button>
            </li>
            @endforeach
        </ul>
        <div class="mt-4">
            <form action="{{route('placeOrder')}}" method="POST">
                @csrf
                <button class="btn btn-primary" type="submit">Place Order</button>
            </form>
        </div>
        @else
        <div class="alert alert-warning">No product found</div>
        @endif
    </div>
</div>
@endsection
@section('script')
<script>
    $( document ).ready(function() {
        $('#cartCounter').append("<?php echo $cartCount?>");

        $('.removeFromCart').click(function (e) {
            e.preventDefault();
            let productId = e.target.dataset.id;
            $(this).parent().remove();
            removeFromCart(productId);
        });

        function removeFromCart(productId) {
            $.ajax({
                method: 'POST',
                url:'{{route('removeFromCart')}}',
                data:{
                    product_id: productId,
                    "_token":"{{ csrf_token() }}"
                }
            }).done(function(data) {
                console.log(data);
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
                    let cartCount =  parseInt($('#cartCounter').text())-1;
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

