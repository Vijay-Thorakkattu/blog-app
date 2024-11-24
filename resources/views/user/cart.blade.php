@extends('user.layout.layout')
@section('title', 'cart')
@section('page-title', 'cart')
@section('content')
<div class="container py-5">
    <div class="tab-class text-center">
        <div class="row g-4">
            <div class="col-lg-4 text-start">
                <h1>Our Products</h1>
            </div>
           
        </div>
   
        <div class="tab-content mt-5">
            <div class="container-fluid page-header py-5">
                <h1 class="text-center text-white display-6">Cart</h1>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active text-white">Cart</li>
                </ol>
            </div>
        </div>
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="table-responsive">
                    @if(!empty(session()->get('cart')));
                    <table class="table">
                        <thead>
                          <tr>
                            <th>SI.No</th>
                            <th scope="col">Products</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Handle</th>
                          </tr>
                        </thead>

                        <tbody>
                            @foreach (session()->get('cart') as $key => $value)

                            {{-- @php
                            dd($value);
                            @endphp --}}
                            @php $i=1; @endphp
                            <tr>
                                <td>{{$i++}}</td>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $value['image']) }}" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="">
                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4">{{ $value['name'] }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">{{ $value['price'] }}</p>
                                </td>
                                <td>
                                    <td>
                                        <div class="input-group quantity mt-4" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-minus rounded-circle bg-light border updatecart" 
                                                        data-name="{{ $value['name'] }}" data-quantity="{{ $value['quantity'] }}" data-action="subtract">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm text-center border-0" 
                                                   value="{{ $value['quantity'] }}" readonly>
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-plus rounded-circle bg-light border updatecart"
                                                        data-name="{{ $value['name'] }}" data-quantity="{{ $value['quantity'] }}" data-action="add">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">${{ $value['price'] * $value['quantity'] }}</p>
                                </td>
                                <td>
                                    <button class="btn btn-md rounded-circle bg-light border mt-4 remove-from-cart" 
                                    data-name="{{ $value['name'] }}">
                                   <i class="fa fa-times text-danger"></i>
                            </button>
                                </td>
                            
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    No item found 
                    @endif
                </div>
            </div>
        </div>
    </div> 
    
    <div class="mt-5">
        <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4" placeholder="Coupon Code">
        <button class="btn border-secondary rounded-pill px-4 py-3 text-primary" type="button">Apply Coupon</button>
    </div>
    <div class="row g-4 justify-content-end">
        <div class="col-8"></div>
        <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
            <div class="bg-light rounded">
                <div class="p-4">
                    <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="mb-0 me-4">Subtotal:</h5>
                        <p class="mb-0">$96.00</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0 me-4">Shipping</h5>
                        <div class="">
                            <p class="mb-0">Flat rate: $3.00</p>
                        </div>
                    </div>
                    <p class="mb-0 text-end">Shipping to Ukraine.</p>
                </div>
                <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                    <h5 class="mb-0 ps-4 me-4">Total</h5>
                    <p class="mb-0 pe-4">$99.00</p>
                </div>
                <button class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="button">Proceed Checkout</button>
            </div>
        </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
function addqnt(InputBox,Product) 
{  

    if(document.getElementById(InputBox).value>0)
    {   
        var qnt=eval(document.getElementById(InputBox).value)+1;     
        console.log(qnt);        
        document.getElementById(InputBox).value=qnt;  

        $.ajax({
        url: '{{ url('update-cart') }}',
        method: "patch",
        data: {_token: '{{ csrf_token() }}',id:Product,quantity:qnt },
                    success: function (response) {
                     document.getElementById('ProductTotalAmount'+Product).innerHTML=response;                    
                     total();
                    
                        }
                });        
    } 
}

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.updatecart').on('click', function() {
        var $btn = $(this);
        var name = $btn.data('name');
        console.log(name);
        var currentQty = parseInt($btn.data('quantity'));
        console.log(currentQty);
        var action = $btn.data('action');
        console.log(action);
        var newQty = currentQty;

        if (action === 'add') {
            newQty++;
        } else if (action === 'subtract' && currentQty > 1) {
            newQty--;
        }

        $btn.siblings('input').val(newQty);
        $btn.data('quantity', newQty);

        $.ajax({
            url: '/update-cart',
            type: 'PATCH', 
            dataType: 'json',
            data: {
                name: name,
                quantity: newQty
            },
            success: function(data) {
                if (data.success) {
                    $btn.closest('tr').find('.product-price').text(`$${data.newPrice}`);
                    location.reload();
                } else {
                    console.error('Failed to update cart');
                }
            }
        });
    });

       $('.remove-from-cart').on('click', function() {
        var $btn = $(this);
        var name = $btn.data('name');

        $.ajax({
            url: '/remove-from-cart',
            type: 'DELETE',
            dataType: 'json',
            data: {
                name: name
            },
            success: function(data) {
                if (data.success) {
                    $btn.closest('tr').remove();
                    location.reload();
                } else {
                    console.error('Failed to remove item from cart: ' + data.message);
                }
            }
        });
    });
});


</script>
@endsection