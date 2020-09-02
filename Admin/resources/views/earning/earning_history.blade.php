@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">EARNING History</a>
        <!-- Form -->
       <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/earnings/search') }}" method="post" autocomplete="off">
        {{ csrf_field() }}  
          <div class="form-group mb-0">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input class="form-control fas fa-search search-btn" placeholder="Search" type="text" name="searchitem" value=" @isset($term) {{$term}} @endisset " id="searchitem" placeholder="Search for User Name or Email" required>  
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
<div id="msg"></div>
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>   
  <th scope="col">Date & Time</th>
  <th scope="col">User Name</th>
  <th scope="col">Contest</th>
  <th scope="col">Order ID</th>
  <th scope="col">Amount (INR)</th> 
  <th scope="col">Winning Amount</th>
  <th scope="col">Actual Winning Amount</th> 
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

  @forelse($contest as $contests)
     
         @php
           $date = $contests['fantasyteam']['created_at'];
         @endphp
    <tr>
      <td> {{ date('d-m-Y H:i:s A', strtotime($date)) }} </td>
      <td> {{ $contests->fantasyteam['user_name'] }} </td>
      <td> {{ $contests->contest_name }} </td>
      <td> {{ $contests['fantasyteam']['contest_id'] }} </td>
      <td> {{ number_format($contests->entry_fee, 2, '.', '') }} </td> 
      <td> 
        @if($contests['fantasyteam']['price_winning_amount'] != 0)
          {{ $contests['fantasyteam']['price_winning_amount'] }}
        @else 
           -
        @endif
      </td>
      <td> 
        @if($winning_amount != 0)
          {{ $winning_amount }} 
        @else
          -
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
  <div class="card-footer py-4">
   <nav aria-label="...">
      <ul class="pagination justify-content-end mb-0">
        <li class="page-item">

             {{ $page_contest->render() }}
            
        </li> 
      </ul>
    </nav>  
  </div>
  </div>
  </div>
 </div>

  <!-- Footer --> 
 @include("layouts.footer")  
 
