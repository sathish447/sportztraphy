@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> MY PROFILE </a> 
     
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
                   
            </div>

        <div class="card-body ">
	
  @if(session('status'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        {{ session('status') }}
        </div>
  @endif

          <form method="post"  action="{{ url('admin/changeusername') }}" autocomplete="off" >
            {{ csrf_field() }}
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Current Password</label>
              <div class="div-input">
                <input type="password" name="oldpassword"  placeholder="Current Password" id="site_title" class="form-control" value="" required="">
              <strong class="text-danger">{{ $errors->first('oldpassword') }}</strong>
              <i class="form-group__bar"></i>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">New Password</label>
              <div class="div-input">
                 <input type="password" name="newpassword"  placeholder="New Password" class="form-control" value="" required="">
              <strong class="text-danger">{{ $errors->first('newpassword') }}</strong>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Confirm New Password</label>
              <div class="div-input">
                 <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password"  value="" >
              <strong class="text-danger">{{ $errors->first('confirmpassword') }}</strong>
              </div>
            </div>

         	 <input type="hidden" name="token" class="form-control" value="" placeholder="" required="">
            <button  type="submit" name="change_password" class="btn btn-primary" type="button">Change Password</button>

          </form>
        </div>
      </div>
    </div>
  </div>
 
  <!-- Footer --> 
 @include("layouts.footer")  
   
