			
					<div class="table-responsive" >					
						<table class="table" id="dows">
							<thead>
								<tr>
									<th>S.NO</th>									
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Satus</th>
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
								@forelse($userReferral as $usr)
								<tr>
									<td>{{ $i }}</td>
									<td>{{ $usr->name=="" ? $usr->teamname:$usr->name }}</td>
									<td>{{ $usr->email }}</td>
									<td>{{ $usr->phone }}</td>
									<td>@if($usr->status == 0 ) 
											In Active 
										@elseif($usr->status == 1)
											 Active
										@endif</td>
								</tr>
								@php  $i++; @endphp
								@empty
								<tr><td colspan="10"><div class="alert alert-info">No users referred yet.</div></td></tr>
								@endforelse
							</tbody>
						</table>
					</div>              
              