					<div class="table-responsive" style="overflow-x: auto;white-space: nowrap;">
						<table class="table" id="dows">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Match Date</th>
									<th>Match Name</th>
									<th>Short Name</th>
									<!-- <th>Contest ID</th> -->
									<th>Contest Name</th>
									<th>Team Name</th>
									<th>Amount</th>
									<th>Fantasy Points</th>
									
								        <th>Match Status</th>	
									<th>Winning Status</th>		
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
								@forelse($contests_joined as $trade)
								<tr>
									<td>{{ $i }}</td>
									<td>{{ date('d-m-Y H:i:s A', strtotime($trade->match->start_date)) }}</td>
									<td>{{ $trade->match->name }}</td>
									<td>{{ $trade->match->short_name }}</td>
									<!-- <td>{{ $trade->contest_id }}</td> -->
									<td>{{ $trade->contestinfo !='' ? $trade->contestinfo['contest_name'] : '-' }}</td>		
									<td>{{ $userdetails->teamname}}</td>
									<td>{{ $trade->price_winning_amount }}</td>
									<td>{{ $trade->fantasy_points }}</td> 
									<td>{{ Ucfirst($trade->match->status) }}</td>
									<td>@if($trade->winner_status == 0 ) 
										 Pending 										
										@else 
										 Won 
										@endif</td>
								</tr>
								@php
						         $i++;
						         @endphp
								@empty
								<tr><td colspan="10"><div class="alert alert-info">Not joined in any contest. </div></td></tr>
								@endforelse
							</tbody>
						</table>
					</div>
					
				
			

<script>
    function pageredirect(self){
	window.location.href = self.value;
}
</script>
