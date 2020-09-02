@include('layouts.header')
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> Add New FAQ </a> 
    
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
         
   <a href="{{ url('admin/faq') }}"><i class="zmdi zmdi-arrow-left"></i> Back to FAQ List</a> 
          
        </div>

    <div class="card-body">

 @if(session('status'))
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> 		{{ session('status') }}
    </div>
 @endif
  @if(session('fail'))
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed!</strong> 		{{ session('fail') }}
    </div>
 @endif
          
       
    <form class="form-horizontal site-form" method="POST" action="{{ url('/admin/updatefaq') }}" autocomplete="off">

     {{ csrf_field() }}
 
      <input type="hidden" name="id" id="id" value="{{ $id }}" >
         
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> FAQ Question</label>
          <div class="div-input">
            <textarea required class="ckeditor" rows="3" cols="30" id="textarea" name="answer"> {{  $content->question }}</textarea>
            @if ($errors->has('contest_name'))
              <span class="help-block">
                  <strong>{{ $errors->first('question') }}</strong>
              </span>
            @endif
          </div>
        </div> 

          <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> FAQ Answer</label>
            <div class="div-input">
              <textarea required class="ckeditor" rows="3" cols="30" id="textarea" name="answer"> {{  $content->answer }}</textarea>
              @if ($errors->has('contest_name'))
                <span class="help-block">
                  <strong>{{ $errors->first('answer') }}</strong>
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
        <input type="submit" name="edit" class="btn btn-primary" type="button" style="margin-top:30px;" value="Save">
     </form>
     <br>
    </div>
    <br />
          </div>
         </div> 
        </div>
@include("layouts.footer")
