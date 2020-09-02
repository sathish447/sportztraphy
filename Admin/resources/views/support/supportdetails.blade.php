@extends('layouts.header')
@section('title', 'Support Ticket')
@section('content')
<section class="content">
	<header class="content__title">
		<h1>Message</h1>
	</header>
	@if(session('raised_new_ticket'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        {{ session('raised_new_ticket') }}
        </div>
    @endif
	<div class="row">
		<div class="col-md-12">
			<div class="messages">
				<div class="messages__body">
					@if($support_data && $support_data !='')
					<div class="messages__header">
						<div class="toolbar toolbar--inner mb-0">
							<div class="toolbar__label">Message From : {{ $tickets['name'] }}
								<span class="zmdi-pull-right"> <a href="{{ url('admin/support') }}"> (Back to Support)</a></span>
							</div>
						</div>
					</div>
					@endif
					@if(count($support_data) > 0)
						<div class="messages__content">
							@foreach($support_data as $support_tickets)
								@if($support_tickets['message'] != NULL)
									<div class="messages__item">
										<div class="messages__details">
											<img src="{{ url('images/user-1.jpg') }}" class="chatig">
											<p>{{ $support_tickets->message }}</p>
											<small><i class="zmdi zmdi-time"></i> {{ humanTiming (strtotime($support_tickets->created_at)) }}</small>
										</div>
									</div>
								@endif
								@if($support_tickets['reply'] != NULL)
									<div class="messages__item messages__item--right">
										<div class="messages__details msgrght">
											<img src="{{ url('images/chat-1.jpg') }}" class="chatig">
											<p>{{ $support_tickets->reply }}</p>
											<small><i class="zmdi zmdi-time"></i> {{ humanTiming (strtotime($support_tickets->created_at)) }}</small>
										</div>
									</div>
								@endif
							@endforeach
						</div>
					@endif
					<div class="messages__reply">
						<form method="post" autocomplete="off" action="{{ url('admin/addMessage') }}">
							{{ csrf_field() }}
							<div class="row">
								<input type="hidden" name="ticket_id" value="{{ $ticket_id }}">				
								<div class="col-md-10 {{ $errors->has('message') ? ' has-error' : '' }}">
									<textarea class="messages__reply__text" name="message" placeholder="Type a message..." required="required" /></textarea>
									@if ($errors->has('message'))
			                            <span class="help-block">
			                                <strong class="text text-danger">{{ $errors->first('message') }}</strong>
			                            </span>
			                        @endif
								</div>
								<div class="col-md-2" style="margin-top: 7px;">
									<input type="submit" name="add" class="btn btn-success" value="Send">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endsection