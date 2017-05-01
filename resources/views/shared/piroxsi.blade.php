<!DOCTYPE html> 
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Piroxsi</title>
    <link href="{{ url('assets/style/bootstrap.min.css') }}" type="text/css" rel="stylesheet" />
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Piroxsi</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a id="lnkRefreshResources" href="#">Refresh Resources</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="row">
        <div class="col-md-12"><br/><br/><br /></div>
      </div>
    </div>
    
    @yield('content')

    <div id="modalRefresh" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalRefreshLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalRefreshLabel">Piroxsi</h5>
          </div>
          <div class="modal-body">
            <p id="lblModalMessage">We are now refreshing the resource list</p>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="{{ url('assets/script/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/script/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/script/piroxsi.js') }}"></script>
    <script type="text/javascript">window.piroxsi.config={!! $config !!};</script>
    @stack('scripts')
  </body>
</html>
