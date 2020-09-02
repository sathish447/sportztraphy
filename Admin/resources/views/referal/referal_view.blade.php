@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">View REWFERAL </a> 

        <ul class="navbar-nav align-items-center d-none d-md-flex">
          @include ('layouts.usermenu')

        </ul>
      </div>
    </nav>
    <div class="header stempbg bg-gradient-primary pb-8 pt-5">
  
  <span class="mask bg-gradient-default opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
         </div>
      </div>
    </div>
  <div class="container-fluid mt--7">
      <!-- Table -->
    <div class="row">
    <div class="col-xl-12 order-xl-1">
          <div class="card shadow">
          <div class="">

                @include('referal.tab')
  <div class="col-12">
<div class="tab-container">
               <div class="tab-content">

  <div class="tab-pane active fade show" id="buy_trade" role="tabpanel">
        @include('user.tradehistory.buytradehistory')
      </div>                
</div>   
          </div>
        </div>
    </div>
</div>
</div>
            </div>
    <!-- Footer -->
  @include("layouts.footer")    
