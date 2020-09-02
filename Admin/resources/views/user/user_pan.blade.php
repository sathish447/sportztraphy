

<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Pan Number </label>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<input type="text" name="balance" class="form-control" value="{{$userdetails->pan['pan_number']}}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Pan Name </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
		<input type="text" name="balance" class="form-control" value="{{$userdetails->pan['pan_name']}}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Dob Pan </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
			<input type="text" name="balance" class="form-control" value="{{$userdetails->pan['dob_pan']}}" readonly>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label>Pan Proof </label>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
			<img src="{{$userdetails->pan['pan_proof']}}" alt="no image">
		</div>	
	</div>
</div>
