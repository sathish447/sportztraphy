import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { AuthService } from 'src/app/services/auth.service';
import { TokenService } from 'src/app/services/token.service';

@Component({
  selector: 'app-password',
  templateUrl: './password.component.html',
  styleUrls: ['./password.component.css']
})
export class PasswordComponent implements OnInit {

   //get form value
   otpForm: any;

   public error: any = []; // Register Server Side Validation Error Variable
   // tslint:disable-next-line: variable-name
   public login_email: any;


  constructor(
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private Token: TokenService,
    private Auth: AuthService,
    private activatedRoute: ActivatedRoute
  ) { }

  ngOnInit() {
    this.activatedRoute.params.subscribe(params => {
    // get the username out of the route params
      this.login_email = params.email;
    });
  }

  onSubmit(Form: NgForm) {
    this.otpForm = {
        'password': Form.value.password,
        'email': this.login_email
    };
    this.Comman.password(this.otpForm).subscribe(
      data => this.handleResponse(data),
      error => this.handleError(error)
    );
    }

     /* Handling Response*/
   handleResponse(data) {
    if (data.status === true) {
      this.Token.handle(data.response['access_token']);
      this.Auth.changeStatus(true);
      this.Notify.success(data.message);
      this.router.navigateByUrl('/allmatch');
    } else if (data.status === false) {
      this.Notify.error(data.message);
    } else if (data['status'] === 'success') {
      this.Token.handle(data.access_token);
      this.Auth.changeStatus(true);
      this.Notify.success('Login Successfully..');
      this.router.navigateByUrl('allmatch');
    } else if (data['status'] === 'Invalid_credentials') {
      this.Notify.error('Invalid Email or Password.');
    } else if (data.status === 'sms_success') {
      this.Notify.success('OTP Send your Registered Mobile Number.');
      this.router.navigate(['otp', data.msg_session_id]);
    }
 }

 /* Handling Errors*/
 handleError(error) {
   this.error = error.error.errors;
 }




}
