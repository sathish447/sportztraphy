import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { Observable, timer } from 'rxjs';
import { take, map } from 'rxjs/operators';
import { AuthService } from 'src/app/services/auth.service';
import { TokenService } from 'src/app/services/token.service';

import 'rxjs/add/observable/timer';
import 'rxjs/add/operator/finally';
import 'rxjs/add/operator/takeUntil';
import 'rxjs/add/operator/map';

@Component({
  selector: 'app-otp',
  templateUrl: './otp.component.html',
  styleUrls: ['./otp.component.css']
})
export class OtpComponent implements OnInit {

    //get form value
    timeLeft: number = 30;
    otpForm: any;
    countdown: number;
    subscribeTimer: any;
    interval: any;


  public error:any = []; // Register Server Side Validation Error Variable
  public date;

  constructor(
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private Token: TokenService,
    private Auth: AuthService,
  ) { }

  ngOnInit() {
    this.activatedRoute.params.subscribe(params => {
    // get the username out of the route params
      this.date = params.msg_id;
    });

    this.startCountdownTimer();
    this.startTimer();
  }

  startTimer() {
    this.interval = setInterval(() => {
      if(this.timeLeft > 0) {
        this.timeLeft--;
      }
      else {
        clearInterval(this.interval);
        this.timeLeft = 30;
      }
    },1000)
  }

  startCountdownTimer() {
    const source = timer(1000, 2000);
    const abc = source.subscribe(val => {
      console.log(val, '-');
      this.subscribeTimer = this.timeLeft - val;
    if(this.timeLeft == 0)
    {
      clearInterval(this.interval);
    }
  });

    // const interval = 1000;
    // const duration = 10 * 1000;
    // const stream$ = Observable.timer(0, interval)
    //   .takeUntil(Observable.timer(duration + interval))
    //   .map(value => duration - value * interval);
    // stream$.subscribe(value => this.countdown = value);
  } 

  onSubmit(Form: NgForm) {
    this.otpForm = {
        'login_otp': Form.value.login_otp,
        'otp_session_id': this.date
    };
    this.Comman.otpverify(this.otpForm).subscribe(
      data => this.handleResponse(data),
      error => this.handleError(error)
    );
  }

   /* Handling Response*/
   handleResponse(data) {

    console.log(data.response);

    if (data.status === true) {
      this.Token.handle(data.response['access_token']);
      this.Auth.changeStatus(true);
      this.Notify.success(data.message);
      this.router.navigateByUrl('/allmatch');
    } else if (data.status === false) {
      this.Notify.error(data.message);
    }

    // if (data["status"] === 'success') {
    //   this.Token.handle(data.access_token);
    //   this.Auth.changeStatus(true);
    //   this.Notify.success('OTP Verified Successfully');
    //   this.router.navigateByUrl('/allmatch');
    //   this.router.navigate(['/allmatch']);
    // } else if (data["status"] === 'error') {
    //   this.Notify.error('Invalid OTP.');
    // }
 }

 /* Handling Errors*/
 handleError(error) {
   this.error = error.error.errors;
 }

}
