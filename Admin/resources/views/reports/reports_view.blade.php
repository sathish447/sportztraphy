@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
<div class="container-fluid">

<!-- Brand -->
<a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">REPORTS LIST</a>
<!-- Form -->
	 <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/users/search') }}" method="post" autocomplete="off">
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

<div class="container-fluid mt--7" id="accordion"> 
<div class="row">
<div class="col">
<div class="card shadow">

<div class="table-responsive">
<div id="msg"></div>
<table class="table align-items-center table-flush">
<thead class="thead-light">
<tr>  
<th scope="col">Match Name</th>
<th scope="col">Prize pool(in ₹)</th>
<th scope="col">Fee Expected(in ₹)</th>
<th scope="col">Fee Received(in ₹)</th>
<th scope="col">profit(in ₹)</th>
</tr>
</thead>
@foreach($match_details as $value)
<tbody>
	<tr>
	<td>{{ $value->match->short_name }}</td>
	<td>{{ $value->contestinfo->prize_pool }}</td>
	<td>{{ $value->contestinfo->contest_size * $value->contestinfo->entry_fee}}</td>
	<td>{{ count($match_details) * $value->contestinfo->entry_fee}}</td>
	@if(count($match_details) == $value->contestinfo->contest_size)
		<td><span class="s-t grey-t text-green">{{ (count($match_details) * $value->contestinfo->entry_fee) - $value->contestinfo->prize_pool}}</span></td>
	@elseif((count($match_details) != $value->contestinfo->contest_size))
		<td><span class="s-t grey-t text-red">{{ (count($match_details) * $value->contestinfo->entry_fee) - $value->contestinfo->prize_pool}}</span></td>
	@endif
	</tr>
</tbody>
@endforeach
</table>
</div>
<div class="card-footer py-4">
<nav aria-label="...">
<ul class="pagination justify-content-end mb-0">
<li class="page-item">


</li> 
</ul>
</nav>  
</div>
</div>
</div>
</div>

<!-- Footer --> 
@include("layouts.footer") 

<div class="modal fade modaldatabg" id="myModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h4 class="modal-title">Contest Information</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body padding-0">
<div class="table-responsive scroll-databox">
<table class="table align-items-center table-flush">
<thead class="thead-light">
<tr>
<th scope="col">Rank</th>
<th scope="col">Prize</th>
</tr>
</thead>
<tbody id="winning_details">   
                             
</tbody>
</table>
</div>
</div>  
</div>
</div>
</div>
