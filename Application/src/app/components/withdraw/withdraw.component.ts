import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { FormBuilder, FormGroup, Validators, FormArray, FormControl } from '@angular/forms';

@Component({
  selector: 'app-withdraw',
  templateUrl: './withdraw.component.html',
  styleUrls: ['./withdraw.component.css']
})
export class WithdrawComponent implements OnInit {

    // public withdrawForm = { // form data variable
    //   withdrawmoney     : null,
    // }

  
  winningamt: any;   // user phone number variable
  withdraw_limit:any;   // user phone number variable
  minimum_limit:any;   // user phone number variable
  maximum_limit:any;   // user phone number variable
  public error: any = []; // Register Server Side Validation Error Variable


  public withdrawForm = {  // get pan form value
    withdrawmoney : null,
  }


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

  getUsetBalance() {
    this.Comman.getUserBalance(this.withdrawForm).subscribe(
      data => {
        console.log(data);
        this.withdraw_limit = data['withdraw_limit'];
        this.minimum_limit  = data['minimum_withdraw_amt'];
        this.maximum_limit  = data['maximum_withdraw_amt'];
        this.winningamt     = data['winnings'] - this.withdraw_limit;
      }
    );
  }



    onSubmit(Form: NgForm){

      this.error = [];
      const formData = new FormData();
 
      formData.append('withdrawmoney', Form.value.withdrawmoney);

      this.Comman.withdrawSubmit(formData).subscribe(
         data => this.handleResponse(data),
         error => this.handleError(error)
       );
    }


    /* Handling Response*/
    handleResponse(data) {
        if (data.status === true) {
          this.Notify.success(data.message);
            // this.router.navigate(['password', data.response['email']]);
            this.ngOnInit();
        } else if (data.status === false) {
          this.Notify.error(data.message);
        }
    }

    /* Handling Errors*/
    handleError(error) {
      this.error = error.error.errors;
    }
  

}
