@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> {{isset($bonus) ? 'UPDATE BONUS' : 'CREATE BONUS'}} </a> 
    
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
             
       <a href="{{ url('admin/bonus') }}"><i class="zmdi zmdi-arrow-left"></i> Back to Bonus List</a> 
              
            </div>

        <div class="card-body">
	
     @if(session('bonus'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> 		{{ session('bonus') }}
        </div>
     @endif
 
          <form method="post"  action="{{ url('admin/create_bonus') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{isset($bonus) ? $bonus->_id : ''}}" name="">
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Referal Bonus</label>
              <div class="div-input">
                <input class="form-control" type = "number" required="required" name="referal_bonus" value="{{isset($bonus) ? $bonus->referalbonus : ''}}">
	  			@if ($errors->has('referal_bonus'))
                	<span class="help-block">
                        <strong>{{ $errors->first('referal_bonus') }}</strong>
                    </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Contest Join Bonus(%) </label>
              <div class="div-input">
               <input class="form-control" type = "number" id="joined_bonus" required="required" name="joined_bonus" value="{{isset($bonus) ? $bonus->joinedbonus : ''}}">
				@if ($errors->has('joined_bonus'))
					<span class="help-block">
						<strong>{{ $errors->first('joined_bonus') }}</strong>
					</span>
				@endif
              </div>
            </div>

		<div id="ch" class="row">
			<div class="pricerangeboxbg"> 
				<div class="col-lg-7 col-md-10 col-10 price-box"></div>
				<div class="col-lg-5 col-md-2 col-2 add_rannge_btn"></div>
			</div>
		</div>
	 
    	<input type="submit" name="edit" class="btn btn-primary" type="button" style="margin-top:30px;" value="{{isset($bonus) ? 'Update' : 'Save'}}">
 		 </form>
        </div>
      </div>
  </div>
    </div>
  
@include("layouts.footer")  
