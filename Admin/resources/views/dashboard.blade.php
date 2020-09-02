@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid">

    <!-- Brand -->
    <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">Dashboard</a>
    <!-- Form -->
    <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
      <div class="form-group mb-0">
        <div class="input-group input-group-alternative">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
          </div>
          <input class="form-control" placeholder="Search" type="text">
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
<div class="header stempbg bg-gradient-primary pb-8 pt-5 pt-md-8">

  <span class="mask bg-gradient-default opacity-8"></span>
  <div class="container-fluid">
    <div class="header-body">
      <!-- Card stats -->
      <div class="row cardpricebox">           
        <div class="col-xl-3 col-lg-6 card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Total Contest Fee</h5>
                <span class="h2 font-weight-bold mb-0">@convert($response['contestfee'])</span>
              </div>
              <div class="col-auto">
                <img class="header-icon" src="{{ url('assets/img/icons/contest-fee.svg') }}"/>

              </div>
            </div>
            <p class="mt-3 mb-0 text-muted text-sm">

              <span class="">Received From Contestants</span>
            </p>
          </div>
        </div>


        <div class="col-xl-3 col-lg-6 card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Total Win Amount</h5>
                <span class="h2 font-weight-bold mb-0">@convert($response['winning'])</span>
              </div>
              <div class="col-auto">
                <img class="header-icon" src="{{ url('assets/img/icons/win-icon.svg') }}"/>
              </div>
            </div>
            <p class="mt-3 mb-0 text-muted text-sm">

              <span class="">Paid to winners</span>
            </p>
          </div>
        </div>


        <div class="col-xl-3 col-lg-6 card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Total Deposit Amount</h5>
                <span class="h2 font-weight-bold mb-0">@convert($response['deposit'])</span>
              </div>
              <div class="col-auto">
                <img class="header-icon" src="{{ url('assets/img/icons/deposit-icon.svg') }}"/>
              </div>
            </div>
            <p class="mt-3 mb-0 text-muted text-sm">

              <span class="">Users Deposited</span>
            </p>
          </div>
        </div>


        <div class="col-xl-3 col-lg-6 card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Total Withdraw Requests</h5>
                <span class="h2 font-weight-bold mb-0">@convert($response['withdraw'])</span>
              </div>
              <div class="col-auto">
                <img class="header-icon" src="{{ url('assets/img/icons/withdraw-icon.svg') }}"/>
              </div>
            </div>
            <p class="mt-3 mb-0 text-muted text-sm">
              <span class="">Till Today</span>
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<div class="container-fluid mt--7">
  <!-- Table -->
  <div class="row">
    <div class="col-xl-8 mb-5 mb-xl-0 overviewchartbg">
      <div class="card bg-gradient-default shadow">
        <div class="card-header bg-transparent">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
              <h2 class="text-white mb-0">User Signup</h2>
            </div>
            <div class="col">
              <ul class="nav nav-pills justify-content-end">
                @php
                $userdata = implode(",",$response["monthweek"]);
                $monthdata = implode(",",array_flip($response["monthweek"]));
                @endphp
                <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales" data-update='{"data":{"datasets":[{"data": [[11,06],[12,8],[01,1]]}]' data-prefix="$" data-suffix="k">
                  <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                    <span class="d-none d-md-block">Month</span>
                    <span class="d-md-none">M</span>
                  </a>
                </li>
                <li class="nav-item" data-toggle="chart" data-target="#chart-sales" data-update='{"data":{"datasets":[{"data":[[11,06],[12,8],[01,1]]}]' data-prefix="$" data-suffix="k">
                  <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                    <span class="d-none d-md-block">Week</span>
                    <span class="d-md-none">W</span>
                  </a>
                </li>

              </ul>
            </div>
          </div>
        </div>
        <div class="card-body">
          <!-- Chart -->
          <div class="chart">
            <!-- Chart wrapper -->
            <canvas id="chart-sales" class="chart-canvas"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 user-pie-chart overviewchartbg1">
      <div class="card shadow">
        <div class="card-body line-bottom">
          <div class="row livepricerowbg">
            <div class="col">
              <div class="table-content">
                <div>
                  <span class="m-icon">
                    <img src="{{ url('assets/img/icons/totaluser-icon.svg') }}" />
                  </span>
                </div>
                <div class="text-right">
                  <span class="h1">{{$response['details']['totalusers']}}</span>
                  <br>
                  <span class="info-span">Total users</span>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="table-content">
                <div class="">
                  <span class="m-icon">
                    <img src="{{ url('assets/img/icons/newuser-icon.svg') }}" />
                  </span>
                </div>
                <div class="text-right">
                  <span class="h1">{{$response['details']['todayusers']}}</span>
                  <br>
                  <span class="info-span">New users</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row livepricerowbg">
            <div class="col">
              <div class="table-content">
                <div><span class="m-icon"><img src="{{ url('assets/img/icons/joinuser-icon.svg') }}" /></span></div>
                <div class="text-right"><span class="h1">{{$response['contestusers']}}</span>
                  <br><span class="info-span">Contest Join</span></div>
                </div>
              </div>
              <div class="col">
                <div class="table-content">
                  <div class=""><span class="m-icon"><img src="{{ url('assets/img/icons/disableuser-icon.svg') }}" /></span></div>
                  <div class="text-right">
                    <span class="h1">{{$response['details']['deactivate_users']}}</span>
                    <br><span class="info-span">Disable users</span></div>
                  </div>
                </div>
              </div>
              <div id="myChart" class="chart--container"></div>
              <!-- Chart -->
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-xl-8 mb-5 mb-xl-0 overviewchartbg">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Page visits</h3>
                </div>
                <div class="col text-right">
                  <a href="#!" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Page name</th>
                    <th scope="col">Visitors</th>
                    <th scope="col">Unique users</th>
                    <th scope="col">Bounce rate</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">
                      /argon/
                    </th>
                    <td>
                      4,569
                    </td>
                    <td>
                      340
                    </td>
                    <td>
                      <i class="fas fa-arrow-up text-success mr-3"></i> 46,53%
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">
                      /argon/index.html
                    </th>
                    <td>
                      3,985
                    </td>
                    <td>
                      319
                    </td>
                    <td>
                      <i class="fas fa-arrow-down text-warning mr-3"></i> 46,53%
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">
                      /argon/charts.html
                    </th>
                    <td>
                      3,513
                    </td>
                    <td>
                      294
                    </td>
                    <td>
                      <i class="fas fa-arrow-down text-warning mr-3"></i> 36,49%
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">
                      /argon/tables.html
                    </th>
                    <td>
                      2,050
                    </td>
                    <td>
                      147
                    </td>
                    <td>
                      <i class="fas fa-arrow-up text-success mr-3"></i> 50,87%
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">
                      /argon/profile.html
                    </th>
                    <td>
                      1,795
                    </td>
                    <td>
                      190
                    </td>
                    <td>
                      <i class="fas fa-arrow-down text-danger mr-3"></i> 46,53%
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-xl-4 overviewchartbg1">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Popular Contests</h3>
                </div>
                <div class="col text-right">
                  <a href="{{ url('admin/contestList') }}" class="btn btn-sm btn-primary">See all</a>
                </div> 
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Contests</th>
                    <th scope="col">Joined</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($response['popular']) > 0)

                  @foreach ($response['popular'] as $pop)
                  <tr>
                    <th scope="row">
                      {{$pop['name']}}
                    </th>
                    <td>
                      {{$pop['count']}}
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        @php $percent = round($pop['count'] /  $response['sel_cont_count'] * 100); @endphp
                        <span class="mr-2">{{$percent}} %</span>
                        <div>
                          <div class="progress">
                            <div class="progress-bar bg-gradient-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                  @endif

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


      @include("layouts.footer")

      <script type="text/javascript">
        let chartConfig = {
          globals: {
            fontFamily: 'Ubuntu'
          },
          layout: 'h',
          graphset: [{
            type: 'pie',
            backgroundColor: '#fff',

            legend: {
              margin: 'auto auto 0% auto',
              backgroundColor: 'none',
              borderWidth: '0px',
              item: {
                color: '%backgroundcolor'
              },
              layout: 'float',
              marker: {
                borderRadius: '3px',
                borderWidth: '0px'
              },
              shadow: false
            },
            plot: {
              tooltip: {
                text: '%v USERS',
                borderRadius: '3px',
                shadow: false
              },
              valueBox: {
                visible: false
              },
              marginRight: '50px',
              borderWidth: '0px',
              shadow: false,
              size: '100px',
              slice: 50
            },
            plotarea: {

              backgroundColor: '#FFFFFF',
              borderColor: '#fff',
              borderRadius: '3px',
              borderWidth: '1px',
              marginTop: '0px',
              marginBottom: '5px'

            },

            series: [{
              text: 'New User',
              values: [{!! $response['details']['todayusers'] !!}],
              top: '45px',
              backgroundColor: '#6CCFDF'
            },
            {
              text: 'Contest Join',
              values: [{!! $response['contestusers'] !!}],
              backgroundColor: '#E76D45'
            },
            {
              text: 'Disable User',
              values: [{!! $response['details']['deactivate_users'] !!}],
              backgroundColor: '#55BA72'
            }
            ]
          },

          ]
        };

        zingchart.render({
          id: 'myChart',
          data: chartConfig
        });
      </script>