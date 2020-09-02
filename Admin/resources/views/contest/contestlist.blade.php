@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
<div class="container-fluid">

<!-- Brand -->
<a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> CONTEST LIST</a>
    <!-- Form -->
     <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/contestlist/search') }}" method="post" autocomplete="off">
    {{ csrf_field() }}  
    <div class="form-group mb-0">
    <div class="input-group input-group-alternative">
    <div class="input-group-prepend">
    <span class="input-group-text"><i class="fas fa-search"></i></span>
    </div>
    <input class="form-control fas fa-search search-btn" placeholder="Search" type="text" name="searchitem" id="searchitem" value=" @isset($term) {{$term}} @endisset " placeholder="Search for User Name or Email" required>  
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
<div class="card-header border-0"> 
<div class="text-right">
<a href="{{ url('admin/createContest') }}" class="btn btn-sm btn-primary"><i class="fa fa-arrow-right"></i> Create Contest</a> 
</div>
</div>

<div class="table-responsive">
<div id="msg"></div>
<table class="table align-items-center table-flush">
<thead class="thead-light">
<tr>  
<th scope="col">Date / Time</th> 
<th scope="col">Category</th>
<th scope="col">Contest Name</th>
<th scope="col">Contest Size</th>
<th scope="col">Prize Pool</th> 
<th scope="col">Status</th>
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

@forelse($contest as $con) 
<tr>

<td>{{ date('d/m/Y H:i:s A', strtotime($con->updated_at)) }}</td> 
<td>{{ $con->category['cat_name'] }}</td>
<td>  <div class="cwidth-200box wraptextbg">{{ $con->contest_name }}</div></td>
<td>{{ $con->contest_size }}</td>
<td>{{ $con->prize_pool }}</td> 
<td>@if ($con->status == 1) Active @else In Active @endif </td>


<td class="text-right">
@if($con->status == 1)  
<a  onclick="changestatus('disable','{{ $con->_id }}')" href="#" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Disable"><i class="fas fa-ban"></i></a> 
@else
<a  onclick="changestatus('enable','{{ $con->_id }}')" href="#" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Enable"><i class="far fa-check-square"></i></a>
@endif


<button type="button" class="btn btn-sm btn-primary" value="{{ $con->_id }}" id="contest_view_id" data-toggle="modal" data-target="#myModal"><i class="far fa-eye"></i>
</button>



</td> 
</tr>

<?php
// print_r($con->winners);
?>


<tr>
<td colspan="11" class="team-div v-ground rank-list">
<div id="collapse{{ $con->_id }}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
<table class="select-table-player white-t"> 
<tbody>
<tr> 
<td>
<h4>View Rank</h4>
<table>
<tbody> 
@if($con->winners)   
@foreach ($con->winners as $key=>$winner) 
<tr class="player-data-td">
<td class="player-pic"> Rank {{ $key }} : </td>
<td class="player-name h6">{{ $winner }} </td> 
</tr>  
@endforeach 
@endif 
</tbody>
</table>
</td>  
</tr>
</tbody>
</table>    
</div>
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

{{ $contest->render() }}

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


<script type="text/javascript">
function changestatus(status,user) {

var result = confirm("Are you sure you want to " +status +"?"); 
if (result) { 

$.ajax({
url: '{{ url("/admin/contestStatus") }}',
type: 'POST',
data: {
"_token": "{{ csrf_token() }}",
"status": status,
"user": user
}, 
success: function (data) {
$("#msg").html(data);
setTimeout(function(){ 

location.reload();
},5000);  
} 
});
}
} 


$(document).ready(function(){

$(document).on('click', '#contest_view_id', function(e){

    e.preventDefault();   

    var id = $(this).val();

    var url = window.location.href;

    url = url+'/'+id;

    $('#dynamic-content').html(''); // leave it blank before ajax call
    $('#modal-loader').show();      // load ajax loader

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json'
    })
    .done(function(data){       
        
        $('#dynamic-content').html(data); // load response 
        var winners = data['winners'];
        var i;
        var display = '';
        $.each(data['winners'], function(propName, propVal) {
             display += '<tr class="player-data-td"><td class="player-pic"> Rank'+propName+' : </td> <td class="player-name h6">'+propVal+' </td>  </tr>';

        });
        $('#winning_details').html(display);

    })
    .fail(function(){
        $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
        $('#modal-loader').hide();
    });

});

});


</script> 
