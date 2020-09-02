import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { defaultDayOfMonthOrdinalParse } from 'ngx-bootstrap/chronos/locale/locale.class';

@Component({
  selector: 'app-personal-info',
  templateUrl: './personal-info.component.html',
  styleUrls: ['./personal-info.component.css']
})
export class PersonalInfoComponent implements OnInit {
  myDateValue: Date;

  public profileinfoForm = {
    state : null,
    dob: null,
    name: null,
    email: null,
    

    // user_email:null
  }


  public error:any = []; // Error Variable for Laravel Server side validation
  public resetError:any = null; // Error Variable for Common Errors
  public date;

   //get form value
   addMoneyForm: any;
   date_of_birth: any;
   wins: any;
   matches: any;
   contests: any;
   profiledoc: any;
   user_name: any;
   id: any;
   profile_proof: any;
   series: any;
   image: any;
   invitecode: any;
   invite_code: any;
   friendsdetails: any;
   walletbalance: any   = [];  // user total wallet balance Variable
   bonusbalance: any    = [];  // user bonus wallet balance Variable
   depositbalance: any = [];  // user deposit wallet balance Variable
   winningbalance: any  = [];  // user winnings wallet balance Variable
   user_email: any      = [];  // user email variable
   user_phone: any      = [];  // user phone number variable
   addmoney: any        = [];
   teamname: any        = [];
   state: any        = [];
   name: any        = [];
   dob: any        = [];
   
   options: any         = [];

  constructor(
      private Notify: ToastrService,
      private common: CommonService,
      private router: Router,
      private Toastr: ToastrService,
      private activatedRoute: ActivatedRoute
      
  ) { }
 
  ngOnInit() {
    
    this.myDateValue = new Date();
    this.getUsetBalance();
    this.profiledoc='';
  }

  getUsetBalance() {

    this.common.userdata().subscribe(
      data => {
        console.log(data);
        this.teamname.push(data['username']);
        this.id = data['id'];
        this.user_email = data['useremail'];
        this.user_name = data['username'];
        this.wins = data['wins'];
        this.matches = data['matches'];
        this.contests = data['contests']; 
        this.series = data['series'];
        this.image = data['image'];
        this.invitecode = data['invite_code'];
        this.profileinfoForm.name = data['name'];
        this.profileinfoForm.dob = data['dob'];
        this.profileinfoForm.state = data['state'];
      }
    );
  }
  invitefriends(Form: NgForm,invitecode,user_name){
    this.friendsdetails = {
      'email': Form.value.invite_email,
      'invitecode': invitecode,
      'username': user_name,
  };
    this.common.invitefriends(this.friendsdetails).subscribe(
      data => this.inviteResponse(data),
      error => this.handleError(error)
    );
  }

  inviteResponse(data) {
    if (data.status === true) {
      this.Notify.success(data.message);
    }
  }
  profileupload(Form: NgForm){
    const formData = new FormData();
    if (this.profile_proof) {
      formData.append('upload_profile_image', this.profile_proof, this.profile_proof.name);
    }
    console.log(formData);
    this.common.profileupload(formData).subscribe(
      data => this.profileResponse(data),
    );
  }

  profileResponse(data) {
    console.log(data);
    if (data.status === true) {
      this.Notify.success(data.message);
    } 
    if(data.status === false){
      this.Notify.error(data.message);
    }
  } 

  onSubmit(Form: NgForm)
  {
    this.profileinfoForm = {
        'name': Form.value.name,
        'dob': Form.value.dob, 
        'state':Form.value.state,
        'email': Form.value.email,
    };
    this.error = [];
    this.common.profilesubmit(this.profileinfoForm).subscribe(
    data => this.handleResponse(data),
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
    this.Notify.success('Profile Updated Successfully.');
  } else if (data['status'] === 'error') {
    this.Notify.error('Profile Update Failed.');
  }
}
profile_image(event) {
console.log(event);
  // tslint:disable-next-line: triple-equals
  // tslint:disable-next-line: max-line-length
  // tslint:disable-next-line: triple-equals
  // tslint:disable-next-line: max-line-length
    if (event.target.files[0].name.replace(/^.*\./, '') == 'png' || event.target.files[0].name.replace(/^.*\./, '') == 'jpeg' || event.target.files[0].name.replace(/^.*\./, '') == 'jpg') {
      if (event.srcElement.files[0].size / 1024 / 1024 <= 1) {
        this.profile_proof = event.srcElement.files[0];
        console.log(this.profile_proof);
        const reader = new FileReader();
        reader.readAsDataURL(event.target.files[0]); // read file as data url

        reader.onload = (event: any) => { // called once readAsDataURL is completed
          this.profiledoc = event.target.result;
          console.log(this.profiledoc);
        };
      } else {
        this.Notify.warning('Image size should be below 1 mb', 'warning');
      }
    } else {
      this.Notify.warning('Only png, jpg, jpeg images are accepted', 'Warning');
    }
  }

/* Handling Errors*/
handleError(error) {
  this.error = error.error.errors;
}


}
