@include('layouts.header') 
<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
  <div class="container-fluid"> 
     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"> CREATE CONTEST </a> 
    
    <ul class="navbar-nav align-items-center d-none d-md-flex">
      @include ('layouts.usermenu')

    </ul>
  </div>
</nav>
<div class="header stempbg bg-gradient-primary pb-8 pt-5"> 
  <span class="mask bg-gradient-default opacity-8"></span>
  <div class="container-fluid">
    <div class="header-body">
    </div> 
  </div>
</div> 
    <div class="container-fluid mt--7"> 
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
             
       <a href="{{ url('admin/contestList') }}"><i class="zmdi zmdi-arrow-left"></i> Back to Contest List</a> 
              
            </div>

        <div class="card-body">
	
     @if(session('status'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> 		{{ session('status') }}
        </div>
     @endif
      @if(session('fail'))
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed!</strong> 		{{ session('fail') }}
        </div>
     @endif
 
          <form method="post"  action="{{ url('admin/updateContest') }}">
            {{ csrf_field() }}
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Category </label>
              <div class="div-input">
               <input type="hidden" name="id" value="add">
		<select class="form-control" name="cat" id="cat" required="required">
			<option value="" >Select Catgory</option>
			@foreach ($cat as $ids => $sn)  
				<option value="{{ $ids }}" {{ old('contest_name') == $ids ? 'selected' : '' }}>{{ $sn }}</option> 
			@endforeach
		</select>
		@if ($errors->has('cat'))
		<span class="help-block">
		<strong>{{ $errors->first('cat') }}</strong>
	       </span>
		@endif
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Contest Name</label>
              <div class="div-input">
                <input class="form-control" type = "text" required="required" name="contest_name" value="{{old('contest_name')}}" >
	  			@if ($errors->has('contest_name'))
                	<span class="help-block">
                        <strong>{{ $errors->first('contest_name') }}</strong>
                    </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Contest Size </label>
              <div class="div-input">
               <input class="form-control" type = "text" id="csize" required="required" name="contest_size" value="{{old('contest_size')}}">
				@if ($errors->has('contest_size'))
					<span class="help-block">
						<strong>{{ $errors->first('contest_size') }}</strong>
					</span>
				@endif
              </div>
            </div>

            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Prize Pool</label>
              <div class="div-input">
                <input class="form-control" type = "text" id="pool" required="required" name="prize_pool" value="{{old('prize_pool')}}"  onchange="updateFee();">
				@if ($errors->has('prize_pool'))
					<span class="help-block">
						<strong>{{ $errors->first('prize_pool') }}</strong>
					</span>
				@endif
              </div>
            </div> 

			<div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Price Count</label>
              <div class="div-input">
               <input class="form-control" type = "text" id="pcount" required="required" name="prize_count" value="{{ old('prize_count') }}">
				@if ($errors->has('prize_count'))
					<span class="help-block">
						<strong>{{ $errors->first('prize_count') }}</strong>
					</span>
				@endif
              </div>
            </div> 

			<div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Prize Type</label>
              <div class="div-input">
               <select class="form-control" id="prize_type" onchange="generates();" required="required"  name="prize_type">
				<option value="" selected>Select</option>
				<option value="1" {{ old('contest_name') == 1 ? 'selected' : '' }}>Individual</option>
				<option value="2" {{ old('contest_name') == 2 ? 'selected' : '' }}>Range</option>
				</select>
				 @if ($errors->has('prize_type'))
				   <span class="help-block">
				   <strong>{{ $errors->first('prize_type') }}</strong>
				   </span>
				 @endif
		         </div>
            </div>  

			<div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label">Entry Fee</label>
              <div class="div-input">
              <input class="form-control" type = "text" id="efee" required="required" name="entry_fee" value="{{ old('entry_fee') }}" readonly>
				@if ($errors->has('entry_fee'))
					<span class="help-block">
						<strong>{{ $errors->first('entry_fee') }}</strong>
					</span>
				@endif
              </div>
           </div>  

           <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Multiple</label>
              <div class="div-input">
				<select class="form-control" id="multiple" required="required"  name="multiple">
				<option value="" selected>Select</option>
				<option value=1>Yes</option>
				<option value=0>No</option>
				</select>	  			
				@if ($errors->has('multiple'))
                	<span class="help-block">
                        <strong>{{ $errors->first('multiple') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label form-control-label"> Confirm</label>
              <div class="div-input">
				<select class="form-control" id="type" required="required"  name="type">
				<option value="" selected>Select</option>
				<option value=1>Yes</option>
				<option value=0>No</option>
				</select>	  			
				@if ($errors->has('type'))
                	<span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
              </div>
            </div>

		<div id="ch" class="row">
			<div class="pricerangeboxbg"> 
				<div class="col-lg-7 col-md-10 col-10 price-box"></div>
				<div class="col-lg-5 col-md-2 col-2 add_rannge_btn"></div>
			</div>
		</div>
	 
    	<input type="submit" name="edit" class="btn btn-primary" type="button" style="margin-top:30px;" value="Save">
 		 </form>
        </div>
      </div>
  </div>
    </div>
  
@include("layouts.footer")  
	<script type="text/javascript">
		function updateFee() {
			var pool = $('#pool').val();
			var pcount = $('#csize').val();
			if(pool !='' && pcount !=''){
				var ifee = pool / pcount;
				var efee = Math.round(ifee + ifee * 0.18);
				$('#efee').val(efee);  
			}
		}

		function generate1() {

			var a = parseInt(document.getElementById("pcount").value);
			var ch = document.getElementById("ch"); 
			for (i = 0; i < a; i++) {
				var input = document.createElement("input");
				ch.appendChild(input);
			}
		}
 		
		function calculatepercentage(counts){ 
	      	    var units = $("#pcount").val();	
		    var percentage = $(".textpercent"+counts).val();	
		    var fromrange = $(".textto"+counts).val();
		    var torange = $(".textto"+counts).val();
		    var prizeamount = $("#pool").val(); 
		    var totalamount=0;
		    var amount=0;
		    var checkcount=0;	
		 
		    if(	prizeamount != '') {			  
			var arr_amount = $('input[name="percentage[]"]').map(function(key, index) {
			    key=key+1; 
			    var fromrange_index = $(".textfrom"+key).val();
  			    var torange_index = $(".textto"+key).val();
 
 			    var percent = this.value;  
			    var calc = parseFloat(prizeamount)*(parseFloat(percent)/100);
			  
		            var rangediff = parseFloat(torange_index)-parseFloat(fromrange_index);
			    if(torange_index !='' && torange_index > 0) {	
			       if(rangediff == 0){ 
				  rangediff = 1;					       
			       }			
			       else{
				 rangediff = parseFloat(torange_index)-parseFloat(fromrange_index);
			       } 
			       amount = parseFloat(calc)*parseFloat(rangediff);  
			       totalamount = parseFloat(totalamount)+parseFloat(amount);
			       checkcount=checkcount+rangediff;	  	
			    }	
			    	
			}).get();
		    }
		 //    if(totalamount > prizeamount){	
			// alert('Range percentage was exceeded the prize pool!!!');
			// var sub=counts-1;		
	  //               $(".textpercent"+sub).val('');  
	  //               $(".textto"+sub).prop("onchange", null).off("change"); 
			// $(".textpercent"+sub).prop("onchange", null).off("change"); 
		 //        $("#btnclick").prop("onclick", null).off("click");  
	  //               return false;
		 //    }
 		    
		 //    if(checkcount >= units) {
	 	//       alert('Prize count was exceeded!!!'); 
			// var sub=counts-1;		
	  //               $(".textto"+sub).val('');  
	  //               $(".textto"+sub).prop("onchange", null).off("change"); 
			// $(".textpercent"+sub).prop("onchange", null).off("change"); 
		 //        $("#btnclick").prop("onclick", null).off("click");  
	  //               return false;
		 //    }			  		 				
		}

		function checktoRange(counts){
 
			 var torange = $(".textto"+counts).val();
		         var fromrange = $(".textfrom"+counts).val(); 
			if(torange < fromrange){
			    alert('To range is not less than from range!!!'); 
		            var sub=counts;		
		            $(".textto"+counts).val('');
			    $("#btnclick").prop("onclick", null).off("click"); 
		            return false;
			}
			else{
			  showadd(counts);	
			}
		}

		function totalrange(){
		   var checkcount=0;
		   var arr_amount = $('input[name="from[]"]').map(function(key, index) {
			    key=key+1; 
			    var fromrange_index = $(".textfrom"+key).val();
  			    var torange_index = $(".textto"+key).val(); 
		           
			    if(torange_index !='' && torange_index > 0) {	
			       var rangediff = parseFloat(torange_index);	
			       // console.log(rangediff);
			   //     if(rangediff == 0){ 
				  // 	rangediff = 1;					       
			   //     }			
			   //     else{
				 	// rangediff = rangediff;
			   //     }  
			     	
			    }	
			  checkcount=rangediff+1;	  	
			    	
			}).get();
		   return checkcount;		
		}

		function showadd(counts){
		   var units = $("#pcount").val();
		   var torange = $(".textto"+counts).val();
		   var fromrange = $(".textfrom"+counts).val(); 
		   var percentage = $(".textpercent"+counts).val();
       	   var lastbox = totalrange();	
       	   console.log(lastbox);
       	   console.log(units);
		  // if(lastbox >= units){
		  
		  //   alert('Prize count was exceeded!!!'); 
		  //           var sub=counts;		
		  //           $(".textto"+sub).val('');  
		  //           $(".textto"+sub).prop("onchange", null).off("change"); 
			 //    $(".textpercent"+sub).prop("onchange", null).off("change");
			 //    // $("#btnclick").prop("onclick", null).off("click"); 
				
		  //           return false;		
		  // 	}		
          	if (fromrange == '' || torange == "" || percentage == "") { 
		        counts = counts+1;	
		        var nextstart = parseInt(torange)+parseInt(1); 
		        
		        if(lastbox <= units){

		             // alert('please enters the value');
		        }
		        else{ 
		            alert('Prize count was exceeded!!!'); 
		            var sub=counts+1;		
		            // $(".textto"+sub).val('');  
		            $(".textto"+sub).prop("onchange", null).off("change"); 
				    $(".textpercent"+sub).prop("onchange", null).off("change");
				    $("#btnclick").prop("onclick", null).off("click"); 
				
		            return false;
		        } 
		    }
		    else {
		       
		        counts = counts+1;	
		        var nextstart = parseInt(torange)+parseInt(1);  
			
			// if(torange < fromrange){
			//     alert('To range is not less than from range!!!'); 
		 //            var sub=counts-1;		
		 //            $(".textto"+sub).val('');  
		 //            $(".textto"+sub).prop("onchange", null).off("change"); 
			//     $(".textpercent"+sub).prop("onchange", null).off("change");	
			//     $("#btnclick").prop("onclick", null).off("click"); 
		 //            return false;
			// }
			 calculatepercentage(counts-1);
		        if(lastbox <= units){ 

		            $('<div class="row row'+counts+'" style="margin-left:0px;"><div class="col-md-4 col-sm-4 col-12"><div class="labelt"> Range From </div><input class="form-control textfrom'+counts+'" type="text" readonly="" name="from[]" value="'+nextstart+'"/></div><div class="col-md-4 col-sm-4 col-12"><div class="labelt"> Range To </div><input class="form-control textto'+counts+'" type="text"  name="to[]" value="" onchange="showadd('+counts+',this)"/></div><div class="col-md-4 col-sm-4 col-12"><div class="labelt"> Percentage </div><input class="form-control textpercent'+counts+'" type="text"  name="percentage[]"  onchange="showadd('+counts+',this)" value=""/></div></div></div>').appendTo("#ch .price-box");

					
		        }
		   //      else { 
		   //          alert('Prize count was exceeded!!!'); 
		   //          var sub=counts+1;		
		   //          $(".textto"+sub).val('');  
		   //          $(".textto"+sub).prop("onchange", null).off("change");
					// $(".textpercent"+sub).prop("onchange", null).off("change");	 
					// $("#btnclick").prop("onclick", null).off("click"); 
		   //          return false;
		   //      } 
		    }
		} 
		function generates() {
 
			var units = $("#pcount").val();
			$("#ch .price-box").html('');
			var prize_type = $('#prize_type').val();

		/*	var validate = check_fields();
			
			if(validate == false){
			  return false;	
			}
		*/
			if(prize_type != '' && prize_type == 1) {
				for (var count = 1; count <= units; count++) { 
				  $('<div class="col-md-4 col-sm-6"><div> Price '+count+' </div><input class="form-control" type="text" name="splitup[]" value="" required="required"/></div>').appendTo("#ch .price-box");
				} 
			}
			else if(prize_type != '' && prize_type == 2) {
			 $("#ch .price-box").empty();
			 var count =1;
             if(count == 1){
                var button = '<input type="button" value="+" class="btn btn-primary btn-sm" id="btnclick" onclick="showadd('+count+',this.value)">';
             }
             else{
                var button ='';
             }

	   $('<div class="row row'+count+'" style="margin-left:0px;"><div class="col-md-4 col-sm-4 col-12"><div class="labelt"> Range From </div><input class="form-control textfrom'+count+'" type="text"  name="from[]" value="0" readonly=""/></div><div class="col-md-4 col-sm-4 col-12"><div class="labelt"> Range To </div><input required="required" class="form-control textto'+count+'" type="text" name="to[]" onchange="showadd('+count+',this)" value=""></div><div class="col-md-4 col-sm-4 col-12"><div class="labelt"> Percentage </div><input required="required" class="form-control textpercent'+count+'" type="text" name="percentage[]" id="percentage[]" value=""/></div></div></div><div></div>').appendTo("#ch .price-box");

	   $('.add_rannge_btn').append(button);
			} 
		}  
        function check_fields() {
            var pcount = $("#pcount").val();
            var prize_type = $('#prize_type').val();
	    var pool = $('#pool').val();
	    
	    
	    if(!pcount.match(/^\d+/) || pcount ==''){
		alert('please enter the prize count!');
		return false;
	    }
	    if(!prize_type.match(/^\d+/) || prize_type =='') {
		alert('please enter the prize type!'); 
		return false;
	    }	
	    if(!pool.match(/^\d+/) || pool =='') {
		alert('please enter the prize pool!');
		return false;
	    }
	   return true;	
	    
        } 
	</script>
