@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> VIEW PLAYER DETAILS </a> 
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
             
       <a href="{{ url('admin/playerList') }}"><i class="zmdi zmdi-arrow-left"></i> Back to Players List</a> 
              
            </div>

        <div class="card-body ">
	
	@if(session('playersuccess'))
		<div class="alert alert-success" role="alert">
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> {{ session('playersuccess') }}
		 </div>
	@endif
	 @if(session('playerfail'))
	    <div class="alert alert-danger" role="alert">
	   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed!</strong> {{ session('playerfail') }}
	   </div>
	@endif
          <form method="post"  action="{{ url('admin/playerUpdate/'.$match->_id) }}" enctype="multipart/form-data" autocomplete="off">
            {{ csrf_field() }}
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Player Name </label>
              <div class="div-input">
                <input class="form-control" type = "text" required="required" name="name" value="{{ $match->name }}" > 
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Batting Style </label>
              <div class="div-input">
                <input class="form-control" type = "text" required="required" name="batting_style" value="{{ $match->batting_style }}" > 
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Bowling Style</label>
              <div class="div-input">
               <input class="form-control" type = "text" required="required" name="bowling_style" value="{{ $match->bowling_style }}" >  
              </div>
            </div>

            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Role</label>
              <div class="div-input">
                <select class="form-control" id="role" required="required"  name="role">
		<option value="" selected>Select</option>
		<option value="bowler" {{ $match->role == 'bowler' ? 'selected' :'' }} >bowler</option>
		<option value="all rounder" {{ $match->role == 'all rounder' ? 'selected' :'' }}>all rounder</option>
		<option value="batsman" {{ $match->role == 'batsman' ? 'selected' :'' }}>batsman</option>
		<option value="keeper" {{ $match->role == 'keeper' ? 'selected' :'' }}>keeper</option>
		</select> 
                @if ($errors->has('role'))
                <span class="help-block">
                  <strong>{{ $errors->first('role') }}</strong>
                </span>
                @endif
              </div>
            </div> 

 	<div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Upload Profile Image</label>
              <div class="div-input">
                @if($match->profile != '')
		  <img id="doc1" class="img-responsive" src="{{ $match->profile }}" width="200" height="200">
		 @else
		 <img id="doc1" class="img-responsive">
		 @endif
		  
		  <label for="profilefile-upload1" class="custom-file-upload customupload">
		   <i class="fa fa-cloud-upload"></i> Upload Image </label>
		  <input id="profilefile-upload1" name="profile" class="profile" type="file" style="display:none;"> 
                      @if ($errors->has('profile'))
                            <span class="{{ $errors->has('profile') ? ' has-error' : '' }} help-block hide-text">
                                <strong>{{ $errors->first('profile') }}</strong>
                            </span>
                      @endif
                      <strong><span class="front text-danger help-block"> </span></strong>
	       <label id="profile-name" class="customupload1"></label>	
              </div>
            </div>

            <button type="submit" class="btn btn-primary" type="button">Update</button>

          </form>
        </div>
      </div>
    </div>
  </div> 

@include("layouts.footer") 

