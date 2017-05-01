@extends('shared.piroxsi')

@push('scripts')    
  <script type="text/javascript" src="{{ url('assets/script/resourcestatus.js') }}"></script>
@endpush

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Resource Status</h1>
      </div>
    </div>
    <div class="row clearfix">
      <div class="col-md-12 column">
        <div class="panel panel-warning">
          <div class="panel-heading">
            <h3 class="panel-title">
              <img src="{{ url($resource->countryFlag) }}" />&nbsp;&nbsp;{{ $resource->country }}
              <small class="pull-right">
                <strong>Last Refresh:</strong>&nbsp;&nbsp;<span id="lblLastUpdate_Date">None</span>
              </small>
            </h3>
          </div>                
        </div>            

        <div class="row clearfix">
          <div class="col-md-12 column">
            <div class="list-group">
              <div class="list-group-item">
                <h4 class="list-group-item-heading">Connection Status</h4>
                <p class="list-group-item-text">                  
                  <span id="lblConnectionInfo" class="label label-danger">Disconnected</span>
                </p>
              </div>
              
              <div class="list-group-item">
                <h4 class="list-group-item-heading">Network Address</h4>
                <p class="list-group-item-text">
                  <span id="lblIpInfo" class="label label-danger">None</span>
                </p>
              </div> 
                        
              <div class="list-group-item">
                <h4 class="list-group-item-heading">Server Messages</h4>
                <p class="list-group-item-text">
                  <span id="lblMessages" class="label label-success">Awaiting for action.</span>
                </p>
              </div>     
              
              <div class="list-group-item">
                <h4 class="list-group-item-heading"></h4>
                <p class="list-group-item-text">                                    
                  <button id="btnConnect" type="button" class="btn btn-primary">Connect</button>&nbsp;&nbsp;
                  <button id="btnDisconnect" type="button" class="btn btn-primary">Disconnect</button>                  
                </p>
              </div>
            </div>
          </div>
        </div>        
      </div>
    </div>
  </div>
@endsection