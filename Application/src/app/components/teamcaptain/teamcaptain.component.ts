import { Component, OnInit } from '@angular/core';
import * as $ from 'jquery';
import { FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { FormBuilder, FormGroup, Validators, FormArray, FormControl } from '@angular/forms';

@Component({
  selector: 'app-teamcaptain',
  templateUrl: './teamcaptain.component.html',
  styleUrls: ['./teamcaptain.component.css']
})
export class TeamcaptainComponent implements OnInit {

  teamdetails: any;
  teamid:any;
  captaion:any='';
  vicecaptaion:any='';
  public error:any = []; // Register Server Side Validation Error Variable

  constructor(
    private http: HttpClient,
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute,) { }

  ngOnInit() {

    this.activatedRoute.params.subscribe(params => {
      // get the username out of the route params
        this.teamid = params.teamid;
      });


    this.getteamDetails();
  }


  getteamDetails(){

    const form ={
      'id':this.teamid
    };

    this.Comman.selectcvcteam(form).subscribe((all) => {
      console.log(all);
      this.teamdetails = all['response']['players'];
    });

  }

  addcaptain(cname,i){
    this.captaion = cname;
    $('#captain'+i).addClass('captainactive');
  }

  addvcaptain(vcname,i){
    this.vicecaptaion = vcname;
    $('#visecaptain'+i).addClass('visecaptainactive');
  }

  saveTeam(){
    if(this.captaion == '' || this.vicecaptaion == ''){
      this.Notify.error('Plese select Captain and Vice Captain.');
      return false;
    }

    const form = {
      captaion:this.captaion,
      vicecaptaion:this.vicecaptaion,
      teamid:this.teamid,
    }

    this.Comman.savecvcteam(form).subscribe(
      data => this.handleResponse(data),
      error => this.handleError(error)
    );

  }

     /* Handling Response*/
     handleResponse(data) {
       console.log(data);
       if (data.status === true) {
        this.Notify.success(data.message);
        this.router.navigate(['contests', data.response['matchkey']]);
      } else if (data.status === false) {
        this.Notify.error(data.message);
      }

    }

     /* Handling Errors*/
     handleError(error) {
      this.error = error.error.errors;
    }


}
