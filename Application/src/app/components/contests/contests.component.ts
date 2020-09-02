import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { TokenService } from 'src/app/services/token.service';
import { NgForm } from '@angular/forms';

@Component({
  selector: 'app-contests',
  templateUrl: './contests.component.html', 
  styleUrls: ['./contests.component.css']
})
export class ContestsComponent implements OnInit {

  public error:any = []; // Register Server Side Validation Error Variable

  contest_details : any;
  category_details:any;
  match_details :any;
  entry_fee :any;
  teamid :any;
  team_details :any;
  cat_id: any;
  team1_key: any;
  entryfee: any; 
  range: any;
  entry_fees: any;
  teamdetailscount: any;
  team2_key: any;
  contest_id: any;
  myteam: any;
  teamdetails: any;
  user_contest_count: any;

  team_details_count: any;

  time_left: any;
  teamcount: any;
  contestid:any;
  
  authid:any;
  public matchkey:any;
  public data:any = [];
  team_create_status:any;
  details:any;

  constructor(
    private Token: TokenService,
    private Auth: AuthService,
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute
  ) { }

  ngOnInit() { 
      this.activatedRoute.params.subscribe(params => {
        // get the username out of the route params
          this.matchkey = params.matchkey;
      });
      this.getAllContest();
      this.getmatchdetails();
      this.getdetails();
      this.getmyMatch();
      this.getAllTeams();
      this.getUserContest();
  } 

  getAllContest(){
    this.data = {
      contestid: this.contestid,
      id: this.matchkey,
    };
    this.Comman.allcontest(this.data).subscribe((all) => {
      this.team_details = all['response']['response'];
      this.category_details = all['response']['category_details'];
      this.contest_details = all['response']['contestdetails'];
    });
  }
  getUserContest(){
    this.data = {
      contestid: this.contestid,
      match_key: this.matchkey,
    };
    this.Comman.userContest(this.data).subscribe((all) => {
      this.user_contest_count = all['response']['user_contest_count'];
    });
  }
  getAllTeams(){
    this.data = {
      id: this.matchkey,
    };
    this.Comman.entryteamdetails(this.data).subscribe((all) => {
      this.teamdetailscount = all['response']['teamdetailscount'];
      this.teamdetails = all['response']['team_details_count'];
    });
  }

  feesDetails(entryFee,con_id,team_id){
    this.entry_fees = entryFee;
    this.cat_id = con_id;
    this.teamid = team_id;
  }

  joinContest(entryFee,cat_id,team_id){
    const form = {
      entryfee:entryFee,
      cat_id:cat_id,
      teamid:team_id,
      id: this.matchkey,
    }
    this.Comman.joincontest(form).subscribe(
      data => this.handleResponse(data)
    );
  }


  handleResponse(data) {
    if (data.status === false) {
     this.Notify.error(data.message);
   } else if (data.status === true) {
     this.Notify.success(data.message);
     this.router.navigate(['contest-info', data.response.match_key,data.response.contest_id,data.response.user_id]);
   }
   else if (data.create_status === false) {
     this.Notify.error(data.message);
   }
  else if (data.teams_status === false) {
    this.Notify.error(data.message);
  }
 }

  getmatchdetails(){

    this.data = {
      'id': this.matchkey,
    };
    this.Comman.selectmatchdetails(this.data).subscribe((all) => {
      this.match_details = all['response'];
      this.team1_key = all['response']['team1_key']
      this.team2_key = all['response']['team2_kay']
      this.time_left = all['response']['left_time']
      this.teamcount = all['teamcount']
      this.range = all['range']
    });
  }
  getmyMatch(){ 
    this.Comman.mymatch().subscribe((all) => {
      // this.mymatch = all['response']['mymatch'];
      this.authid = all['authid'];
    });
  }
  getdetails(){

    this.details = {
      'contestid': this.matchkey,
    };
    this.Comman.userparticipentdetails(this.details).subscribe((all) => {
      this.team_create_status = all['response']['team_create_status'];
      this.myteam = all['response']['myteam'];
    });

  }

  contestjoin(e){

  }
}
