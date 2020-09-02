@include('layouts.header')

<section class="content">
  <header class="content__title">
    <h1> Add New Content </h1>

  </header>
  
   <div class="card">
    <div class="card-body">

 <div id="content-wrapper">
      <div class="mui--appbar-height"></div>
      <div class="mui-container-fluid">
   
  <div class="flex-box content-section-whl"> 
   <div class="col-md-12 col-sm-12 col-xs-12 boxt">
    <div class="mui-panel">
        <div class="division-box">
          <div class="col-md-12 col-sm-12 col-xs-12 boxts">
             <!-- col-md-12 col-sm-12 col-xs-12 boxts -->
         <div class="col-md-12 col-sm-12 col-xs-12 boxt">

          @if ($message = Session::get('success'))
          <div class="alert alert-success alert-block">
          <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
          </div>
          @endif 

          @if ($message = Session::get('error'))
          <div class="alert alert-warning alert-block">
          <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
          </div>
          @endif  
       
    <form class="form-horizontal site-form" method="POST" action="{{ url('/setting/savecontent') }}" autocomplete="off">

     {{ csrf_field() }}
 
        <div class="row">
        <div class="col-xs-8 col-sm-8 col-md-8">
        <!-- <div class="loding">Loading...</div> -->
            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
             Title 
             <input type="text" name="title" id="title" value="{{ old('title') }}" required class="form-control input-sm" placeholder="Name"> 
                 
            </div>
        </div>
        </div> 
        <div class="row">
        <div class="col-xs-8 col-sm-8 col-md-8">
        <!-- <div class="loding">Loading...</div> -->
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
             Description
             <textarea required class="ckeditor" rows="10" id="textarea" name="description"> {{ old('description') }}</textarea>  
            </div>
        </div>
        </div> 

    <div align="left"><input type="submit" id="submit" value="Update" class="btn btn-info site-btn  site-blue-btn" /></div> 
     </form>
    <br>
   </div>
   </br>
         </div>
        </div> 
       </div>
      </div>
  </div>
</div>

</div>

</div>
</section>
@include("layouts.footer")