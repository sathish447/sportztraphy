import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { error } from 'util';

@Component({
  selector: 'app-forgotpassword',
  templateUrl: './forgotpassword.component.html',
  styleUrls: ['./forgotpassword.component.css']
})
export class ForgotpasswordComponent implements OnInit {

  public form = { 
    email:null 
  }

  public error:any = []; // Error Variable for Laravel Server side validation
  public forgotError:any = null; // Error Variable for Common Errors

  constructor(
    private common:CommonService,
    private router:Router,
    private Notify:ToastrService
  ) { }

  ngOnInit() {
  }

  /* Forgot Password Form Submit Event  */

  onSubmit() 
  {
    this.error = [];
    this.common.forgotpasswordsubmit(this.form).subscribe(
          data => this.handleResponse(data),
          error => this.handleError(error),
      );
  }

/* Handling Respose */
  handleResponse(data)
  {
    this.Notify.success('Email sent Successfully','Success');
    this.router.navigate(['login']);
    this.error = [];
  }

/* Handling Error Respose */

  handleError(error)
  {
    console.log(error);
    if(error.error.errors)
      {
        this.error = error.error.errors;
      }
      // this.forgotError = error.error.login;
  }

}
