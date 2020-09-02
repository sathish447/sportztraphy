@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">view withdraw settings</a> 
     
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

          <form method="post"  action="{{ url('admin/withdrawcommissionupdate') }}" autocomplete="off" >
            {{ csrf_field() }}
<input type="hidden" value="{{ $commission->_id }}" name="id">
				
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Withdraw Limit</label>
              <div class="div-input">
                <input type="number" name="withdraw_limit" class="form-control"  step="any" min="0" max="10000000" value="{{ $commission->withdraw_limit != NULL ? $commission->withdraw_limit : '0' }}"/><i class="form-group__bar"></i>
		@if ($errors->has('withdraw_limit'))
                    <span class="help-block">
                        <strong>{{ $errors->first('withdraw_limit') }}</strong>
                    </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Minimum Withdraw Limit</label>
              <div class="div-input">
                <input type="number" name="withdraw_minimum"  step="any" min="0" max="10000000" class="form-control" value="{{ $commission->withdraw_minimum != NULL ? $commission->withdraw_minimum : '0' }}"/><i class="form-group__bar"></i>
		@if ($errors->has('withdraw_minimum'))
                    <span class="help-block">
                        <strong>{{ $errors->first('withdraw_minimum') }}</strong>
                    </span>
        	@endif
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Maximum Withdraw Limit</label>
              <div class="div-input">
                 <input type="number" name="withdraw_maximum"  step="any" min="0" max="10000000" class="form-control" value="{{ $commission->withdraw_maximum != NULL ? $commission->withdraw_maximum : 0 }}"/><i class="form-group__bar"></i>
	        @if ($errors->has('withdraw_maximum'))
                    <span class="help-block">
                        <strong>{{ $errors->first('withdraw_maximum') }}</strong>
                    </span>
        	@endif
              </div>
            </div> 
            <button type="submit" name="update" class="btn btn-primary" type="button">Update</button>

          </form>
        </div>
      </div>
    </div>
  </div>
 
  <!-- Footer --> 
 @include("layouts.footer")  
   
