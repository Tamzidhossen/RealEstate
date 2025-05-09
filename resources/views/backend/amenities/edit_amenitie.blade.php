@extends('admin.admin_dashboard')
@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- middle wrapper start -->
<div class="col-md-8 col-xl-8 middle-wrapper">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Edit Amenities</h6>

                <form id="myForm" action="{{ route('update.amenitie') }}" method="POST" class="forms-sample">
                    @csrf

                    <input type="hidden" name="id" value="{{ $amenities->id }}">

                    <div class="form-group mb-3">
                        <label for="exampleInputUsername1" class="form-label">Amenities Name</label>
                        <input type="text" name="amenities_name" class="form-control" value="{{ $amenities->amenities_name }}">
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Save Chenges</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- middle wrapper end -->


<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                amenities_name: {
                    required : true,
                }, 
                
            },
            messages :{
                amenities_name: {
                    required : 'Please Enter Amenities Name',
                }, 
                 

            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>
@endsection