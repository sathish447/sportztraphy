<footer class="footer">
        <div class="row align-items-center justify-content-xl-between">
          <div class="col-xl-6">
            <div class="copyright text-center text-xl-left text-muted">
              © {{ date("Y") }} <a href="https://fantasy.demozab.com" class="font-weight-bold ml-1" target="_blank">Fantasy</a>
            </div>
          </div>
       
        </div>
      </footer>
    </div>
	</div>
  <!--   Core   -->
  <script src="{{ url('/assets/js/plugins/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ url('/assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

  <!--   Optional JS   -->


<script src="{{ url('/assets/js/plugins/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script> 
  <script src="{{ url('/assets/js/plugins/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ url('/assets/js/plugins/chart.js/dist/Chart.extension.js') }}"></script>
  <script src="{{ url('/assets/js/pie-chart-source.js') }}"></script>
  {{-- <script src="{{ url('/assets/js/pie-chart.js') }}"></script> --}}
  <!--   Argon JS   -->
  <script src="{{ url('/assets/js/argon-dashboard.min.js?v=1.1.1') }}"></script>
  <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
  <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>

  <script>
    window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "argon-dashboard-free"
      });
  </script>




<script>
  $('#loding').hide();

  $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
   $(this).val($(this).val().replace(/[^0-9\.]/g,''));
   if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});

$("#proof_upload1").change(function() {
    var limit_size = 1048576;
    var photo_size = this.files[0].size;
    if(photo_size > limit_size){
        $("#kyc_btn").attr('disabled',true);
        $('#proof_upload1').val('');
        alert('Image Size Larger than 1MB');
    }
    else
    { 
        $("#proof_upload1").text(this.files[0].name);
        $("#kyc_btn").attr('disabled',false);
        
        var file = document.getElementById('proof_upload1').value;
        var ext = file.split('.').pop();
        switch(ext) {
              case 'jpg':
              case 'JPG':
              case 'Jpg':
              case 'jpeg':
              case 'JPEG':
              case 'Jpeg':
              case 'png':
              case 'PNG':
              case 'Png':
              readURL8(this);
              break;
              default:
              alert('Upload your proof like JPG, JPEG, PNG');
              break;
        }
    }
});

    function readURL8(input) {
    var limit_size = 1048576;
    var photo_size = input.files[0].size;
    if(photo_size > limit_size){
        alert('Image size larger than 1MB');
  }
  else
  {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
  }
}

$("#proof_upload2").change(function() {
    var limit_size = 1048576;
    var photo_size = this.files[0].size;
    if(photo_size > limit_size){
        $("#kyc_btn").attr('disabled',true);
        $('#proof_upload2').val('');
        alert('Image Size Larger than 1MB');
    }
    else
    { 
        $("#proof_upload2").text(this.files[0].name);
        $("#kyc_btn").attr('disabled',false);
        
        var file = document.getElementById('proof_upload2').value;
        var ext = file.split('.').pop();
        switch(ext) {
            case 'jpg':
            case 'JPG':
            case 'Jpg':
            case 'jpeg':
            case 'JPEG':
            case 'Jpeg':
            case 'png':
            case 'PNG':
            case 'Png':
              readURL7(this);
            break;
            default:
              alert('Upload your proof like JPG, JPEG, PNG');
            break;
        }
    }
});

function readURL7(input) {
var limit_size = 1048576;
var photo_size = input.files[0].size;
if(photo_size > limit_size){
    alert('Image Size Larger than 1MB');
}
else
{
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#doc3').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
}
}

  $('#accountname').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
     event.preventDefault();
     return false;
   }
 });
  $(function(){

    $('.adminaddress').keyup(function()
    {
      var yourInput = $(this).val();
      re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
      var isSplChar = re.test(yourInput);
      if(isSplChar)
      {
        var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $(this).val(no_spl_char);
      }
    });

  });

  $('.datepicker4').each(function(e) {
    e.datepicker({
      format: 'yy-mm-dd',
      autoclose: true
    });
    $(this).on("click", function() {
      e.datepicker("show");
    });
  });
  function isNumberKey(evt)
  {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 
      && (charCode < 48 || charCode > 57))
     return false;

   return true;
 }

 $(document).ready(function () {
  //called when key is pressed in textbox
  $("#numberonly").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
        return false;
      }
    });
});
 $("#reason").on("keydown", function (e) {
  var c = $("#reason").val().length;
  if(c == 0)
    return e.which !== 32;
});

</script>

  <script>
  function readURL1(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#doc1').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$("#file-upload1").change(function() {
  $("#file-name1").text(this.files[0].name);
  readURL1(this);
});

  function readURL2(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#doc2').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$("#file-upload2").change(function() {
  $("#file-name2").text(this.files[0].name);
  readURL2(this);
});
</script>


<script>
  $('.dot').on('input', function() {
    this.value = this.value
.replace(/[^\d.]/g, '') // numbers and decimals only
.replace(/(\..*)\./g, '$1') // decimal can't exist more than once
});
</script>


<!--  Delete Bank Detals -->
<script>
  
  function deleteBank(id)
{
    $('#bank_id').val(id);
}

$('#confirm_delete_bank_details').on('submit', function(){
    $('#confirm_btn').attr('disabled', true);
    var formData = $('#confirm_delete_bank_details').serialize();    

    $.ajax({
        type: "get", 
        url: "{{ url('admin/delete_bank') }}",
        dataType: "json",
        data: formData,
        success: function(data){
          $('#confirm_delete_bank_details').hide();
            $('#confirm_result').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success! </strong>'+data.msg+'</div>');
            window.setTimeout(function() {
                window.location.href = "{{ url('admin/bank') }}";
            }, 1000);
        }
    });
    return false;
});

</script>


</body>

</html>
