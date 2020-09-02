@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">User deatails </a> 
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
      <div class="col-md-12">        
        <div class="card">
<div class="card-header border-0">
              <div class="row align-items-center profilerightboxbg">
                <div class="col">
                  <h3 class="mb-0">Profile Details</h3>
                </div>
                <div class="col text-right">
                  <ul class="excellistf row">
        <li>
        <a class="btn btn-sm btn-primary" href="{{url('admin/users/induvidual_exportExcel').'/'.$user['id'].'/xls'}}"><i class="zmdi zmdi-download zmdi-hc-fw"></i> xls</a>
      </li>
        <li>
        <a class="btn btn-sm btn-primary" href="{{url('admin/users/induvidual_exportExcel').'/'.$user['id'].'/pdf'}}"> <i class="zmdi zmdi-download zmdi-hc-fw"></i> pdf</a></li>
        <li>
        <a class="btn btn-sm btn-primary" href="{{url('admin/users/induvidual_exportExcel').'/'.$user['id'].'/csv'}}"> <i class="zmdi zmdi-download zmdi-hc-fw"></i> csv</a></li>
      </ul>
                </div>
              </div>
            </div>

          <div class="card-body">
         
            <div class="table-responsive">
  <table class="table align-items-center table-flush">
                
      <tbody>

	 @if(count($user))
	<th >Name :  </th> <td>{{ $user['name'] != '' ? $user['name'] : 'Test' }}  </td> </tr>
	<th >Email :  </th> <td>{{ $user['email'] != '' ? $user['email'] : '-' }}  </td> </tr>  
      </tr>  
	 @else
         <tr><td colspan="7"> No record found!</td></tr>

         @endif
     
    </tbody>
  </table>
             
            </div>
          </div>
        </div>
      </div>
    </div>
           
    <div class="row">
      <div class="col-md-12">        
        <div class="card">
          <div class="card-body">
          <h4 class="card-title">Deposit Details </h4>
            <div class="table-responsive">
  <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>   
	<th scope="col">Date & Time</th>
	<th scope="col">User Name</th>
	<th scope="col">Order ID</th>
	<th scope="col">Amount (INR)</th> 
	<th scope="col">Reference Id</th>
	<th scope="col">Payment Mode</th> 
	<th scope="col">signature</th>
	<th scope="col">Status</th> 
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

	@forelse($user['deposittransaction'] as $transactions)
     
	       @php
	         $date = $transactions->created_at;
	       @endphp
		<tr>
			<td> {{ date('d-m-Y H:i:s A', strtotime($date)) }} </td>
			<td> {{ $transactions->users['name'] }} </td>
			<td> {{ $transactions->orderId }} </td>
			<td> {{ number_format($transactions->orderAmount, 2, '.', '') }} </td> 
			<td> {{ $transactions->referenceId }} </td>
			<td> {{ $transactions->paymentMode }} </td>
		    <td> {{  $transactions->signature != '' ? $transactions->signature : '-' }} </td> 
			<td> {{ $transactions->txStatus }} </td>
			 
		</tr>
 
      <?php $i++; ?>
	@empty
         <tr><td colspan="7"> No record found!</td></tr>

         @endforelse
     
    </tbody>
  </table>
             
            </div>
          </div>
        </div>
      </div>
    </div>

     <div class="row">
      <div class="col-md-12">        
        <div class="card">
          <div class="card-body">
          <h4 class="card-title">Withdraw Details </h4>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>   
	<th scope="col">Date & Time</th>
	<th scope="col">User Name</th>
	<th scope="col">Amount (INR)</th> 
	<th scope="col">Payment Mode</th> 
	<th scope="col">Status</th>
	 
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

	@forelse($user['transaction'] as $key=>$transactions)
	 <tr> 
	     @php
	        $i=1; 
	        $date = $transactions['txTime']['date'];
	     @endphp
		 <tr>
		<td> {{ date('d-m-Y H:i:s A', strtotime($date)) }} </td>
		<td> {{ $transactions->users['name'] }} </td>
		<td> {{ number_format($transactions->orderAmount, 2, '.', '') }} </td> 
		<td> {{ $transactions->paymentMode }} </td>
		<td>
		        @if($transactions->txStatus == 3) Waiting
                        @elseif($transactions->txStatus == 2) Cancelled
                        @elseif($transactions->txStatus == 1) Completed
                        @endif
		 </td>
		 
		</tr>
 
      <?php $i++; ?>
	@empty
         <tr><td colspan="7"> No record found!</td></tr> 
        @endforelse
     
    </tbody>
  </table>
            </div>
          </div>
        </div>
      </div>
    </div> 
  @include("layouts.footer")  
