@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">withdraw settings </a>
        
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
             
            </div>
     
        <div class="table-responsive">
<div id="msg"></div>
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>   
 		    <th scope="col">S.No</th>
                    <th scope="col">Withdraw Limit</th>
                    <th scope="col">Withdraw minimum Limit</th>
                    <th scope="col">Withdraw maximum Limit</th> 
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
	 
       @forelse($details as $key => $commission)
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{$commission->withdraw_limit}}</td>
                    <td>{{$commission->withdraw_minimum}}</td>
                    <td>{{$commission->withdraw_maximum}}</td>
		    <td><a href="{{ url('/admin/withdrawcommissionsettings', Crypt::encrypt($commission->_id)) }}" class="btn btn-primary btn-sm">View / Edit</a></td>
      
    </tr>
 
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
             
           {{ $details->render() }}
            
        </li> 
      </ul>
    </nav>  
  </div>
  </div>
  </div>
 </div>

  <!-- Footer --> 
 @include("layouts.footer")  

