import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { CommonService } from 'src/app/services/common.service';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';

@Component({ 
  selector: 'app-resetpassword',
  templateUrl: './resetpassword.component.html',
  styleUrls: ['./resetpassword.component.css']
})
export class ResetpasswordComponent implements OnInit {


  resetpasswordForm: any;

  public error:any = []; // Error Variable for Laravel Server side validation
  public resetError:any = null; // Error Variable for Common Errors
  public data;


  constructor(
    private Notify: ToastrService,
    private common: CommonService,
    private router: Router,
    private activatedRoute: ActivatedRoute
  ) { }
  

  
  ngOnInit() {
    this.activatedRoute.params.subscribe(params => {
        this.data = params.forgot_secret;
      });
  }

  onSubmit(Form: NgForm)
  {
    this.resetpasswordForm = {
        'password': Form.value.password,
        'confirmpassword':Form.value.confirmpassword,
        'forgot_secrect': this.data
};
console.log(this.resetpasswordForm);
    this.error = [];
      this.common.resetformsubmit(this.resetpasswordForm).subscribe(
      data => this.handleResponse(data),
      error => this.Notify.error(error.error.error)
    );
  }  

  /* Handling Respose */
  handleResponse(data)
  {
    if(data.success == true){
      this.Notify.success(data.message,'Success');
      this.router.navigateByUrl('login');
    }
    else{
    this.Notify.error(data.message,'Failled');
    this.ngOnInit();
    this.error = [];
  }

}
}
