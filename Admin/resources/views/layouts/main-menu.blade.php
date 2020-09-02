<li class="nav-item  {{ (request()->is('admin/dashboard')) ? 'active' : '' }} ">
            <a class="nav-link  {{ (request()->is('admin/cities')) ? 'active' : '' }}"  href="{{ url('admin/dashboard') }}">
            <i class="fas fa-home"></i>Dashboard
            </a>
          </li>
          <li class="nav-item {{ (request()->is('admin/users')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/users')) ? 'active' : '' }}" href="{{ url('admin/users') }}">
              <i class="fa fa-users" aria-hidden="true"></i>Users
            </a>
          </li>
          <li class="nav-item {{ (request()->is('admin/playerList')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/playerList')) ? 'active' : '' }}"  href="{{ url('admin/playerList') }}">
              <i class="fa fa-list-alt" aria-hidden="true"></i>Players List
            </a>
          </li>
          <li class="nav-item {{ (request()->is('admin/match/upcoming')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/match/upcoming')) ? 'active' : '' }}"  href="{{ url('admin/match/upcoming') }}">
              <i class="far fa-calendar-alt"></i>Match List
            </a>
          </li>
          <li class="nav-item {{ (request()->is('admin/catlist')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/catlist')) ? 'active' : '' }}"  href="{{ url('admin/catlist') }}">
            <i class="fas fa-award"></i>Contest Categories
            </a>
          </li> 
          <li class="nav-item {{ (request()->is('admin/contestList')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/contestList')) ? 'active' : '' }}"  href="{{ url('admin/contestList') }}">
              <i class="fa fa-trophy" aria-hidden="true"></i>Contests
            </a>
          </li>
       <li class="nav-item {{ (request()->is('admin/fantasyteam')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/fantasyteam')) ? 'active' : '' }}"  href="{{ url('admin/fantasyteam') }}">
            <i class="fas fa-users-cog"></i>Fantasy Teams
            </a>
          </li>

          <li class="nav-item {{ (request()->is('admin/withdrawRequest')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/withdrawRequest')) ? 'active' : '' }}"  href="{{ url('admin/withdrawRequest') }}">
             <i class="fas fa-comment-dollar"></i>Withdraw Request
            </a>
          </li>


	<li class="nav-item {{ (request()->is('admin/withdrawHistory')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/withdrawHistory')) ? 'active' : '' }}"  href="{{ url('admin/withdrawHistory') }}">
             <i class="fas fa-hand-holding-usd"></i>Withdraw History
            </a>
          </li>
	<li class="nav-item {{ (request()->is('admin/depositHistory')) ? 'active' : '' }}">
            <a class="nav-link {{ (request()->is('admin/depositHistory')) ? 'active' : '' }}"  href="{{ url('admin/depositHistory') }}">
            <i class="far fa-credit-card"></i>Deposit History
            </a>
          </li> 
        <li class="nav-item {{ (request()->is('admin/earning')) ? 'active' : '' }}">
          <a class="nav-link {{ (request()->is('admin/earning')) ? 'active' : '' }}"  href="{{ url('admin/earning') }}">
           <i class="fas fa-piggy-bank"></i>Earning
          </a>
      </li>
      <li class="nav-item {{ (request()->is('admin/transactions')) ? 'active' : '' }}">
        <a class="nav-link {{ (request()->is('admin/transactions')) ? 'active' : '' }}"  href="{{ url('admin/transactions') }}">
        <i class="fas fa-history"></i>Transactions
        </a>
      </li> 
      
    <li class="nav-item {{ (request()->is('admin/referal')) ? 'active' : '' }}">
      <a class="nav-link {{ (request()->is('admin/referal')) ? 'active' : '' }}"  href="{{ url('admin/referal') }}">
      <i class="fas fa-sitemap"></i>Referal 
      </a>
    </li>     
    <li class="nav-item {{ (request()->is('admin/bonus')) ? 'active' : '' }}">
      <a class="nav-link {{ (request()->is('admin/bonus')) ? 'active' : '' }}"  href="{{ url('admin/bonus') }}">
      <i class="fas fa-sitemap"></i>Bonus Settings 
      </a>
    </li>
    <li class="nav-item {{ (request()->is('admin/managebonus')) ? 'active' : '' }}">
      <a class="nav-link {{ (request()->is('admin/managebonus')) ? 'active' : '' }}"  href="{{ url('admin/managebonus') }}">
      <i class="fas fa-sitemap"></i>Manage Bonus 
      </a>
    </li>       
      <li class="nav-item">
        <a class="nav-link"  href="{{ url('admin/reports') }}">
        <i class="far fa-file-alt"></i>Reports
        </a>
    </li> 


<li class="nav-item {{ (request()->is('admin/msgForm')) ? 'active' : '' }}">
  <a class="nav-link {{ (request()->is('admin/msgForm')) ? 'active' : '' }}"  href="{{ url('admin/msgForm') }}">
  <i class="far fa-bell"></i>Notification
  </a>
</li> 
<li class="nav-item {{ (request()->is('admin/faq')) ? 'active' : '' }}">
  <a class="nav-link {{ (request()->is('admin/faq')) ? 'active' : '' }}"  href="{{ url('/admin/faq') }}">
  <i class="fas fa-sticky-note"></i>FAQ 
  </a>
</li>
<!-- <li class="nav-item">
  <a class="nav-link"  href="{{ url('admin/cms') }}">
  <i class="fas fa-users-cog"></i>Manage CMS Pages
  </a>
</li>
<li class="nav-item">
  <a class="nav-link"  href="{{ url('admin/contact_us') }}">
  <i class="fas fa-users-cog"></i>Contact us
  </a>
</li> -->
<li class="nav-item">
              <a class="nav-link collapsed" href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
                <i class="fas fa-users-cog"></i>
                <span class="nav-link-text">Manage CMS Pages</span>
              </a>
              <div class="collapse {{ (request()->segment(2)=='term_conditions' || request()->segment(2)=='about_us' || request()->segment(2)=='contact_us'  || request()->segment(2)=='news') ? 'show' : '' }}" id="navbar-forms" style="">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item {{ (request()->is('admin/contact_us')) ? 'active' : '' }}">
                    <a href="{{ url('admin/contact_us') }}" class="nav-link {{ (request()->is('admin/contact_us')) ? 'active' : '' }}">
                      <span class="sidenav-mini-icon"></span>
                      <span class="sidenav-normal"> Contact Us </span>
                    </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/term_conditions')) ? 'active' : '' }}">
                    <a href="{{ url('admin/term_conditions') }}" class="nav-link {{ (request()->is('admin/term_conditions')) ? 'active' : '' }}">
                      <span class="sidenav-mini-icon"></span>
                      <span class="sidenav-normal"> Terms & Conditions </span>
                    </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/privacy_policy')) ? 'active' : '' }}">
                    <a href="{{ url('admin/privacy_policy') }}" class="nav-link {{ (request()->is('admin/privacy_policy')) ? 'active' : '' }}">
                      <span class="sidenav-mini-icon"></span>
                      <span class="sidenav-normal"> Privacy Policy </span>
                    </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/about_us')) ? 'active' : '' }}">
                    <a href="{{ url('admin/about_us') }}" class="nav-link {{ (request()->is('admin/about_us')) ? 'active' : '' }}">
                      <span class="sidenav-mini-icon"></span>
                      <span class="sidenav-normal"> About Us </span>
                    </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/news')) ? 'active' : '' }}">
                    <a href="{{ url('admin/news') }}" class="nav-link {{ (request()->is('admin/news')) ? 'active' : '' }}">
                      <span class="sidenav-mini-icon"></span>
                      <span class="sidenav-normal"> News </span>
                    </a>
                  </li>
                </ul>
              </div>
            </li>


	<!-- <li class="navigation__sub navigation__sub--toggled"><a href="#"><i class="zmdi zmdi-settings" aria-hidden="true"></i>Settings</a>
                    <ul>
                    <li class="@@photogalleryactive"> 
                        <a href="{{ url('admin/security') }}"> Security Settings </a>
                    </li>
                    <li class="@@photogalleryactive"> 
                        <a href="{{ url('admin/withdrawcommission') }}">
                            withdraw Settings </a>
                    </li> 
	-->
	 

