@extends('admin.admin_dashboard')
@section('content')

<div class="row profile-body">
    <!-- left wrapper start -->
    <div class="d-none d-md-block col-md-4 col-xl-4 left-wrapper">
    <div class="card rounded">
        <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
                <img class="wd-100 rounded-circle" src="{{ (!empty($profileData->photo)) ? asset('uploads/admin_images/'.$profileData->photo) :  asset('uploads/no_image.jpg') }}" alt="profile">
                <span class="h4 ms-3">{{ $profileData->username }}</span>
            </div>
        </div>
        <div class="mt-3">
            <label class="tx-11 fw-bolder mb-0 text-uppercase">Name:</label>
            <p class="text-muted">{{ $profileData->name }}</p>
        </div>
        <div class="mt-3">
            <label class="tx-11 fw-bolder mb-0 text-uppercase">Email:</label>
            <p class="text-muted">{{ $profileData->email }}</p>
        </div>
        <div class="mt-3">
            <label class="tx-11 fw-bolder mb-0 text-uppercase">Phone:</label>
            <p class="text-muted">{{ $profileData->phone }}</p>
        </div>
        <div class="mt-3">
            <label class="tx-11 fw-bolder mb-0 text-uppercase">Address:</label>
            <p class="text-muted">{{ $profileData->address }}</p>
        </div>
        <div class="mt-3 d-flex social-links">
            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
            <i data-feather="github"></i>
            </a>
            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
            <i data-feather="twitter"></i>
            </a>
            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
            <i data-feather="instagram"></i>
            </a>
        </div>
        </div>
    </div>
    </div>
    <!-- left wrapper end -->
    <!-- middle wrapper start -->
    <div class="col-md-8 col-xl-8 middle-wrapper">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Update Admin Profile</h6>

                    <form action="{{ route('admin.profile.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Username" value="{{ $profileData->username }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Name" value="{{ $profileData->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="{{ $profileData->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" id="exampleInputEmail1" placeholder="Phone" value="{{ $profileData->phone }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="exampleInputEmail1" placeholder="Address" value="{{ $profileData->address }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                            <div class="my-2"><img class="wd-80 rounded-circle" src="{{ (!empty($profileData->photo)) ? asset('uploads/admin_images/'.$profileData->photo) :  asset('uploads/no_image.jpg') }}" id="blah" alt="" ></div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Save Chenges</button>
                        <button class="btn btn-secondary">Cancel</button>  
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- middle wrapper end -->
</div>
@endsection