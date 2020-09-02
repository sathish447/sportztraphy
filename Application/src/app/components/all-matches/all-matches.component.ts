import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { TokenService } from 'src/app/services/token.service';

@Component({
  selector: 'app-all-matches',
  templateUrl: './all-matches.component.html',
  styleUrls: ['./all-matches.component.css']
})
 
export class AllMatchesComponent implements OnInit {

  public error:any = []; // Register Server Side Validation Error Variable
  match_details : any;
  authid : any;
  mymatch:any;
  completed: any;
  live: any;
  upcomming: any;
  mymatchcount:any;
  diff:any;
  future:any;

  //  match_details = [
  //   {
  //   "_id": "5de664746dfec70e53463ac2",
  //   "format": "one-day",
  //   "key": "zimban_2013_one-day_01",
  //   "result": null,
  //   "name": "Zimbabwe vs Bangladesh",
  //   "related_name": "1st ODI Match at Bulawayo",
  //   "short_name": "Zimbabwe vs Bangladesh",
  //   "start_date": "2013-05-03T07:30+00:00",
  //   "status": "completed",
  //   "title": "Zimbabwe vs Bangladesh - 1st ODI Match at Bulawayo - Zimbabwe vs Bangladesh 2013",
  //   "venue": "Queens Sports Club, Bulawayo",
  //   "winner": "team2",
  //   "team1": "Zimbabwe",
  //   "team1_key": "zim",
  //   "team1_season_key": "zimban_2013_zim",
  //   "team2": "Bangladesh",
  //   "team2_kay": "ban",
  //   "team2_season_key": "zimban_2013_ban",
  //   "msg_info": "BAN won by 121 Runs.",
  //   "msg_completed": "BAN won by 121 Runs.",
  //   "season_name": "Zimbabwe vs Bangladesh 2013",
  //   "season_key": "zimban_2013"
  //   },
  //   {
  //   "_id": "5de664746dfec70e53463ac3",
  //   "format": "one-day",
  //   "key": "zimban_2013_one-day_02",
  //   "result": null,
  //   "name": "Zimbabwe vs Bangladesh",
  //   "related_name": "2nd ODI Match at Bulawayo",
  //   "short_name": "Zimbabwe vs Bangladesh",
  //   "start_date": "2013-05-05T07:30+00:00",
  //   "status": "completed",
  //   "title": "Zimbabwe vs Bangladesh - 2nd ODI Match at Bulawayo - Zimbabwe vs Bangladesh 2013",
  //   "venue": "Queens Sports Club, Bulawayo",
  //   "winner": "team1",
  //   "team1": "Zimbabwe",
  //   "team1_key": "zim",
  //   "team1_season_key": "zimban_2013_zim",
  //   "team2": "Bangladesh",
  //   "team2_kay": "ban",
  //   "team2_season_key": "zimban_2013_ban",
  //   "msg_info": "ZIM won by 6 Wickets.",
  //   "msg_completed": "ZIM won by 6 Wickets.",
  //   "season_name": "Zimbabwe vs Bangladesh 2013",
  //   "season_key": "zimban_2013"
  //   },
  //   {
  //   "_id": "5de664746dfec70e53463ac4",
  //   "format": "one-day",
  //   "key": "zimban_2013_one-day_03",
  //   "result": null,
  //   "name": "Zimbabwe vs Bangladesh",
  //   "related_name": "3rd ODI Match at Bulawayo",
  //   "short_name": "Zimbabwe vs Bangladesh",
  //   "start_date": "2013-05-08T07:30+00:00",
  //   "status": "completed",
  //   "title": "Zimbabwe vs Bangladesh - 3rd ODI Match at Bulawayo - Zimbabwe vs Bangladesh 2013",
  //   "venue": "Queens Sports Club, Bulawayo",
  //   "winner": "team1",
  //   "team1": "Zimbabwe",
  //   "team1_key": "zim",
  //   "team1_season_key": "zimban_2013_zim",
  //   "team2": "Bangladesh",
  //   "team2_kay": "ban",
  //   "team2_season_key": "zimban_2013_ban",
  //   "msg_info": "ZIM won by 7 Wickets.",
  //   "msg_completed": "ZIM won by 7 Wickets.",
  //   "season_name": "Zimbabwe vs Bangladesh 2013",
  //   "season_key": "zimban_2013"
  //   }];

  constructor( 
    private Token: TokenService,
    private Auth: AuthService,
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router
  ) { }

  ngOnInit() {

    this.getAllMatch(); 
    this.getmyMatch();
    this.getmymatchcount();
    // this.getmyMatchdetails();
  }

  getAllMatch(){
    this.Comman.allmatch().subscribe((all) => {
      // console.log(all['response']['matchdetails']);
      this.match_details = all['response']['matchdetails'];
      // this.diff = Math.floor((this.future.getTime() - new Date().getTime()) / 1000);      
      // console.log(this.diff);
    });
  }

  getmyMatch(){
    this.Comman.mymatch().subscribe((all) => {
      // console.log(all);
      this.mymatch = all['response']['mymatch'];
      this.authid = all['authid'];
    });
  }

  getmymatchcount(){
    this.Comman.mymatchcount().subscribe((all) => {
      // console.log(all);
      this.mymatchcount = all['response'];
    });

  }
     /* Handling Response*/
     handleResponse(data) {
      const details = data.response.matchdetails;
      // tslint:disable-next-line: whitespace
      // tslint:disable-next-line: align
    }


  /* Handling Errors*/

  handleError(error) {
    this.error = error.error.errors;
  }

}
