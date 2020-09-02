@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> VIEW MATCH </a> 
    <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/users/search') }}" method="post" autocomplete="off"> 
        {{ csrf_field() }}  
          <div class="form-group mb-0">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input class="form-control fas fa-search search-btn" placeholder="Search" type="text" name="searchitem" id="searchitem" placeholder="Search for User Name or Email" required>  
<button type="submit" class="fas fa-search search-btn"></button>
            </div>
          </div>
 
        </form> 
   

    <ul class="navbar-nav align-items-center d-none d-md-flex">
      @include ('layouts.usermenu')

    </ul>
  </div>
</nav>
<div class="header stempbg bg-gradient-primary pb-8 pt-5">

  <span class="mask bg-gradient-default opacity-8"></span>
  <div class="container-fluid">
    <div class="header-body">
    </div>
  </div>
</div> 
    <div class="container-fluid mt--7"> 
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
             
       <a href="{{ url('admin/match/upcoming') }}"><i class="zmdi zmdi-arrow-left"></i> Back to Match List</a> 
              
            </div>

        <div class="card-body ">
	
    @if(session('matchsuccess'))
    <div class="alert alert-success" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> {{ session('matchsuccess') }}
    </div>
    @endif

    @if(session('matchfail'))
    <div class="alert alert-danger" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> {{ session('matchfail') }}
    </div>
    @endif

          <form method="post" action="{{ url('admin/matchUpdate/'.$match->_id) }}" >
            {{ csrf_field() }}
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Match Name </label>
              <div class="div-input">
                <input class="form-control" type = "text" required="required" name="matchname" value="{{ $match->name }}" readonly=""> 
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Short Name </label>
              <div class="div-input">
                <input class="form-control" type = "text" required="required" name="matchname" value="{{ $match->short_name }}" readonly=""> 
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Start Date </label>
              <div class="div-input">
                @php
                $dt = new DateTime($match->start_date);
                $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
                $startdate  = $dt->format('d-m-Y H:i:s');
                @endphp
                <input class="form-control" type = "text" required="required" name="matchname" value="{{ $startdate }}" readonly=""> 
              </div>
            </div>

            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Status</label>
              <div class="div-input">
                <select class="form-control" id="status" required="required"  name="status">
                  <option value="" selected>Select</option>
                  <option value="notstarted" {{ $match->status == 'notstarted' ? 'selected' :'' }} >Not started</option>
                  <option value="started" {{ $match->status == 'started' ? 'selected' :'' }}>Started</option>
                  <option value="completed" {{ $match->status == 'completed' ? 'selected' :'' }}>Completed</option>
                </select>
                @if ($errors->has('status'))
                <span class="help-block">
                  <strong>{{ $errors->first('status') }}</strong>
                </span>
                @endif
              </div>
            </div> 
            <button type="submit" class="btn btn-primary" type="button">Update</button>

          </form>
        </div>
      </div>
    </div>
  </div>
 
 

@include("layouts.footer") 


