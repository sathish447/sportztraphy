@extends('layout.default')
@section('content')
   <div class="album text-muted">
     <div class="container">
       <div class="row">
         <h1>This is a demo text</h1>
         @foreach ($users->take(10) as $usr)
            <p>First Name: {{$usr->first_name}} </p>
            <p>First Name: {{$usr->last_name}}</p>
            <p>First Name: {{$usr->email}}</p>
            <p>First Name: {{$usr->phone}}</p><hr />
        @endforeach
    </div>
     </div>
   </div>
@endsection
<b></b>