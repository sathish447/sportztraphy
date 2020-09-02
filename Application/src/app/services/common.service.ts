import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { TokenService } from './token.service';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class CommonService {

  constructor(
    private http: HttpClient,
    private router: Router,
    private Token: TokenService,
    private Auth: AuthService,

  ) { }

  //private baseUrl = 'https://consummo.com/sportztrophyapi/public/api';
  private baseUrl = 'http://localhost/angular-node-crypto/laravel/public/api/';
  

  //private baseUrl = 'http://localhost:8000/api';
  condiction: any;

  user() {
    return this.http.get(`${this.baseUrl}/users`);
  }

  register(data) {
    return this.http.post(`${this.baseUrl}/register`, data);
  }

  otpverify(data) {
    return this.http.post(`${this.baseUrl}/otpverify`, data);
  }

  password(data) {
    // console.log(data);
    return this.http.post(`${this.baseUrl}/password`, data);
  }

  login(data) {
    this.Auth.changeStatus(false);
    this.Token.remove();
    return this.http.post(`${this.baseUrl}/login`, data);
  }

  forgotpasswordsubmit(data) {
    return this.http.post(`${this.baseUrl}/forgotpassword_submit`, data);
  }

  // payment(data) {
  //   return this.http.post(`https://test.cashfree.com/billpay/checkout/post/submit`, data);
  // }

  resetformsubmit(data) {
    return this.http.post(`${this.baseUrl}/resetpassword_submit`, data);
  }

  gmaillogin(data) {
    return this.http.post(`${this.baseUrl}/gmaillogin`, data);
  }

  facebooklogin(data) {
    return this.http.post(`${this.baseUrl}/facebooklogin`, data);
  }

  mobile_request(data) {
    return this.http.post(`${this.baseUrl}/mobile_request`, data);
  }

  facebook_mobile_request(data) {
    return this.http.post(`${this.baseUrl}/facebook_mobile_request`, data);
  }

  gmailotpverify(data) {
    return this.http.post(`${this.baseUrl}/gmailotpverify`, data);
  }

  getUserBalance(data) {
    return this.http.get(`${this.baseUrl}/getuser_balance`, data);
  }

  userdata() {
    return this.http.get(`${this.baseUrl}/user_data`);
  }

  sendVerificationMail() {
    return this.http.get(`${this.baseUrl}/send_verification_mail`);
  }

  useraddBalance(data) {
    return this.http.post(`${this.baseUrl}/adduserbalance`, data);
  }

  panSubmit(data) {
    return this.http.post(`${this.baseUrl}/pan_submit`, data);
  }

  banksubmit(data) {
    return this.http.post(`${this.baseUrl}/bank_submit`, data);
  }

  playercredits(data) {
    return this.http.post(`${this.baseUrl}/playercredits`, data);
  }

  changepasswordsubmit(data) {
    return this.http.post(`${this.baseUrl}/changepassword`, data);
  }

  profilesubmit(data) {
    return this.http.post(`${this.baseUrl}/profile_info_submit`, data);
  }

  testcash(data) {
    return this.http.post(`${this.baseUrl}/testcash`, data);
  }

  allmatch() {
    return this.http.get(`${this.baseUrl}/allmatch`);
  }
  
  mymatch() {
    return this.http.get(`${this.baseUrl}/mymatch`);
  }
  mymatchdetails() {
    return this.http.get(`${this.baseUrl}/mymatchdetails`);
  }
  mymatchcount() {
    return this.http.get(`${this.baseUrl}/mymatchcount`);
  }
  allcontest(data) {
    return this.http.post(`${this.baseUrl}/allcontest`,data);
  }
  userparticipentdetails(data){
    return this.http.post(`${this.baseUrl}/participatedetails`,data);
  }
  selectedContest(data){
    return this.http.post(`${this.baseUrl}/selectcontest`, data);
  }
  selectmatchdetails(data){
    return this.http.post(`${this.baseUrl}/matchdetails`, data);
  }

  saveteam(data){
    return this.http.post(`${this.baseUrl}/saveteam`, data);
  }

  updateteam(data){
    return this.http.post(`${this.baseUrl}/updateteam`, data);
  }


  selectcvcteam(data) {
    return this.http.post(`${this.baseUrl}/selectcvcteam`,data);
  }

  savecvcteam(data){
    return this.http.post(`${this.baseUrl}/savecvcteam`,data);
  }

  withdrawSubmit(data) {
    return this.http.post(`${this.baseUrl}/Withdraw_update`, data);
  }

  getTrans(data) {
    return this.http.post(`${this.baseUrl}/get_user_transaction`,data);
  }


  getRankDetails(data) {
    return this.http.post(`${this.baseUrl}/rank`,data);
  }

  getcreateteamdetails(data) {
    return this.http.post(`${this.baseUrl}/createTeam`,data);
  }

  geteditteamdetails(data) {
    return this.http.post(`${this.baseUrl}/editTeam`,data);
  }


  joincontest(data) {
    return this.http.post(`${this.baseUrl}/joincontest`,data);
  }

  myviewteam(data) {
    return this.http.post(`${this.baseUrl}/myviewteam`,data);
  }
  selectviewteam(data){
    return this.http.post(`${this.baseUrl}/selectviewteam`,data);
  }
  
  entryteamdetails(data) {
    return this.http.post(`${this.baseUrl}/entryteamdetails`,data);
  }
  userContest(data) {
    return this.http.post(`${this.baseUrl}/userContest`,data);
  }
  contestinfo(data) {
    return this.http.post(`${this.baseUrl}/contestinfo`,data);
  }

  getelevenplayer(data) {
    return this.http.post(`${this.baseUrl}/getelevenplayers`,data);
  }
  getplayerdetails(data){
    return this.http.post(`${this.baseUrl}/getplayerdetail`,data);
  }
  invitefriends(data){
    return this.http.post(`${this.baseUrl}/invite_friends`,data);
  }


  logout() {
    this.Auth.changeStatus(false);
    this.router.navigateByUrl('/login');
    this.Token.remove();
  }

  setValues() {
    this.user().subscribe((all) => {
      this.condiction = all;
      // localStorage.setItem('kyc',this.condiction.kyc_verify); 
      // localStorage.setItem('google',this.condiction.google2fa_status);
    });
    return this.condiction;
  }


 fantasypoints(data) {
    return this.http.post(`${this.baseUrl}/fantasypoints`, data);
  }
  profileupload(data) {
    return this.http.post(`${this.baseUrl}/profile_image_upload`, data);
  }
}
