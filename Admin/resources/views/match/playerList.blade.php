@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-top-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> PLAYERS LIST</a>
        <!-- Form -->
       <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/players/search') }}" method="post" autocomplete="off">
        {{ csrf_field() }}  
          <div class="form-group mb-0">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input class="form-control fas fa-search search-btn" placeholder="Search" type="text" name="searchitem" id="searchitem" placeholder="Search for Player Name" required>  
              <button type="submit" class="fas fa-search search-btn"></button>
            </div>
          </div> 
        </form>  

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
              <h3 class="mb-0">Total Players : {{ $counts }}</h3>
             <!-- <a href="{{ url('admin/createContest') }}"><i class="zmdi zmdi-arrow-left"></i> Create Contest</a> -->
            </div>
     
        <div class="table-responsive">
<div id="msg"></div>
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>  
	<th scope="col">S.No</th>
	<th scope="col">Name</th>
	<th scope="col">Batting Style</th>
	<th scope="col">Bowling Style</th>  
	<th scope="col">Role</th>
	<th></th>	
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
 
    <td>{{ $i }}  </td> 
	<td>{{ $trade->name }}</td> 
	<td>{{ $trade->batting_style }}</td>
	<td>{{ $trade->bowling_style }}</td> 
	<td>{{ $trade->role }}</td>     
		 
      <td class="text-left">
          <a href="{{ url('admin/player/edit/'.$trade->_id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a> 
      </td> 
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
             
           {{ $list->render() }}
            
        </li> 
      </ul>
    </nav>  
  </div>
  </div>
  </div>
 </div>

  <!-- Footer --> 
 @include("layouts.footer")  

 
