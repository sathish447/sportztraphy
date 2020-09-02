import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-mycontest',
  templateUrl: './mycontest.component.html',
  styleUrls: ['./mycontest.component.css']
})
export class MycontestComponent implements OnInit {
  data : any;
  usercontestdetails : any;
  match_details : any;
  team1_key : any;
  team2_key : any;
  time_left : any;
  teamcount : any;
  range     : any;
  authid     : any;
  status: any;
  user_contest_count: any;

  matchkey : any;

  constructor(  
    private Comman: CommonService,
    private router: Router,
    private activatedRoute: ActivatedRoute

  ) { }

  ngOnInit() {
    this.activatedRoute.params.subscribe(params => {
      // get the username out of the route params
        this.matchkey = params.matchkey;
    });

      this.getUserContest();
      this.getmatchdetails();
      this.getmyMatch();

  }
  
  getUserContest(){
    this.data = {
      // contestid: this.contestid,
      match_key: this.matchkey,
    };
    this.Comman.userContest(this.data).subscribe((all) => {

      console.log(all);
      this.usercontestdetails = all['response']['user_contest_details'];
      this.user_contest_count = all['response']['user_contest_count'];
    });
  }
  getmatchdetails(){

    this.data = {
      'id': this.matchkey,
    };
    this.Comman.selectmatchdetails(this.data).subscribe((all) => {
      this.match_details = all['response'];
      this.team1_key = all['response']['team1_key'];
      this.team2_key = all['response']['team2_kay'];
      this.time_left = all['response']['left_time'];
      this.teamcount = all['teamcount'];
      this.range = all['range'];
      this.status = all['response']['status'];
    });
  }
  getmyMatch(){ 
    this.Comman.mymatch().subscribe((all) => {
      // this.mymatch = all['response']['mymatch'];
      this.authid = all['authid'];
    });
  }


}
