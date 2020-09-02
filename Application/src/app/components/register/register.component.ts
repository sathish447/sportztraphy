import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import * as $ from 'jquery'; 
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router,ActivatedRoute } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { TokenService } from 'src/app/services/token.service';

@Component({ 
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {

  auth2: any;

  @ViewChild('gmailLoginRef', {static: true }) loginElement: ElementRef;
  public form = {
    phone:null,
    email:null,
    password:null,
    referral:null,
    reqtype:null,
    registertype:null,
    registerdevice:null,
    confirm_password:null
  }

  public gmaildata = {
    token     : null,
    id        : null,
    name      : null,
    image_url : null,
    email     : null,
  }
  referral: any;

  public error:any = []; // Register Server Side Validation Error Variable
  

  constructor(
    private Token: TokenService,
    private Auth: AuthService,
    private Comman: CommonService,
    private Toastr: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute
  ) { }

  ngOnInit() {
    this.activatedRoute.params.subscribe(params => {
      console.log(params);
      this.form.referral = params.invite_code;
    });
    // $('.text-danger').parent().parent().addClass('text-danger');
    this.googleSDK();
    this.fbLibrary();
  }

  onSubmit() {
    
     this.form.reqtype = 'web';
     this.form.registertype = 'normal';
     this.form.registerdevice = 'web';

     this.Comman.register(this.form).subscribe(
       data => this.handleResponse(data),
       error => this.handleError(error)
     );
  }

    /* Handling Response*/
    handleResponse(data) {
      console.log(data);
      if (data.status === true) {
        this.Toastr.success(data.message);
          // this.router.navigate(['otp', data.response['msg_session_id']]);
          this.router.navigate(['login']);
      } else if (data.status === false) {
          this.Toastr.error(data.message);
      } 
      else if (data.password_status === false) {
        this.Toastr.error(data.message);
      } 

      // if (data["status"] === 'sms_success') {
      //     this.Toastr.success('OTP Send Your Registered Mobile Number.');
      //     this.router.navigate(['otp', data.msg_session_id]);
      //   // this.router.navigateByUrl('otp');
      // } else if (data["status"] === 'sms_error') {
      //     this.Toastr.error('Invalid Mobile Number.');
      // }
      // document.location.href = 'http://localhost/otc-user/register-message.html';
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
        this.Toastr.success(data.message);
        this.router.navigate(['mobile-number',data['response']['details']['_id'],data['response']['details']['gmailauthtoken'],data['response']['details']['name'],data['response']['details']['email'],data['response']['details']['image'],data['session_id']]);
      } else if(data['response']["register_status"] === true) {
        this.Token.handle(data['response']['token']['access_token']);
        this.Auth.changeStatus(true);
        this.Toastr.success(data.message);
        this.router.navigate(['allmatch']);
      } else if(data['response']["register_status"] === false) {
        this.Token.handle(data['response']['token']);  
        this.Auth.changeStatus(true);
        this.Toastr.success(data.message);
        this.router.navigate(['mobile-number',data['response']['details']['_id'],data['response']['details']['gmailauthtoken'],data['response']['details']['name'],data['response']['details']['email'],data['response']['details']['image'],data['session_id']]);
      } else if(data["otp_status"] === false) {
        this.Token.handle(data['response']['token']);  
        this.Auth.changeStatus(true);
        this.Toastr.success(data.message);
        this.router.navigate(['otp', data['response']['msg_session_id']]);
      } else if(data["otp_status"] === true) {
        this.Token.handle(data['response']['token']);  
        this.Auth.changeStatus(true);
        this.Toastr.success(data.message);
        this.router.navigate(['otp', data['response']['msg_session_id']]);
      } else if(data["gmail_status"] === false) {
        this.Toastr.error(data.message);
      }
      else if (data['gmail_error'] === 'gmail_error'){
        this.Toastr.error('Your Email Already Facebook Registered..');
      }
   }

    signinWithGoogle(){
    }

  // Facebook Integrate and user details
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
    // console.log(data.access_token);
    if (data["mobile_status"] === false) {
      this.Toastr.success(data.message);
      this.router.navigate(['mobile-number',data['response']['details']['_id'],data['response']['details']['first_name'],data['response']['details']['last_name'],data['response']['details']['email'],data['session_id']]);
    } else if(data['response']["register_status"] === true) {
      this.Token.handle(data['response']['token']['access_token']);
      this.Auth.changeStatus(true);
      this.Toastr.success(data.message);
      this.router.navigate(['allmatch']);
    } else if(data['response']["register_status"] === false) {
      this.Token.handle(data['response']['token']);  
      this.Auth.changeStatus(true);
      this.Toastr.success(data.message);
      this.router.navigate(['mobile-number',data['response']['details']['_id'],data['response']['details']['first_name'],data['response']['details']['last_name'],data['response']['details']['email'],data['session_id']]);
    } else if(data["otp_status"] === false) {
      this.Token.handle(data['response']['token']);  
      this.Auth.changeStatus(true);
      this.Toastr.success(data.message);
      this.router.navigate(['otp', data['msg_session_id']]);
    } else if(data["facebook_status"] === false) {
      this.Toastr.error(data.message);
    }
    // if (data['response']["phone_verify"] === false) {
    //   this.Toastr.success(data['response']['message']);
    //   this.router.navigate(['otp', data.response['msg_session_id']]);
    // } else if(data["register_status"] === true) {
    //   this.Token.handle(data.response['id']);
    //   this.Auth.changeStatus(true);
    //   this.Toastr.success(data.message);
    //   this.router.navigate(['allmatch']);
    // } else if(data["register_status"] === false) {
    //   this.Token.handle(data.response['token']);  
    //   this.Auth.changeStatus(true);
    //   this.Toastr.success(data.message);
    //   this.router.navigate(['mobile-number',data.response['id'],data.response['first_name'],data.response['last_name'],data.response['email'],data.response['session_id']]);
    // }
    // else if(data['facebook_error'] === false){
    //   this.Toastr.error(data.message);
    // }

  }

  /* Handling Errors*/
  handleError(error) {
    this.error = error.error.errors;
  }
}
