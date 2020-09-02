@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> Support LIST</a>
        <!-- Form -->
      <!--  <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/users/search') }}" method="post" autocomplete="off">
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
        </form> --> 

        <ul class="navbar-nav align-items-center d-none d-md-flex">
          @include ('layouts.usermenu');

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
             <!-- <a href="{{ url('admin/createContest') }}"><i class="zmdi zmdi-arrow-left"></i> Create Contest</a> -->
            </div>
     
        <div class="table-responsive">
<div id="msg"></div>
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>  
	<th scope="col">Date & Time</th>
	<th scope="col">Ticket Id</th>
	<th scope="col">Username</th>
	<th scope="col">Subject</th>  
	<th scope="col">Actions</th> 
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

	@forelse($tickets as $ticket)
    <tr>
 
       <td>{{ date('d-m-Y H:i:s', strtotime($ticket->created_at)) }}</td>
            <td>{{ $ticket->reference_no }}</td>
            <td><a href="{{ url('admin/users_edit/'.Crypt::encrypt($ticket->user_id)) }} ">{{ username($ticket->user_id) }}</a></td> 
            <td>{{ $ticket->subject }}</td>
            <td><a class="btn btn-primary btn-sm" href="{{ url('/admin/support/'.Crypt::encrypt($ticket->id)) }}" class="btn btn-info">Chat</a></td>
     
    </tr>
 
      <?php $i++; ?>
@empty
      <tr><td> Yet no one raise support ticket</td></tr>

      @endforelse
     
    </tbody>
  </table>
  </div>
  <div class="card-footer py-4">
   <nav aria-label="...">
      <ul class="pagination justify-content-end mb-0">
        <li class="page-item">
             
           {{ $tickets->render() }}
            
        </li> 
      </ul>
    </nav>  
  </div>
  </div>
  </div>
 </div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> Delete </div>
      <div class="modal-body"> Are you sure you want to delete this user? </div>
      <div class="modal-footer"> <a class="btn btn-danger btn-ok">Yes</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
</div>
  <!-- Footer --> 
 @include("layouts.footer")  

  
