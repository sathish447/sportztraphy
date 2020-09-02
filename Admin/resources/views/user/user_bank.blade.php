

<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Bank Name </label>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<input type="text" name="balance" class="form-control" value="{{$userdetails->bank['bank_name']}}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Account Number </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
		<input type="text" name="balance" class="form-control" value="{{$userdetails->bank['account_number']}}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Branch </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
			<input type="text" name="balance" class="form-control" value="{{$userdetails->bank['branch']}}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Bank Proof </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
        <img class="img-responsive" src="{{$userdetails->bank['bank_proof']}}" alt="no image">
		</div>	
	</div>
</div>
