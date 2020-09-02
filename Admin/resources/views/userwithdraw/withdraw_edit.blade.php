@extends('layouts.header')
@section('title', 'Withdraw History')
@section('content')
<section class="content">
	<header class="content__title">
		<h1>Withdraw History</h1>
	</header>
	@if(session('status'))
	    <div class="alert alert-success" role="alert">
	        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> {{ session('status') }}
	    </div>
	@endif
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<a href="{{ url('admin/withdrawHistory') }}"><i class="zmdi zmdi-arrow-left"></i> Back to withdraw history</a>
					<br /><br />
				     <form method="post" id="currency_form" action="{{ url('admin/withdraw_update') }}" autocomplete="off" onsubmit="btn_update.disabled = true; return true;">
						{{ csrf_field() }}
						<input type="hidden" value="{{ $withdraw->id }}" name="id">
						<input type="hidden" value="{{ currency($withdraw->type) }}" name="currency">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label> Requested Withdraw Amount (INR)</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" name="amount" class="form-control" value="{{ $withdraw->amount != NULL ? number_format($withdraw->amount, 2, '.', '') : 'None' }}" readonly /><i class="form-group__bar"></i>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Withdraw Fee (INR)</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" name="fee" class="form-control" value="{{ number_format($withdraw->adminfee, 2) }}" readonly /><i class="form-group__bar"></i>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Total Deducted Amount (INR)</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" name="total_amount" class="form-control" value="{{  number_format($withdraw->amount + $withdraw->adminfee, 2)}}" readonly /><i class="form-group__bar"></i>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Status</label>
								</div>
							</div>
							@if($withdraw->status == 0)
							<div class="col-md-4">
								<div class="form-group">
									<select class="form-control" name="status">
									    <option value="0">Waiting for approval</option>
										<option value="1">Approved</option>
										<option value="2">Rejected</option>
									</select>
								</div>
							</div>
							<p class="text text-info">NOTE : Once you update the status as "Approved / Rejected", you can't update status again!</p>
							@else
							    @if($withdraw->status == 1) Approved @endif
							    @if($withdraw->status == 2) Rejected @endif
							    @if($withdraw->status == 3) Cancelled by user @endif
							@endif
						</div>
						@if($withdraw->status == 0)
							<div class="form-group">
								<button type="submit" name="edit" id="btn_update" class="btn btn-light"><i class=""></i> Update</button>
							</div>
						@endif
					</form>
					<hr />
					<!-- <h5>Bank Details</h5>
					<br /> 
					<form>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Bank Name</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" value="{{ $withdraw->users->bank_name }}" readonly /><i class="form-group__bar"></i>
								</div>
							</div>
						</div> 
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Account Number</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" value="{{ $withdraw->users->account_number }}" readonly /><i class="form-group__bar"></i>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Account Name</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" value="{{ $withdraw->users->account_name }}" readonly /><i class="form-group__bar"></i>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Bank Code</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" value="{{ $withdraw->users->ifsc }}" readonly /><i class="form-group__bar"></i>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Bank Branch</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" value="{{ $withdraw->users->bank_name }}" readonly /><i class="form-group__bar"></i>
								</div>
							</div>
						</div>
						 
					</form> -->
				</div>
			</div>
		</div>
	</div>
	@endsection