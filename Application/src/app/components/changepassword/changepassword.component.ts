import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-changepassword',
  templateUrl: './changepassword.component.html',
  styleUrls: ['./changepassword.component.css'] 
})
export class ChangepasswordComponent implements OnInit {

  changepasswordForm: any;  

  public error:any = []; // Error Variable for Laravel Server side validation
  public resetError:any = null; // Error Variable for Common Errors
  public date;

  constructor(
    private Notify: ToastrService,
    private common: CommonService,
    private router: Router,
    private Toastr: ToastrService,
    private activatedRoute: ActivatedRoute
  ) { }
 
  ngOnInit() {
  }

  onSubmit(Form: NgForm)
  {
    this.changepasswordForm = {
        'oldpassword': Form.value.oldpassword,
        'newpassword': Form.value.password,
        'confirmpassword':Form.value.confirmpassword,
    };
    this.error = []; 
    this.common.changepasswordsubmit(this.changepasswordForm).subscribe(
    data => this.handleResponse(data),
    error => this.handleError(error)
  );

}

  /* Handling Respose */
  handleResponse(data)
  {
    if (data["status"] === true) {
      this.Toastr.success('Password changed successfully.');
      this.ngOnInit();
      // this.router.navigateByUrl('otp');
    } else if (data["status"] === 'error') {
      this.Toastr.error(data.message);
    } else if (data.status === false) {
      this.Toastr.error(data.message);
    } else if (data["status"] === 'old_password') {
      this.Toastr.error(data.message);
    }else if (data["confirm_status"] === 'false') {
      this.Toastr.error(data.message);
    }
  }

   /* Handling Errors*/
   handleError(error) {
    this.error = error.error.errors;
  }
}
