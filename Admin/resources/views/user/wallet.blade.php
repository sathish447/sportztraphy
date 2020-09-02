

<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Total Balance </label>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<input type="text" name="balance" class="form-control" value="{{ $wallet['currency'] }} {{ $wallet['total'] }}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Deposited </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
		<input type="text" name="balance" class="form-control" value="{{ $wallet['currency'] }} {{ $wallet['deposit'] }}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Winnings </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
			<input type="text" name="balance" class="form-control" value="{{ $wallet['currency'] }} {{ $wallet['winnings'] }}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Bonus </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
			<input type="text" name="balance" class="form-control" value="{{ $wallet['currency'] }} {{ $wallet['bonus'] }}" readonly>
		</div>	
	</div>
</div>
