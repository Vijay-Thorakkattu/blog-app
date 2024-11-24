@extends('user.layout.layout')
@section('title', 'Products')
@section('page-title', 'Prosducts')
@section('content')
<div class="container py-5">
    <div class="tab-class text-center">
        <div class="row g-4">
            <div class="col-lg-4 text-start">
                <h1>Our Products</h1>
            </div>
           
        </div>
   
        <div class="tab-content mt-5">
            <div id="tab-1" class="tab-pane fade show p-0 active">
              
                <div class="row g-4">
                   
                    <div class="col-lg-12">
                        
                        <div class="row g-4">
                            @foreach($products as $product)
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="rounded position-relative fruite-item">
                                    <div class="fruite-img">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">{{ $product->name }}</div>
                                    <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                        <h4>{{ $product->name }}</h4>
                                        <p>{{ $product->description }}</p>
                                        <div class="d-flex justify-content-between flex-lg-wrap">
                                            <p class="text-dark fs-5 fw-bold mb-0">${{ $product->price }}</p>
                                            <a href="#"  onclick="add_to_cart({{$product->id}})" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>      
</div>

<script>
    function add_to_cart(productID){
    
        $.get('/add-to-cart/'+productID, function(data){ 
            document.getElementById("spanCartCount").innerHTML=data;
            location.reload();
            // cartcount();

                });
    
    }

    // function cartcount(){    
    //     $.ajax({
    //     url: '{{ url('cartcount') }}',
    //     method: "get",
    //     data: {_token: '{{ csrf_token() }}'},
    //                 success: function (response) {                    
    //                     document.getElementById("spanCartCount").innerHTML=response; 
                        
    //                                 }
    //             });  
    // }



</script>
@endsection