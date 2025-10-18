@extends('agent.agent_dashboard')
@section('agent')
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title">All Package History</h6>

          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Image</th>
                  <th>Package Name</th>
                  <th>Package Price</th>
                  <th>Invoice</th>
                  <th>Package Credit</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($getData as $key => $item)
                <tr>
                  <td>{{ $key+1 }}</td>
                  <td><img src="{{ (!empty($item->rel_to_user->photo)) ? asset('uploads/agent_images/'.$item->rel_to_user->photo) :  asset('uploads/no_image.jpg') }}"></td>
                  <td>{{ $item->package_name }}</td>
                  <td>{{ $item->package_price }}</td>
                  <td>{{ $item->invoice }}</td>
                  <td>{{ $item->package_credits }}</td>
                  <td>{{ $item->created_at->format('l d M Y') }}</td>
                  <td>
                    <a href="{{ route('agent.package.invoice', $item->id) }}" class="btn btn-inverse-info" title="Details"> <i data-feather="download"></i> </a>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection