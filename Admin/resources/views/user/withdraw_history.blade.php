<div class="table-responsive">
    <table class="table" id="dows">
        <thead>
            <tr>
                <th>S.NO</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
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
            @forelse($withdraw_history as $usr)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $userdetails->teamname }}</td>
                <td>{{ $userdetails->email }}</td>
                <td>{{ $userdetails->phone }}</td>
                <td>@if($usr->txStatus == 1 )
                        Completed
                    @elseif($usr->txStatus == 2)
                        Cancelled
                    @elseif($usr->txStatus == 3)
                        Wating for Admin
                    @endif</td>
            </tr>
            @php $i++; @endphp
            @empty
            <tr>
                <td colspan="10">
                    <div class="alert alert-info">No Records Found.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>