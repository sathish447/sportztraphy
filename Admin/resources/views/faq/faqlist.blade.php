@include('layouts.header')
  <!-- Navbar -->
  <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">

      <!-- Brand -->
      <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">FAQ</a>
      <!-- Form -->
      <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/faq/search') }}" method="post" autocomplete="off">
      {{ csrf_field() }}  
        <div class="form-group mb-0">
          <div class="input-group input-group-alternative">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input value=" @isset($term) {{$term}} @endisset " class="form-control fas fa-search search-btn" placeholder="Search" type="text" name="searchitem" id="searchitem" placeholder="Search for User Name or Email" required>  
<button type="submit" class="fas fa-search search-btn"></button>
          </div>
        </div>
<!-- <a class="btn btn-warning btn-xs" href="{{ url('/admin/faq') }}"> Reset </a> -->
      </form> 
      <!-- User -->

      <ul class="navbar-nav align-items-center d-none d-md-flex">
        @include ('layouts.usermenu')

      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
  <!-- title end -->



  <div class="header stempbg bg-gradient-primary pb-8 pt-5">
    <span class="mask bg-gradient-default opacity-8"></span>
  </div>
  <div class="container-fluid mt--7">
    <div class="row">
      <div class="col">
        <div class="card shadow">
          <div class="card-header border-0">           
            <h3 class="mb-0">Total Questions : {{ count($contents) }}</h3> 
            <div class="text-right">
              <a href="{{ url('/admin/addnewfaq') }}" class="btn btn-sm btn-primary"><i class="fa fa-arrow-right"></i> Add FAQ</a> 
              </div>            
          </div>
          
          <div class="table-responsive">
          <div id="msg"></div>
        <table class="table align-items-center table-flush" id="allusers-table" >
          <thead>
            <tr>
              <th>S.No</th>
              <th> FAQ Questions </th>
              <th> FAQ Answers </th>
              <th> Created At </th>
              <th>Action</th>
            </tr>
          </thead> 
           <tbody>
                @php
                $i=1; 
                @endphp

             @if (count($contents) > 0)
          
              @foreach ($contents as $content)

                <tr>
                <td>{{ $i }}</td>
                <td>{{ strip_tags($content->question) }}</td>
                <td> 
                  <?php 
                  if($content->answer!= ""){
                    echo mb_strimwidth($content->answer, 0, 100, "...");
                    }
                  ?>
                    
                  </td>
                <td>{{ $content->created_at }}</td>
              
                <td class="txt-left">
                  <a href="{{ url('admin/faq/'.$content->_id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>                
                </td>
                </tr>
                @php
                $i++;
                @endphp
              @endforeach

          @else
            <tr><td colspan="5">No News Found</td></tr>

          @endif
          </tbody>
          
 
          </table>

          {{ $contents->render() }}
          
        </div>
      </div>
      </div>
      </div>
  @include("layouts.footer")  

