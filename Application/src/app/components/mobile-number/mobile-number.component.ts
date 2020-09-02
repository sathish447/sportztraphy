import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { NgForm } from '@angular/forms';

@Component({
  selector: 'app-mobile-number',
  templateUrl: './mobile-number.component.html',
  styleUrls: ['./mobile-number.component.css']
})
export class MobileNumberComponent implements OnInit {

  numberForm :any;
  public id;
  public token;
  public name;
  public image_url;
  public session_id;
  public email;
  public facebook_session_id;
  public first_name;
  public last_name;
  public error:any = []; // Error Variable for Laravel Server side validation


  constructor(
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute
  ) { }
  
  ngOnInit() {
    
    this.activatedRoute.params.subscribe(params => {
      
      // console.log(params);
    // get the username out of the route params
      this.id = params.id; 
      this.token = params.token;
      this.name = params.name;
      this.email = params.email;
      this.image_url = params.image_url;
      this.session_id = params.session_id;
    });
    // console.log(this.date);
  }

  onSubmit(Form: NgForm) { 
    // console.log(this.session_id);
    if(this.session_id == '5D6EBEE6-EC04-4776-846D'){
      this.numberForm = {
        'phone'        : Form.value.phone,
        'id'           : this.id,
        'token'        : this.token,
        'name'         : this.name,
        'email'        : this.email,
        'image_url'    : this.image_url
      };  
      this.Comman.mobile_request(this.numberForm).subscribe(
        data => this.handleResponse(data),
        error => this.handleError(error)
    );
    } else if(this.session_id == '5D6EBEE6-EC04-4776-847D'){
        this.numberForm = {
          'phone'        : Form.value.phone,
          'id'           : this.id,
          'email'        : this.email,
          'first_name'   : this.first_name,
          'last_name'    : this.last_name,
        }; 
        this.Comman.facebook_mobile_request(this.numberForm).subscribe(
          data => this.handleResponse(data),
          error => this.handleError(error)
      );
    }
 }

   /* Handling Response*/
   handleResponse(data) {
    if (data["register_status"] === true) { 
      this.Notify.success(data.message);
      this.router.navigate(['otp', data.response['msg_session_id']]);
    } else if (data['response']["status"] === false) {
      this.Notify.error(data.message);
    }
  }

  /* Handling Errors*/
  handleError(error) {
    console.log('error');
    this.error = error.error.errors;
  }
}
