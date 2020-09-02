import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import * as $ from 'jquery';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { TokenService } from 'src/app/services/token.service';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  auth2: any;

  @ViewChild('gmailLoginRef', {static: true }) loginElement: ElementRef;

  public form = {
    login_input:null,
    password:null,
    phone:null,
    email:null,
    referral:null
  }

  public gmaildata = { 
    token     : null,
    id        : null,
    name      : null,
    image_url : null,
    email     : null,
  }

  public error:any = []; // Register Server Side Validation Error Variable

  constructor(
    private Comman: CommonService,
    private Notify: ToastrService,
    private Token:TokenService,
    private Auth: AuthService,
    private router: Router
  ) { }

  ngOnInit() {
    this.googleSDK();
    this.fbLibrary();
  }

  onSubmit() {
    this.Comman.login(this.form).subscribe(
      data => this.handleResponse(data),
      error => this.handleError(error)
    );
 }

  /* Gmail User Details */
  prepareLoginButton() {
    this.auth2.attachClickHandler(this.loginElement.nativeElement, {} ,

      (googleUser) => {
        let profile = googleUser.getBasicProfile();
        this.gmaildata = { 
          token             : googleUser.getAuthResponse().id_token,
          id                : profile.getId(),
          name              : profile.getName(),
          image_url         : profile.getImageUrl(),
          email             : profile.getEmail(),
        } 
        
        this.Comman.gmaillogin(this.gmaildata).subscribe(
          data => this.gmailResponse(data),
        );
      });
  }
  googleSDK() {
    
    window['googleSDKLoaded'] = () => {
      window['gapi'].load('auth2', () => {
        this.auth2 = window['gapi'].auth2.init({
          client_id: '334214402042-30kevlfr0hk2frs013r6563i5objgie0.apps.googleusercontent.com',
          cookiepolicy: 'single_host_login',
          scope: 'profile email'
        });
        this.prepareLoginButton();
      });
    }
  
    (function(d, s, id){
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) {return;}
      js = d.createElement(s); js.id = id;
      js.src = "https://apis.google.com/js/platform.js?onload=googleSDKLoaded";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'google-jssdk'));
  }

   /* Gmail Response*/
   gmailResponse(data) {
    console.log(data['response']['details']);
     // this.router.navigateByUrl('gmaillogin');
    if (data["mobile_status"] === false) {
       this.Notify.success(data.message);
       // tslint:disable-next-line: max-line-length
       this.router.navigate(['mobile-number',data['response']['details']['_id'],data['response']['details']['gmailauthtoken'],data['response']['details']['name'],data['response']['details']['email'],data['response']['details']['image'],data['session_id']]);
     } else if(data['response']["register_status"] === true) {
       this.Token.handle(data['response']['token']['access_token']);
       this.Auth.changeStatus(true);
       this.Notify.success(data.message);
       this.router.navigate(['allmatch']);
     } else if(data['response']["register_status"] === false) {
       this.Token.handle(data['response']['token']);  
       this.Auth.changeStatus(true);
       this.Notify.success(data.message);
       // tslint:disable-next-line: max-line-length
       this.router.navigate(['mobile-number',data['response']['details']['_id'],data['response']['details']['gmailauthtoken'],data['response']['details']['name'],data['response']['details']['email'],data['response']['details']['image'],data['session_id']]);
     } else if(data["otp_status"] === false) {
       this.Token.handle(data['response']['token']);  
       this.Auth.changeStatus(true);
       this.Notify.success(data.message);
       this.router.navigate(['otp', data['response']['msg_session_id']]);
     } else if(data["otp_status"] === true) {
       this.Token.handle(data['response']['token']);  
       this.Auth.changeStatus(true);
       this.Notify.success(data.message);
       this.router.navigate(['otp', data['response']['msg_session_id']]);
     } else if(data["gmail_status"] === false) {
       this.Notify.error(data.message);
     }
     else if (data['gmail_error'] === 'gmail_error'){
       this.Notify.error('Your Email Already Facebook Registered..');
     }
  }

   signinWithGoogle(){
  }

 shoSuccess(){
  this.Notify.success('Login Successful','Success');
 } 

   /* Handling Response*/
   handleResponse(data) {
    // tslint:disable-next-line: whitespace
    // tslint:disable-next-line: align
    if (data.status === true) {
      if (data.response['type'] == 'email_login') {
        this.router.navigate(['password', data.response['email']]);
      } else if (data.response['type'] == 'phone_login') {
        this.Notify.success(data.message);
        this.router.navigate(['otp', data.response['msg_session_id']]);
      }
    } else if (data.status === false) {
      this.Notify.error(data.message);
    } else if (data.status === 'sms_success'){
      this.Notify.success('OTP Send your Registered Mobile Number.');
      this.router.navigate(['otp', data.msg_session_id]);
    } else if (data.status === 'sms_error') {
      this.Notify.error('Invalid Mobile Number.');
    } else if (data.status === 'login_details_error') {
        this.Notify.error('Invalid Email or Password.');
    } else if (data.status === 'Invalid_credentials') {
      this.Notify.error('Invalid Email or Password.');
    } else if (data.status === 'email_login') {
      this.router.navigate(['password', data.email]);
    } else if (data.status === 'Invalid_details') {
      this.Notify.error('Invalid Email or Number.');
    }
    
  }

//Facebook Integrate and user details
fbLibrary() {
  (window as any).fbAsyncInit = function() {
    window['FB'].init({
      appId      : '430754704284840',
      cookie     : true,
      xfbml      : true,
      version    : 'v3.1'
    });
    window['FB'].AppEvents.logPageView();
  };

  (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

}
login() {

    window['FB'].login((response) => {
      if (response.authResponse) {

          window['FB'].api('/me', {
            fields: 'last_name, first_name, email'
          }, (userInfo) => {
            this.Comman.facebooklogin(userInfo).subscribe(
              data => this.facebookResponse(data),
            );
          });
        } else {
          console.log('User login failed');
        }
      }, {scope: 'email'});
    }

      //facebook response
    facebookResponse(data) {
      if (data["mobile_status"] === false) {
        this.Notify.success(data.message);
        this.router.navigate(['mobile-number',data['response']['details']['_id'],data['response']['details']['first_name'],data['response']['details']['last_name'],data['response']['details']['email'],data['session_id']]);
      } else if(data['response']["register_status"] === true) {
        this.Token.handle(data['response']['token']['access_token']);
        this.Auth.changeStatus(true);
        this.Notify.success(data.message);
        this.router.navigate(['allmatch']);
      } else if(data['response']["register_status"] === false) {
        this.Token.handle(data['response']['token']);  
        this.Auth.changeStatus(true);
        this.Notify.success(data.message);
        this.router.navigate(['mobile-number',data['response']['details']['_id'],data['response']['details']['first_name'],data['response']['details']['last_name'],data['response']['details']['email'],data['session_id']]);
      } else if(data["otp_status"] === false) {
        this.Token.handle(data['response']['token']);  
        this.Auth.changeStatus(true);
        this.Notify.success(data.message);
        this.router.navigate(['otp', data['msg_session_id']]);
      } else if(data["facebook_status"] === false) {
        this.Notify.error(data.message);
      }
    }

  /* Handling Errors*/

  handleError(error) {
    this.error = error.error.errors;
  }

}
