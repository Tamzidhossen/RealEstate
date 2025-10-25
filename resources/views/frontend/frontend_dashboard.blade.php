
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Easy RealEstate</title>

<!-- Fav Icon -->
<link rel="icon" href="{{ asset('frontend/assets') }}/images/favicon.ico" type="image/x-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Stylesheets -->
<link href="{{ asset('frontend/assets') }}/css/font-awesome-all.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/flaticon.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/owl.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/bootstrap.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/jquery.fancybox.min.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/animate.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/jquery-ui.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/nice-select.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/color/theme-color.css" id="jssDefault" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/switcher-style.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/style.css" rel="stylesheet">
<link href="{{ asset('frontend/assets') }}/css/responsive.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >

</head>


<!-- page wrapper -->
<body>

    <div class="boxed_wrapper">

        <!-- preloader -->
        @include('frontend.home.preload')
        <!-- preloader end -->

        <!-- switcher menu -->
        <!-- end switcher menu -->

        <!-- main header -->
        @include('frontend.home.header')
        <!-- main-header end -->

        <!-- Mobile Menu  -->
        @include('frontend.home.mobile_menu')
        <!-- End Mobile Menu -->


        @yield('content')


        <!-- main-footer -->
        @include('frontend.home.footer')
        <!-- main-footer end -->



        <!--Scroll to top-->
        <button class="scroll-top scroll-to-target" data-target="html">
            <span class="fal fa-angle-up"></span>
        </button>
    </div>


    <!-- jequery plugins -->
    <script src="{{ asset('frontend/assets') }}/js/jquery.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/popper.min.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/owl.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/wow.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/validation.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/jquery.fancybox.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/appear.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/scrollbar.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/isotope.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/jquery.nice-select.min.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/jQuery.style.switcher.min.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/jquery-ui.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/nav-tool.js"></script>

    <!-- map script -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-CE0deH3Jhj6GN4YvdCFZS7DpbXexzGU"></script>
    <script src="{{ asset('frontend/assets') }}/js/gmaps.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/map-helper.js"></script>
    {{-- End map Script --}}

    <!-- main-js -->
    <script src="{{ asset('frontend/assets') }}/js/script.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script>
		@if(Session::has('message'))
			var type = "{{ Session::get('alert-type','info') }}"
			switch(type){
				case 'info':
				toastr.info(" {{ Session::get('message') }} ");
				break;
				case 'success':
				toastr.success(" {{ Session::get('message') }} ");
				break;
				case 'warning':
				toastr.warning(" {{ Session::get('message') }} ");
				break;
				case 'error':
				toastr.error(" {{ Session::get('message') }} ");
				break; 
			}
		@endif 
	</script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    {{-- Start Add to Wishlist data --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        //Add to Wishlist
        function AddToWishlist(property_id){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/add-to-wishlist/"+property_id,
                success:function(data){
                    // Start Message
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        
                        showConfirmButton: false,
                        timer: 3000 
                    })
                    if ($.isEmptyObject(data.error)) {
                            
                            Toast.fire({
                            type: 'success',
                            icon: 'success', 
                            title: data.success, 
                            })

                    }else{
                    
                    Toast.fire({
                        type: 'error',
                        icon: 'error', 
                        title: data.error, 
                        })
                    }
                    // End Message
                }
            });
        }
    </script>
    {{-- End Add to Wishlist data --}}

    {{-- Start load wishlist data  --}}
    <script type="text/javascript">
        //Load Wishlist Data
        const basePath = "{{ asset('uploads/property/thambnail') }}";
        function WishlistLoad(){
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/get-wishlist-property/',

                success:function(response){
                    $('#wishQty').text(response.wishQty);

                    let rows = "";
                    $.each(response.wishlists, function(key,value){
                        rows += `
            <div class="deals-block-one">
                <div class="inner-box">
                    <div class="image-box">
                        <figure class="image"><img src="${basePath}/${ value.property.property_thambnail }" alt=""></figure>
                        <div class="batch"><i class="icon-11"></i></div>
                        <span class="category">Featured</span>
                        <div class="buy-btn"><a href="property-details.html">For ${ value.property.property_status}</a></div>
                    </div>
                    <div class="lower-content">
                        <div class="title-text"><h4><a href="property-details.html">${ value.property.property_name}</a></h4></div>
                        <div class="price-box clearfix">
                            <div class="price-info pull-left">
                                <h6>Start From</h6>
                                <h4>$${ value.property.lowest_price}</h4>
                            </div>
                        </div>
                        <ul class="more-details clearfix">
                            <li><i class="icon-14"></i>${ value.property.bedrooms} Beds</li>
                            <li><i class="icon-15"></i>${ value.property.bathrooms} Baths</li>
                            <li><i class="icon-16"></i>${ value.property.property_size} Sq Ft</li>
                        </ul>
                        <div class="other-info-box clearfix">
                            <ul class="other-option pull-right clearfix">
                                <li><a type="submit" class="text-body" id="${ value.id }" onclick="RemoveToWishlist(this.id)" ><i class="fa fa-trash"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> `;

                    });
                    // console.log(rows);
                    $('#wishlist').html(rows);
                }
            });
        }
        WishlistLoad();

         //Remove to Wishlist
        function RemoveToWishlist(id){
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/remove-to-wishlist/"+id,
                success:function(data){
                    WishlistLoad();
                    // Start Message
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        
                        showConfirmButton: false,
                        timer: 3000 
                    })
                    if ($.isEmptyObject(data.error)) {
                            
                            Toast.fire({
                            type: 'success',
                            icon: 'success', 
                            title: data.success, 
                            })

                    }else{
                    
                    Toast.fire({
                        type: 'error',
                        icon: 'error', 
                        title: data.error, 
                        })
                    }
                    // End Message
                }
            });
        }
    </script>
    {{-- End load wishlist data  --}}

    {{-- Start Add to Compare data --}}
    <script type="text/javascript">
        //Add to Compare
        function AddToCompare(property_id){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/add-to-compare/"+property_id,
                success:function(data){
                    // Start Message
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        
                        showConfirmButton: false,
                        timer: 3000 
                    })
                    if ($.isEmptyObject(data.error)) {
                            
                            Toast.fire({
                            type: 'success',
                            icon: 'success', 
                            title: data.success, 
                            })

                    }else{
                    
                    Toast.fire({
                        type: 'error',
                        icon: 'error', 
                        title: data.error, 
                        })
                    }
                    // End Message
                }
            });
        }
    </script>
    {{-- End Add to Compare data --}}

    {{-- Start load Compare data  --}}
    <script type="text/javascript">
        //Load Wishlist Data
        const img = "{{ asset('uploads/property/thambnail') }}";
        function compare(){
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/get-compare-property/',

                success:function(response){

                    let rows = "";
                    $.each(response, function(key,value){
                        rows += `<tr>
                        <th>Property Info</th>
                        <th>
                            <figure class="image-box"><img src="${img}/${ value.property.property_thambnail }" alt=""></figure>
                            <div class="title">${ value.property.property_name }</div>
                            <div class="price">$${ value.property.lowest_price }</div>
                        </th>
                    </tr>  
                    <tr>
                        <td>
                            <p>City</p>
                        </td>
                        <td>
                            <p>${ value.property.city }</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Area</p>
                        </td>
                        <td>
                            <p>${ value.property.property_size } Ft</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Rooms</p>
                        </td>
                        <td>
                            <p>${ value.property.bedrooms }</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Bathrooms</p>
                        </td>
                        <td>
                            <p>${ value.property.bathrooms }</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Garage</p>
                        </td>
                        <td>
                            <p>${ value.property.garage }</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Action</p>
                        </td>
                        <td>
                            <p><a type="submit" class="text-body" id="${ value.id }" onclick="RemoveCompare(this.id)" ><i class="fa fa-trash"></i></a></p>
                        </td>
                    </tr> `;

                    });
                    // console.log(rows);
                    $('#compare').html(rows);
                }
            });
        }
        compare();

        //Remove to Compare
        function RemoveCompare(id){
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/remove-compare/"+id,
                success:function(data){
                    compare();
                    // Start Message
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        
                        showConfirmButton: false,
                        timer: 3000 
                    })
                    if ($.isEmptyObject(data.error)) {
                            
                            Toast.fire({
                            type: 'success',
                            icon: 'success', 
                            title: data.success, 
                            })

                    }else{
                    
                    Toast.fire({
                        type: 'error',
                        icon: 'error', 
                        title: data.error, 
                        })
                    }
                    // End Message
                }
            });
        }
    </script>
    {{-- End load wishlist data  --}}

</body><!-- End of .page_wrapper -->
</html>
