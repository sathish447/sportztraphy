@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
<div class="container-fluid">

<!-- Brand -->
<a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">USER BONUS LIST</a>
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
<th scope="col">Date&Time</th> 
<th scope="col">Username</th>
<th scope="col">Bonus Amount</th>
<th scope="col">Action</th>
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

@forelse($bonus as $con) 

	<tr>

	<td>{{ date('d/m/Y H:i:s A', strtotime($con->updated_at)) }}</td> 
	<td>{{ $con->teamname }}</td>
	<td>  <div class="cwidth-200box wraptextbg">{{$con->wallet['bonus']}}</div></td>
	<td>

	 <a href="{{ url('admin/update_manage_bonus/'.$con->_id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>       

	</td> 
	</tr>

	<?php
	// print_r($con->winners);
	?>

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
