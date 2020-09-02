@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> USER NOTIFICATIONS </a> 
    
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
        <div class="card-body">
  
     @if(session('mail_send'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong>     {{ session('mail_send') }}
        </div>
     @endif
 
          <form method="post"  action="{{ url('admin/notify') }}">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Notification Type</label>
                <div class="div-input">
                 <input type="radio" value="1" name="ntype" >Push 
                 <input type="radio" value="2" name="ntype" >Mail
                 @if ($errors->has('ntype'))
                      <span class="help-block">
                       <strong>{{ $errors->first('ntype') }}</strong>
                   </span>
                 @endif
               </div>

              <label for="inputPassword"  class="col-sm-2 col-form-label form-control-label"> Username </label>
              <div class="div-input">
                 <select class="form-control" multiple="multiple" name="teamname[]" id="teamname" required="required">
                    @foreach ($user as $users)  
                      <option value="{{ $users->teamname }}">{{ $users->teamname }}</option> 
                    @endforeach
                  </select>
                  @if ($errors->has('teamname'))
                  <span class="help-block">
                  <strong>{{ $errors->first('teamname') }}</strong>
                       </span>
                  @endif
              </div>
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Content </label>
              <div class="div-input editor">
                    <textarea id="messageArea" name="content" class="form-control ckeditor" placeholder="Write your message.."></textarea>
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
        </div>
      </div>
  </div>
    </div>
  
@include("layouts.footer")  
