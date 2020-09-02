@extends('layouts.header')
@section('title', 'Users List - Admin')
@section('content')
<section class="content">
	<header class="content__title">
		<h1>Kyc Requested Users</h1>
	</header>
	<div class="card">
		<div class="card-body">
		  <!--   <form action="{{ url('/admin/users/search') }}" method="post" autocomplete="off">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-3">                
						<input type="text" name="searchitem" class="form-control" placeholder="Search for User Name or Email" value= "" required>
					</div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-success user_date" value="Search" />
						<a class="btn btn-warning btn-xs" href="{{ url('/admin/users') }}"> Reset </a> 
					</div>
				</div>
			</form> -->
			<br/>
			<div class="col-md-12 col-sm-12 col-xs-12 userexprotlet">
				@if($details)
	    			<h5> Total Kyc Requests : {{ count($details) }} </h5>
	    			<hr />
	    		@endif

	    		@if(count($details) > 0)
	    		<div class="right-div exportright">
					<a href="{{url('admin/users/exportExcel')}}">
						<span class="btn btn-success user_date"> <i class="zmdi zmdi-download zmdi-hc-fw"></i> export</span>
					</a>
				</div>
				@endif
			</div>

			<div class="table-responsive search_result">
				<table class="table" id="dows">
					<thead>
						<tr>
							<th>S.No</th>
							<th>First Name</th>
							<th>Email ID</th>
							<th>Email Verified</th>
							<th>KYC Status</th>
							<th>User Status</th>
							<th colspan="2">Action</th>
						</tr>
					</thead>
					<tbody>
					 @if(count($details) > 0)
					 @php $i = ($details->currentpage()-1) * $details->perpage() + 1; @endphp
					@foreach($details as $kyc)
						<tr>
							<td>{{ $i }}</td>
							<td>{{ $kyc->user->name }} </td>
							<td>{{ $kyc->user->email }}</td>
							<td>@if($kyc->user->email_verify == 1) Yes @elseif($kyc->user->email_verify == 2) Waiting @else No @endif</td>
							<td>
							@if($kyc->status == 0) 
								<a href="{{ url('admin/kycview/'.Crypt::encrypt($kyc->id)) }}">Waitting</a>
							@elseif($kyc->status == 2)
							 	<a href="{{ url('admin/kycview/'.Crypt::encrypt($kyc->id)) }}">Rejected</a>
							@elseif($kyc->status == 3)
								<a href="{{ url('admin/kycview/'.Crypt::encrypt($kyc->id)) }}">Expired</a>
							@elseif($kyc->status == 1)
								<a href="{{ url('admin/kycview/'.Crypt::encrypt($kyc->id)) }}">Accepted</a>
							@else 
							 	Not Applied 
							@endif</td>
							<td id="{{ $kyc->user->id }}">
								@if($kyc->user->status == 1)
									<button class="btn btn-danger btn-xs" onclick="changestatus(0,{{ $kyc->user->id }})">Deactivate</button>
								@else
									<button class="btn btn-danger btn-xs" onclick="changestatus(1,{{ $kyc->user->id }})">Activate</button>
								@endif
							</td>
							<td><a class="btn btn-success btn-xs" href="{{ url('/admin/users_edit/'.Crypt::encrypt($kyc->user->id)) }}"><i class="zmdi zmdi-edit"></i> View </a>
							</td>
							<td><a class="btn btn-success btn-xs" href="{{ url('/admin/user_excel/'.Crypt::encrypt($kyc->user->id)) }}"><i class="zmdi zmdi-download zmdi-hc-fw"></i> Export </a>
							</td>
						</tr>
						<?php $i++; ?>
					@endforeach
					@else
					    <tr><td colspan="7"> No record found!</td></tr>
					@endif
					</tbody>
				</table>
				<div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <div class="pagination-tt clearfix">
                @if($details->count())
				    {{ $details->links() }}
				@endif
                </div>
              </div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	function changestatus(status,user)
	{
		$.ajax({
	      url: '{{ url("/admin/user_status") }}',
	      type: 'POST',
	      data: {
	        "_token": "{{ csrf_token() }}",
	        "status": status,
	        "user": user
	      }, 
	      success: function (data) {
	        window.location.reload();
	      },
	    });
	}
</script>
@endsection


