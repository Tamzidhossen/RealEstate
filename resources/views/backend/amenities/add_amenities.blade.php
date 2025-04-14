@extends('admin.admin_dashboard')
@section('content')
<!-- middle wrapper start -->
<div class="col-md-8 col-xl-8 middle-wrapper">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Add Amenities</h6>

                <form action="{{ route('store.type') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputUsername1" class="form-label">Amenities Name</label>
                        <input type="text" name="amenities_name" class="form-control @error('amenities_name') is-invalid @enderror">
                        @error('amenities_name')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Save Chenges</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- middle wrapper end -->
@endsection