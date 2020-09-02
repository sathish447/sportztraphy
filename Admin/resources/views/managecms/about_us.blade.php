@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> UPDATE ABOUT US CONTENT </a> 
    
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

        <div class="card-body">
        @if(session('status'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong>       {{ session('status') }}
        </div>
        @endif
          <form method="post" action="{{url('admin/create_about_us')}}">
            {{ csrf_field() }}
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Content </label>
              <div class="div-input editor">
               <input type="hidden" name="type" value="aboutus">
                    <textarea id="messageArea" name="content" class="form-control ckeditor" placeholder="Write your message..">{{@$aboutus->content}}</textarea>
              </div>
            </div>
     
        <input type="submit" name="save" class="btn btn-primary" type="button" style="margin-top:30px;" value="Update">
         </form>
        </div>
      </div>
  </div>
    </div>
  
@include("layouts.footer")  
<script type="text/javascript">
     CKEDITOR.replace( 'messageArea',
     {
      customConfig : 'config.js',
      toolbar : 'simple'
      })
</script> 