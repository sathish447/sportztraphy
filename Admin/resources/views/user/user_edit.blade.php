@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">View USER </a> 

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

                @include('user.tab')
  <div class="col-12">
<div class="tab-container">
               <div class="tab-content">

               <div class="tab-pane active fade show" id="profile" role="tabpanel">                       
                  <form>
                          <div class="form-group row">
                          <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Name </label>
                            <div class="form-group">
                            <input class="form-control" value="{{ $userdetails->name }}" readonly="">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Email</label>
                            <div class="form-group">
                            <input class="form-control" value="{{ $userdetails->email }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Country </label>
                            <div class="form-group">
                            @if(isset($userdetails->country))
                            <input class="form-control" value="{{ $userkyc->country }}" readonly>
                            @else
                            <input class="form-control" value="India" readonly>
                            @endif
                            </div>
                          </div>

                            <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Phone No </label>
                            <div class="form-group">
                              {{-- @if(isset($userkyc->country)) --}}
                              <input class="form-control" value="{{ $userdetails->phone }}" readonly>
                              {{-- @else
                              <input class="form-control" value=" - " readonly> 
                              @endif --}}
                            </div>
                          </div> 
                        </form>
                    </div>   

      <div class="tab-pane fade" id="account" role="tabpanel">
        <ul class="nav my-tab nav-fill flex-column flex-md-row subtabbgrow" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-11-tab" data-toggle="tab" href="#wallet" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Wallet</a>
            </li>  
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-12-tab" data-toggle="tab" href="#coin_deposit" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">Bonus Earned</a>
            </li>  
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-13-tab" data-toggle="tab" href="#pan_details" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">PAN Details</a>
            </li>  
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-14-tab" data-toggle="tab" href="#bank_details" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">Bank Details</a>
            </li> 
      </ul>
      <div class="tab-content subtabbg">
          <div class="tab-pane active fade show" id="wallet" role="tabpanel"> 
                @include('user.wallet')
          </div>   
          <div class="tab-pane  fade " id="coin_deposit" role="tabpanel">
                {{-- @include('user.user_crypto_deposit') --}}
         </div>
          <div class="tab-pane  fade " id="pan_details" role="tabpanel">
                @include('user.user_pan')
          </div>      
          <div class="tab-pane  fade " id="bank_details" role="tabpanel">
                @include('user.user_bank')
          </div>
      </div>
  </div>  
  
  <div class="tab-pane fade" id="history" role="tabpanel">
        <ul class="nav my-tab nav-fill flex-column flex-md-row subtabbgrow" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-15-tab" data-toggle="tab" href="#deposit_history" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Deposit History</a>
            </li>  
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-16-tab" data-toggle="tab" href="#withdraw_history" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">Withdraw history</a>
            </li>              
      </ul>
      <div class="tab-content subtabbg">
                 <div class="tab-pane active fade show" id="deposit_history" role="tabpanel">
                 @include('user.deposit_history') 
                </div>

                <div class="tab-pane  fade " id="withdraw_history" role="tabpanel">
                @include('user.withdraw_history')
                </div>

                <div class="tab-pane  fade " id="currency_withdraw" role="tabpanel">
                {{-- @include('user.user_fiat_withdraw') --}}
                </div>
      </div>
  </div>   
  <div class="tab-pane fade" id="buy_trade" role="tabpanel">
        @include('user.tradehistory.buytradehistory')
      </div>

      <div class="tab-pane fade" id="sell_trade" role="tabpanel">
        @include('user.tradehistory.contestinfo')
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
