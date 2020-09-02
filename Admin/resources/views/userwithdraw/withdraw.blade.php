@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> Withdraw {{ $type == 'pending' ? 'Request' : 'History' }} </a>
        <!-- Form -->
       <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/withdrawsearch') }}" method="post" autocomplete="off">
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
             <!-- <a href="{{ url('admin/createContest') }}"><i class="zmdi zmdi-arrow-left"></i> Create Contest</a> -->
            </div>
     
        <div class="table-responsive">

		@if(session('status'))
			<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> {{ session('status') }}
			</div>
		@endif
		@if(session('errorstatus'))
			<div class="alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed!</strong> {{ session('errorstatus') }}
			</div>
		@endif
             <form action="{{ url('admin/withdrawUpdate') }}" method="post">
		   		{{ csrf_field() }}
		 <button type="submit" id="btnsend" class="btn btn-success btn-xs" align="right" style="display: none;">Send</button>
 
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>   
	<th scope="col">Date & Time</th>
	<th scope="col">User Name</th>
	<th scope="col">Amount (INR)</th> 
	<th scope="col">Payment Mode</th> 
	<th scope="col">Status</th>
	 @if($type == 'pending')  
	  <th scope="col"><input type="checkbox" name="selectall" onchange ="checkAll(this)"  id="selectall" value="Select All">Select All</th>
	 @endif 
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

	@forelse($transaction as $key=>$transactions)
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
							
		 @if($type == 'pending') 
		  <!--<td> <a class="btn btn-success btn-xs" href="{{  url('/admin/withdrawEdit/'.Crypt::encrypt($transactions->id)) }}"> <i class="zmdi zmdi-edit"></i> View </a> </td>  -->
		  <td><input type="checkbox" name="txns[]"  onchange="check('{{ $transactions->_id }}')" id="select_{{ $transactions->_id }}" value="{{ $transactions->_id }}">
		   <input type="hidden" name="txn['amount'][{{ $transactions->_id }}]" value="{{ $transactions->orderAmount }}">
                   <input type="hidden" name="txn['beneid'][{{ $transactions->_id }}]"  value="{{ $transactions->users['bank']['beneid'] }}">
	           <input type="hidden" name="txn['transferId'][{{ $transactions->_id }}]"  value="{{ $transactions->transferId }}">
		  </td>
		  <!-- url('/admin/withdrawEdit/'.Crypt::encrypt($transactions->id))   -->
		  @endif	
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
             
           {{ $transaction->render() }}
            
        </li> 
      </ul>
    </nav>  
  </div>
  </div>
  </div>
 </div>

  <!-- Footer --> 
 @include("layouts.footer")  
 

<script type="text/javascript">
function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
                 $("#btnsend").show();
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
                 $("#btnsend").hide();
             }
         }
     }
 }
 
 var count=0;
 function check(id) {
 	 var checkBox = document.getElementById("select_"+id);

        if (checkBox.checked == true) { 
            count++;
        } else { 
            count--;
        }
        if(count >= 1){
        	$("#btnsend").show();
        }
        else{
        	$("#btnsend").hide();
        }
    }
 </script>	
