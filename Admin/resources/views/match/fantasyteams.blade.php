@include('layouts.header') 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">VIEW FANTASY TEAM</a>
        <!-- Form -->
       <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="{{ url('/admin/fantasyteams/search') }}" method="post" autocomplete="off">
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
             <!-- <a href="{{ url('admin/createContest') }}"><i class="zmdi zmdi-arrow-left"></i> Create Contest</a> -->
            </div>
     
        <div class="table-responsive">
        <div id="msg"></div>
              <table class="table align-items-center table-flush">
                <thead class="thead-light"> 
           <tr>
                      <th scope="col">S.No</th>
                      <th scope="col">Match date</th> 
                      {{-- <th scope="col">Match Name</th> --}}
                      <th scope="col">Match Name</th>  
                      <th scope="col">Contest Name</th> 
                      <th scope="col">Team Name</th> 
                      <th scope="col"> Winning Price </th> 
                      {{-- <th scope="col">Fantay Points</th>  --}}
                      <th scope="col">Match Status</th>  
                      <th scope="col">Contest Status</th>  
          <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @php 
                    $i =1; 
                    $j=1;
                    $limit=15; 
                    if(isset($_GET['page'])){
                    $page = $_GET['page'];
                    $i = (($limit * $page) - $limit)+1;
                  }else{
                  $i =1;
                }  
                @endphp

                @forelse($list as $key=>$trades)  
           <tr>  
                  @php   
                  $dt = new DateTime($trades->match !='' ? $trades->match->start_date : '');
                  $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
                  $startdate  = $dt->format('d-m-Y'); 
                  $time  = $dt->format('H:i:s'); 
                  $bowler=[];
                  $keeper=[];
                  $allrounder=[];
                  $batsman=[];    
                  $players=[];  
                  $players = isset($trades['players']) ? $trades['players'] : $players;    

                  @endphp 

            @if(!empty($players)) 
              @foreach($players as $pl)
              @php $players_role = isset($pl['role']) ? $pl['role'] : ''; @endphp

              @if($players_role !='' && $pl['role'] == 'wk')  
              <?php $keeper[] = $pl;   ?>
              @elseif($players_role !='' && $pl['role'] == 'bowl') 
              <?php $bowler[] = $pl;   ?>
              @elseif($players_role !='' && $pl['role'] == 'bat') 
              <?php $batsman[] = $pl;   ?>
              @elseif($players_role !='' && $pl['role'] == 'ar') 
              <?php $allrounder[] = $pl;  ?>
              @endif 

              @endforeach
              @endif 

           <td>{{ $i }} </td>   
      <td>
                    {{ $trades->match !='' ? $startdate : '' }}{{ $trades->match !='' ? $time : '' }}
                    </td>
 
                  <td> 
                  <div class="">
                    <span class="badge badge-dot">
                        <i class="bg-warning"></i>
                      </span>{{ $trades->match !='' ? $trades->match->short_name : ''}}
                  </div>
                    </td>
                  {{-- <td> <div class="h5">{{ $trades->match !='' ? $trades->match->short_name  : ''}}</div></td>  --}}
                  <td>{{ $trades->contestinfo !='' ? $trades->contestinfo['contest_name'] : '-' }}</td>   
                  <td class="h5">{{ $trades->user_name}}</td>
                  <td>{{ isset($trades->price_winning_amount) ? $trades->price_winning_amount : 0 }}</td>
                  {{-- <td>{{ $trades->fantasy_points }}</td>  --}}
                  <td>{{ $trades->match !='' ? Ucfirst($trades->match->status) : '' }}</td>
                  <td>@if($trades->cancelled == 1) 
                    Cancelled                    
                    @elseif($trades->winner_status == 0 && $trades->match->status != 'completed')
                    Pending
                    @elseif($trades->winner_status == 0 && $trades->match->status == 'completed' )
                    Lost
                    @else 
                    Won 
                    @endif
                  </td> 
    <td>
    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal{{ $trades->_id }}" aria-expanded="true" aria-controls="myModal{{ $trades->_id }}">View</a>

    
    <!-- <a href="#" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#collapse{{ $trades->_id }}" aria-expanded="true" aria-controls="collapse{{ $trades->_id }}" title="View"><i class="far fa-eye"></i></a> -->
  
  </td>
        </tr>
    <tr>
    <td colspan="10" class="team-div v-ground">
    <div class="modal fade modaldatabg modalground myModal" id="myModal{{ $trades->_id }}"aria-labelledby="headingOne" data-parent="#accordion" >
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

    <div class="modal-header">
    <h4 class="modal-title">Team Members</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body team-div v-ground">
    <div class="table-responsive" id="dynamic-content">
    <table class="table align-items-center table-flush select-table-player white-t"> 
        <tbody>
              <tr>
                <td>
                  <h6> Wicket-Keepers </h6> 
                  <table>
                    <tbody> 
                      @if(isset($keeper) && count($keeper) > 0) 
                      @foreach ($keeper as $wk) 
                      <tr class="player-data-td">
                        <td class="player-pic"><img src="https://fantasy.demozab.com/assets/images/player.svg" width="50" height="50"></td>
                        <td class="player-name h6">{{ $wk['name'] }}<br><span class="grey-t s-t"> WK - {{ $wk['team_name'] }}</span></td>
                        <td class="h6">{{ $wk["fantasy_points"] }}<br>
                          <span class="grey-t s-t">Points</span>
                        </td>
                      </tr>
                      @endforeach 
                      @endif                 
                    </tbody>
                  </table>
                </td>
                <td>
                  <h6>Batsmen</h6>
                  <table>
                    <tbody>
                    @if(isset($batsman) && count($batsman) > 0)  
                    @foreach ($batsman as $wk) 
                    <tr class="player-data-td">
                        <td class="player-pic"><img src="https://fantasy.demozab.com/assets/images/player.svg" width="50" height="50"></td>
                        <td class="player-name h6">{{ $wk['name'] }}<br><span class="grey-t s-t"> BAT - {{ $wk['team_name'] }}</span></td>
                        <td class="h6">{{ $wk["fantasy_points"] }}<br>
                          <span class="grey-t s-t">Points</span>
                        </td>
                      </tr> 
                    @endforeach 
                    @endif
                    </tbody>
                  </table>
                </td> 

                <td>
                  <h6>All-Rounders</h6>
                  <table>
                    <tbody>
                    @if(isset($allrounder) && count($allrounder) > 0)  
                    @foreach ($allrounder as $wk)
                    <tr class="player-data-td">
                        <td class="player-pic"><img src="https://fantasy.demozab.com/assets/images/player.svg" width="50" height="50"></td>
                        <td class="player-name h6">{{ $wk['name'] }}<br><span class="grey-t s-t"> AR - {{ $wk['team_name'] }}</span></td>
                        <td class="h6">{{ $wk["fantasy_points"] }}<br>
                          <span class="grey-t s-t">Points</span>
                        </td>
                      </tr>
                    @endforeach 
                    @endif                 
                    </tbody>
                  </table>
                </td>
                <td>
                  <h6>Bowlers</h6>
                  <table>
                    <tbody>
                    @if(isset($bowler) && count($bowler) > 0)  
                    @foreach ($bowler as $wk)
                    <tr class="player-data-td">
                        <td class="player-pic"><img src="https://fantasy.demozab.com/assets/images/player.svg" width="50" height="50"></td>
                        <td class="player-name h6">{{ $wk['name'] }}<br><span class="grey-t s-t"> BOWL - {{ $wk['team_name'] }}</span></td>
                        <td class="h6">{{ $wk["fantasy_points"] }}<br>
                          <span class="grey-t s-t">Points</span>
                        </td>
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
    </div>  
    </div>
    </div>
    </div>
       </td>
        </tr>


      <?php $i++; ?>
@empty
           @php 
       $bowler=[];
              $keeper=[];
              $allrounder=[];
              $batsman=[];   
    @endphp
      <tr><td colspan="7"> No record found!</td></tr>

      @endforelse
     
    </tbody>
  </table>
  </div>
  <div class="card-footer py-4">
   <nav aria-label="...">
      <ul class="pagination justify-content-end mb-0">
        <li class="page-item">
             
           {{ $list->render() }} 
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
  $(document).ready(function(){
    $(document).on('click', '#contest_view_id', function(e){
        e.preventDefault();   
        var id = $(this).val();
        var url = window.location.href;
        url = url+'/'+id;
        console.log(url);
        $('#dynamic-content').html(''); // leave it blank before ajax call
        $('#modal-loader').show();      // load ajax loader
        $.ajax({
            url: "{{url('fantasyteam')}}",
            type: 'GET',
            dataType: 'json'
        })
        .done(function(data){       
          console.log(data);
            $('#dynamic-content').html(data); // load response 
            var winners = data['winners'];
            var i;
            var display = '<table class="select-table-player white-t"><tbody><tr><td><h6> Wicket-Keepers </h6><table><tbody>';
            $.each(data['keeper'], function(propName, propVal) {
                display += '<tr class="player-data-td"><td class="player-pic"><img src="https://fantasy.demozab.com/assets/images/player.svg" width="50" height="50"></td><td class="player-name h6">';
                display += propName;
                display += '<br><span class="grey-t s-t"> WK '+propVal + '</span></td><td class="h6">{{ $wk["fantasy_points"] }}<br><span class="grey-t s-t">Points</span></td></tr>';
            } );
            display += '</tbody></table></td>';
            $('#winning_details').html(display);
        })
        .fail(function(){
            $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
            $('#modal-loader').hide();
        });
    });
  });
</script>