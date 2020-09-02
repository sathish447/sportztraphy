import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { FormBuilder, FormGroup, Validators, FormArray, FormControl } from '@angular/forms';

declare var Razorpay: any;

@Component({
  selector: 'app-myaccount',
  templateUrl: './myaccount.component.html',
  styleUrls: ['./myaccount.component.css']
})
export class MyaccountComponent implements OnInit {

  testcash :any;
  //get form value
  addMoneyForm1: any;
  walletbalance: any   = [];  // user total wallet balance Variable
  bonusbalance: any    = [];  // user bonus wallet balance Variable
  depositbalance: any  = [];  // user deposit wallet balance Variable
  winningbalance: any  = [];  // user winnings wallet balance Variable
  user_email: any      = [];  // user email variable
  user_teamname: any   = [];  // user teamname variable
  user_phone: any      = [];  // user phone number variable
  addmoney: any        = [];
  options: any         = [];
  useremail: any       = [];
  userphone: any       = [];
  userteamname: any    = [];
  getBaseUrl: any      = [];
  getUsers: any        = [];
  mobileverify: any    = [];
  emailverify: any     = [];
  panverify: any       = [];
  bankverify: any      = [];
  winningamt:any       = [];

  public addMoneyForm = {  // get pan form value
    addmoney : null,
  }

  public error: any = []; // Register Server Side Validation Error Variable
  rzp1: any;

  constructor(
    private http: HttpClient,
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
  ) { }

  ngOnInit() {
    this.getUsetBalance();
  }

  dynamicaddmoney(value) {
    this.addMoneyForm.addmoney = value;
  }

  onSubmit() {
    this.Comman.otpverify(this.addMoneyForm).subscribe(
      data => this.handleResponse(data),
      error => this.handleError(error)
    ); 
  }

  getUsetBalance() {
    this.Comman.getUserBalance(this.addMoneyForm).subscribe( 
      data => {
        this.walletbalance.push(data['balance']);
        this.bonusbalance.push(data['bonus']);
        this.depositbalance.push(data['deposit']);
        this.winningbalance.push(data['winnings']);
        this.useremail.push(data['useremail']);
        this.userphone.push(data['userphone']);
        this.userteamname.push(data['customername']);
        this.user_email     = data['useremail'];
        this.user_phone     = data['userphone'];
        this.user_teamname  = data['customername'];
        this.emailverify    = data['email_verify'];
        this.mobileverify   = data['mobile_verify'];
        this.panverify      = data['pan_verify'];
        this.bankverify     = data['bank_verify'];
        this.winningamt     = data['winnings'];
        this.addmoney     = data['addmoney'];
      }
    );
  }
  initPay(Form: NgForm) {
    // console.log(Form.value.user_email);
    const formData = new FormData(); 
    console.log(Form.value.addmoney);
    formData.append('secretkey', 'b9bdf5bffd470e5d06492dacf8bb66f3ed670603');
    formData.append('appId', '10554101503d1cfca147fe23645501');
    formData.append('addmoney', Form.value.addmoney);
    formData.append('orderAmount', Form.value.addmoney);
    formData.append('orderCurrency', 'INR');
    formData.append('orderNote', 'test');
    formData.append('customerName', this.user_teamname);
    formData.append('customerPhone', this.user_phone);
    formData.append('customerEmail', this.user_email);
    formData.append('returnUrl', 'https://fantasy.demozab.com/myaccount');
    formData.append('notifyUrl', 'https://fantasy.demozab.com/public/api/response');

    this.Comman.testcash(formData).subscribe(
      data => this.paymentResponse(data),
    );
  }

  paymentResponse(data) {
    if(data['status'] === 'payment_error')
    {
      this.Notify.error('Order Id already used..');
      this.router.navigateByUrl('myaccount');
    } else {
      window.location.href = data['paymentLink'];
    }
  }


//razarpay payment gateway

// initPay(Form: NgForm) {

//   this.addmoney = Form.value.addmoney;

//   this.options = {
//     'key': 'rzp_test_cZg2a9YUJmwCnP',
//     'amount': this.addmoney * 100,
//     'currency': 'INR',
//     'name': 'Acme Corp',
//     'description': 'A Wild Sheep Chase is the third novel by Japanese author  Haruki Murakami',
//     'image': 'assets/images/payment_logo.jpg',
//     'handler': function (response) {
//       alert('erreo');
//         alert(response.razorpay_payment_id);
//     },
//     'prefill': {
//         'name': 'amin uddin',
//         'email': this.user_email,
//         'contact': this.user_phone 
//     },
//     'notes': {'address': 'note value'},
//     'theme': { color: '#ff9933' }
// };

//   var rzp1 = new Razorpay(this.options);
//   rzp1.open();
//   console.log('works');
// }

  // addMoneySubmit(Form: NgForm) {
  //     this.addmoney = Form.value.addmoney;
  // }

  /* Handling Response*/
  handleResponse(data) {
    if (data['status'] === 'success') {
      this.Notify.success('OTP Verified Successfully..');
      this.router.navigateByUrl('allmatch');
    } else if (data['status'] === 'error') {
      this.Notify.error('Invalid OTP.');
    }
  }

  /* Handling Errors*/
  handleError(error) {
    this.error = error.error.errors;
  }

}
