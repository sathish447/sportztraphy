import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { defaultDayOfMonthOrdinalParse } from 'ngx-bootstrap/chronos/locale/locale.class';
import { NgForm } from '@angular/forms';

@Component({
  selector: 'app-account-verify', 
  templateUrl: './account-verify.component.html',
  styleUrls: ['./account-verify.component.css']
})
export class AccountVerifyComponent implements OnInit {

  myDateValue: Date;
  emailverify: any  = [];  // user email verify variable
  mobileverify: any = [];  // user phone number verify variable
  userphone: any    = [];  // user phone number variable
  useremail: any     = [];  // user email variable
  bankstatus = 0;  // user bank status variable
  panstatus: any     = [];  // user pan status variable
  // tslint:disable-next-line: no-inferrable-types
  public verifysymbolshow = true; // condition based display verify symbol variable

  public error: any = []; // Register Server Side Validation Error Variable
  public date = '';

  public bankproof: File;   // Declear a selectedImage1 as File [File : data type]
  public panproof: File;   // Declear a selectedImage2 as File [File : data type]

  pandoc: any;              // Declear a doc1 as any [any : data type]
  bankdoc: any;              // Declear a doc2 as any [any : data type]

  public panForm = {  // get pan form value
    pan_name : null,
    pan_number: null,
    dob_pan: null,
    upload_pan_image:null
  }

  public bankForm = {  // get bank form value
    account_number : null,
    bank_name: null,
    branch: null,
    retype_account_number: null,
    upload_bank_image: null
  } 
  
  constructor(
      private Comman: CommonService,
      private Notify: ToastrService,
      private router: Router,
      private activatedRoute: ActivatedRoute,
  ) { }

  ngOnInit() {

    this.myDateValue = new Date();
    this.me();
  }
  onDateChange(newDate: Date) {
    console.log(newDate);
  }


  /* Function for showing a select image [ Field ID : selectedImage1 ] */

  bank_image(event) {

  // tslint:disable-next-line: triple-equals
  // tslint:disable-next-line: max-line-length

  // tslint:disable-next-line: triple-equals
  // tslint:disable-next-line: max-line-length
    if (event.target.files[0].name.replace(/^.*\./, '') == 'png' || event.target.files[0].name.replace(/^.*\./, '') == 'jpeg' || event.target.files[0].name.replace(/^.*\./, '') == 'jpg') {

      if (event.srcElement.files[0].size / 1024 / 1024 <= 1) {
        this.bankproof = event.srcElement.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(event.target.files[0]); // read file as data url

        reader.onload = (event: any) => { // called once readAsDataURL is completed
          this.bankdoc = event.target.result;
        };
      } else {
        this.Notify.warning('Image size should be below 1 mb', 'Warning');
      }
    } else {
      this.Notify.warning('Only png, jpg, jpeg images are accepted', 'Warning');
    }
  }


  /* Function for showing a select image [ Field ID : selectedImage1 ] */

  pan_image(event) {
 
    // tslint:disable-next-line: triple-equals
    // tslint:disable-next-line: max-line-length
    // tslint:disable-next-line: triple-equals
    // tslint:disable-next-line: max-line-length
      if (event.target.files[0].name.replace(/^.*\./, '') == 'png' || event.target.files[0].name.replace(/^.*\./, '') == 'jpeg' || event.target.files[0].name.replace(/^.*\./, '') == 'jpg') {
        if (event.srcElement.files[0].size / 1024 / 1024 <= 1) {
          this.panproof = event.srcElement.files[0];
          console.log(this.panproof);
          const reader = new FileReader();
          reader.readAsDataURL(event.target.files[0]); // read file as data url

          reader.onload = (event: any) => { // called once readAsDataURL is completed
            this.pandoc = event.target.result;
          };
        } else {
          this.Notify.warning('Image size should be below 1 mb', 'warning');
        }
      } else {
        this.Notify.warning('Only png, jpg, jpeg images are accepted', 'Warning');
      }
    }


  me(){
    this.Comman.userdata().subscribe(
      data => {
        console.log(data);  
        this.userphone.push(data['userphone']);
        this.useremail.push(data['useremail']);
        this.emailverify  = data['email_verify'];
        // tslint:disable-next-line: triple-equals
        this.mobileverify = data['mobile_verify'];
        this.panForm.pan_name = data['pan']['pan_name'];
        this.panForm.pan_number = data['pan']['pan_number'];
        this.panForm.dob_pan = data['pan']['dob_pan'];
        this.pandoc = data['pan']['pan_proof'];

        this.bankForm.account_number = data['bank']['account_number'];
        this.bankForm.bank_name = data['bank']['bank_name'];
        this.bankForm.branch = data['bank']['branch'];
        this.bankdoc = data['bank']['bank_proof'];
 
        this.bankstatus = data['bank_status'];
        this.panstatus = data['pan_status'];

      }
    );
  }

  onEmailVerifySubmit(){
    this.Comman.sendVerificationMail().subscribe(
      data => this.handleResponse(data),
      error => this.handleError(error)
    );
  }

  panSubmit(Form: NgForm){

    this.error = [];
    const formData = new FormData();
    if (this.panproof) {
      formData.append('upload_pan_image', this.panproof, this.panproof.name);
    }
    console.log(formData);
    formData.append('pan_name', Form.value.pan_name);
    formData.append('pan_number', Form.value.pan_number);
    formData.append('dob_pan', Form.value.dob_pan);
    this.Comman.panSubmit(formData).subscribe(
       data => this.panhandleResponse(data),
       error => this.handleError(error)
     );
  }

  bankSubmit(Form: NgForm){
 
    const formData = new FormData();

    if (this.bankproof) {
      formData.append('upload_bank_image', this.bankproof, this.bankproof.name);
    }

    formData.append('account_number', Form.value.account_number);
    formData.append('retype_account_number', Form.value.retype_account_number);
    formData.append('bank_name', Form.value.bank_name);
    formData.append('branch', Form.value.branch);

    this.Comman.banksubmit(formData).subscribe(
      data => this.bankhandleResponse(data),
      error => this.handleError(error)
    );
  }

   /* Handling Response*/
   handleResponse(data) {
    
    if (data.status === true) {
      this.Notify.success(data.message);
    } else if (data.status === false) {
        this.Notify.error(data.message);
    } else if (data['status'] === 'success') {
      this.Notify.success('Mail sent to registered mail-id. please check your inbox/spam.');
      this.ngOnInit();
    } else if (data['status'] === 'error') {
      this.Notify.error('Failed');
      this.ngOnInit();
    }
  }

     /* Handling Response*/
     bankhandleResponse(data) {
      // console.log(data);
      if (data.status === true) {
        this.Notify.success(data.message);
      } else if (data.status === false) {
          this.Notify.error(data.message);
      } else if (data['status'] === 'success') {
        this.Notify.success('Bank details updated successfully');
        this.ngOnInit();
      } else if (data['status'] === 'error') {
        this.Notify.error('Failed');
      } else if (data['status'] === 'account_number_mismatch') {
        this.Notify.error('Account number mismatch.');
        this.ngOnInit();
      }
    }

       /* Handling Response*/
   panhandleResponse(data) {
    // console.log(data);

    if (data.status === true) {
      this.Notify.success(data.message);
    } else if (data.status === false) {
        this.Notify.error(data.message);
    } else if (data['status'] === 'success') {
    } else if (data['status'] === 'error') {
      this.Notify.error('Invalid Mail-Id.');
    }
  }

  /* Handling Errors*/
  handleError(error) {
    this.error = error.error.errors;
  }



}
