@extends('shared.piroxsi')

@push('scripts')
  <script type="text/javascript" src="{{ url('assets/script/resourcelist.js') }}"></script>
@endpush

@section('content') 
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-lg-12"> 
        <table class="table table-striped" width="100%">
          <thead>
            <tr>
              <th></th>
              <th>Sessions</th>
              <th>Uptime</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @if (count($resources) > 0)
              @foreach ($resources as $resource)
                <tr>
                  <td><img src="{{ url($resource->countryFlag) }}" /></td>
                  <td>{{ $resource->sessions }}</td>
                  <td>{{ $resource->uptime }}</td>
                  <td>
                    <button type="button" class="btn btn-primary controlButton" data-id="{{ $resource->id }}">Control</button>                  
                  </td>
                </tr>
              @endforeach
            @else            
              <tr>
                <td colspan="4" class="text-center">There are no resources available</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection