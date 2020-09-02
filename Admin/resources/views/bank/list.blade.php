@extends('layouts.header')
@section('title', 'Admin Bank')
@section('content')
<section class="content">
	<header class="content__title">
		<h1>Company Bank Details</h1>
	</header>
		@if(session('status'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> {{ session('status') }}
        </div>
    @endif
	<div class="row">
		 <div class="pull-right">
                <!--  <a class="btn btn-bg site-btn m-btn violet-btn" href="{{ url('admin/add_bank') }}"><i class="zmdi zmdi-edit"></i> Add Bank Details </a> --> </td>
                </div>
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
		   <div class="table-responsive search_result">
				<table class="table" id="dows">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Date & Time</th>
							<th>Currency Name</th>
							<th>Bank Details</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					    @if(count($bank) > 0)
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
							@foreach($bank as $admin_banks)
						 @php $account = strlen($admin_banks->account) > 50 ? substr($admin_banks->account,0,50)."..." : $admin_banks->account;
						 @endphp
						<tr>
							<td>{{ $i }}</td>
							<td>{{ date('Y/m/d h:i:s', strtotime($admin_banks->created_at)) }}</td>
							<td>{{ $admin_banks->coin }}</td>
							<td>{{ $account }}</td>
							<td>
								<a class="btn btn-success btn-xs" href="{{ url('/admin/edit_bank/'.Crypt::encrypt($admin_banks->id)) }}"><i class="zmdi zmdi-edit"></i> Update </a>

					<!-- 			<a class="btn btn-success btn-xs" href="{{ url('/admin/delete_bank/'.Crypt::encrypt($admin_banks->id)) }}"><i class="zmdi zmdi-edit"></i> Delete </a> -->

								<!--  <a href="javascript:void(0);" data-toggle="modal" data-target='#modal_send_from_address' class="btn btn-success btn-xs" onclick="deleteBank('{{ $admin_banks->id }}')"><i class="zmdi zmdi-delete"></i> Delete </a> -->
							</td>
						</tr>
						@php
			           $i++;
			           @endphp
					@endforeach
					@else
					    <tr><td colspan="7"> No record found!</td></tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
	</div>


<!--
<div class="modal fade" id="modal_send_from_address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Delete Bank Details</h4>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div id="confirm_result">
            <div class="modal-body">
              <p class="pull-left">Are you sure you want to delete this details?</p>
            </div>
            <div class="modal-footer" id="model_footer_form">
              <div class="row">
                <div class="col-md-12">
                <form method="post" autocomplete="off" id="confirm_delete_bank_details">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="bank_id">
                  <button id="confirm_btn" type="submit" class="btn btn-primary">Yes</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </form>
              </div>
              </div>
            </div>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>!-->

<div class="modal fade" id="modal_send_from_address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title pull-left">Delete Bank Details</h5>
<button type="button" class="close t-white" data-dismiss="modal">×</button>
</div>
<div class="modal-body">
<div class="row">
          <div class="col-md-12">
            <div id="confirm_result">
           
              <p class="pull-left">Are you sure you want to delete this details?</p>
            </div>
</div>
</div>
</div>
<div class="modal-footer" id="model_footer_form">
<div class="row">
                <div class="col-md-12">
                <form method="post" autocomplete="off" id="confirm_delete_bank_details">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="bank_id">
                  <button id="confirm_btn" type="submit" class="btn btn-success">Yes</button>
                  <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                </form>
              </div>
              </div>
</div>
</div>
</div>
</div>


	</div>
</section>
@endsection