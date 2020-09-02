    @include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">REFERAL USER LIST</a>
        <!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/referal/search') }}" method="post" autocomplete="off">
        {{ csrf_field() }}  
          <div class="form-group mb-0">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input value=" @isset($term) {{$term}} @endisset " class="form-control fas fa-search search-btn" placeholder="Search" type="text" name="searchitem" id="searchitem" placeholder="Search for User Name or Email" required>  
<button type="submit" class="fas fa-search search-btn"></button>
            </div>
          </div>
<!-- <a class="btn btn-warning btn-xs" href="{{ url('/admin/users') }}"> Reset </a> -->
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
    <div class="container-fluid mt--7">
      <!-- Table -->

      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              
              <h3 class="mb-0">Total Users : {{ $counts }}</h3> 
              
            </div>
	    
            <div class="table-responsive">
            <div id="msg"></div>
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr> 
                    <th scope="col">S.No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Team Name</th>
                    <th scope="col">Email</th>
                    {{-- <th scope="col">Mobile Verified</th>
                    <th scope="col">Email Verified</th> --}}
                    <th scope="col">Registered Via</th> 
                    {{-- <th scope="col">Login Status</th>  --}}
                    <th colspan="2">Action</th>
                  </tr>
              </thead>
            <tbody>

    @if(count($details) > 0)
    @php $i = ($details->currentpage()-1) * $details->perpage() + 1; @endphp
    @foreach($details as $user)
    <tr>
      <td>{{ $i }}</td>
      <th scope="row">
        <div class="media align-items-center">
            <div class="media-body">
            <span class="badge badge-dot">
              @if($user->is_login == 1) <i class="bg-success"></i>  @else  <i class="bg-warning"></i> @endif  
            </span><span class="mb-0 text-sm">{{ $user->name != '' ? $user->name : '-' }}</span>
          </div>
        </div>
      </th>
      <td>{{ $user->teamname }}</td>
      <td>{{ $user->email }}</td>
      {{-- <td>@if($user->email_verify == 1) Yes @elseif($user->email_verify == 2) Waiting @else No @endif</td>
      <td>  @if($user->phone_verify == 1) Yes @elseif($user->phone_verify == 2) Waiting @else No @endif </td> --}}
        <td>@if($user->register_type == 'normal') Email&Pass @else {{ ucfirst($user->register_type) }} @endif</td>
        {{-- <td>@if($user->is_login == 1) Yes  @else No @endif</td>                --}}
       <!--  <td id="{{ $user->id }}">
          @if($user->status == 1)
          <button title="User Active" class="btn btn-danger btn-xs" onclick="changestatus(0,'{{ $user->id }}')">Deactivate</button>
          @else
          <button title="User In-active" class="btn btn-danger btn-xs" onclick="changestatus(1,'{{ $user->id }}')">Activate</button>
          @endif
        </td>   -->
        <td class="text-left">
          <a href="{{ url('/admin/referal_view/'.Crypt::encrypt($user->id)) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="View"><i class="far fa-eye"></i></a>

      </td>

      </tr>
      <?php $i++; ?>
      @endforeach
      @else
      <tr><td colspan="7"> No record found!</td></tr>
      @endif

    </tbody>
  </table>
  </div>
  <div class="card-footer py-4">
   <nav aria-label="...">
      <ul class="pagination justify-content-end mb-0">
        <li class="page-item">
            @if($details->count())
            {{ $details->links() }}
            @endif
        </li> 
      </ul>
    </nav>  
  </div>
  </div>
  </div>
  </div>

  <!-- Footer --> 
  @include("layouts.footer")   

  <script type="text/javascript">