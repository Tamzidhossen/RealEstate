@extends('admin.admin_dashboard')
@section('content')
<!-- middle wrapper start -->
<div class="col-md-8 col-xl-8 middle-wrapper">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Update Property Type</h6>

                <form action="{{ route('update.type') }}" method="POST" class="forms-sample">
                    @csrf
                    <input type="hidden" name="id" value="{{ $types->id }}">
                    <div class="mb-3">
                        <label for="exampleInputUsername1" class="form-label">Type Name</label>
                        <input type="text" name="type_name" class="form-control @error('type_name') is-invalid @enderror" value="{{ $types->type_name }}">
                        @error('type_name')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputUsername1" class="form-label">Type Icon</label>
                        <input type="text" name="type_icon" class="form-control @error('type_icon') is-invalid @enderror" value="{{ $types->type_icon }}">
                        @error('type_icon')
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