    @include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> Match LIST</a>
        <!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto {{ $status == 'upcoming' ? 'active' : '' }}" action="{{ url('/admin/match/search/upcoming') }}" method="post" autocomplete="off">
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto {{ $status == 'live' ? 'active' : '' }}" action="{{ url('/admin/match/search/live') }}" method="post" autocomplete="off">
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto {{ $status == 'completed' ? 'active' : '' }}" action="{{ url('/admin/match/search/completed') }}" method="post" autocomplete="off">

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
      <!-- Table -->
 
  <br /><br />
  <div class="row">
    <div class="col-md-12">
     <div class="card-header border-0">
             
			  <div class="nav-wrapper">
    <ul class="nav my-tab nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
                            <a class="nav-link {{ $status == 'upcoming' ? 'active' : '' }}" href="{{ url('admin/match/upcoming') }}" >Upcoming</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status == 'live' ? 'active' : '' }}"  href="{{ url('admin/match/live') }}" >Live</a>
        </li>
        <li class="nav-item {{ $status == 'completed' ? 'active' : '' }}">
          <a class="nav-link" href="{{ url('admin/match/complete') }}" >Completed</a>
        </li>
         
            </ul>
<br>
 <div id="msg"></div>

                  <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr> 
        <th scope="col">S.No</th>
        <th scope="col">Match Date</th>
        <th scope="col">Match Name</th>
        <th scope="col">Short Name</th>
        <th scope="col">Match Status</th>
	<th>Action</th>    

      </tr>
      </thead>
      <tbody>

    @php 
  $i =1; 
  $limit=15; 
  if(isset($_GET['page'])){
    $page = $_GET['page'];
    $i = (($limit * $page) - $limit)+1;
  }else{
    $i =1;
  }        
  @endphp

@forelse($list as $trade)
    <tr>
 @if(isset($trade->name) && $trade->name != '') 
  @php
    $dt = new DateTime($trade->start_date);
    $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
    $startdate  = $dt->format('d-m-Y H:i:s');
  @endphp
<td>{{ $i }}</td>
<td>{{ $startdate }}</td>
      <th scope="row">
        <div class="cwidth-325box wraptextbg">
        <div class="media align-items-center">

          <div class="media-body">
            <span class="badge badge-dot">
              @if($trade->status == 'started') <i class="bg-success"></i>  @elseif($trade->status == 'notstarted')  <i class="bg-warning"></i> 
                @else <i class="bg-error"></i> @endif
            </span><span class="mb-0 text-sm">{{ $trade->name }}</span>
          </div>
        </div>
</div>
      </th>

      
      <td>{{ $trade->short_name }}</td>
      <td>{{ Ucfirst($trade->status) }}</td>

      <td class="text-left">
      @if($status == 'upcoming') 
		  @if($trade->view_status == 1)  
		<a onclick="viewstatus('{{ $trade->_id }}','disable')" href="#" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Disable"><i class="fas fa-ban"></i></a> 
		 @else
		<a onclick="viewstatus('{{ $trade->_id }}','enable')" href="#" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Enable"><i class="far fa-check-square"></i></a>
    		@endif		 
		@endif    
     <a href="{{ url('admin/match/edit/'.$trade->_id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>       
   </td> 
 </tr>
 @endif
 

      <?php $i++; ?>
@empty
      <tr><td colspan="7"> No record found!</td></tr>

      @endforelse
     
    </tbody>
  </table>
  </div>
  <div class="card-footer py-4">
   <nav aria-label="...">
      <ul class="pagination justify-content-end mb-0">
        <li class="page-item">
             
           {{ $list->render() }}
            
        </li> 
      </ul>
    </nav>  
  </div>
  </div>
  </div>
 

  <!-- Footer --> 
  @include("layouts.footer")   
<script type="text/javascript">
  function viewstatus(id,status){
     var result = confirm("Are you sure you want to " +status +"?"); 
      if (result) { 
      var data_url = '{{ url("admin/match/status") }}';
      var _token = '{{ csrf_token() }}';
      $.ajax({
      type: "POST",
      url: data_url,
      data: {id:id,status:status,_token:_token},
      dataType: "html", 
      success: function (data) {
         $("#msg").html(data);
         setTimeout(function(){ 
           
           location.reload();
         },5000);  
          }
       });
      }
  }
</script>

