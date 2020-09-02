import { Component, OnInit, ɵConsole } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/services/common.service';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-viewteam',
  templateUrl: './viewteam.component.html',
  styleUrls: ['./viewteam.component.css']
})
export class ViewteamComponent implements OnInit {

  matchkey: any;
  viewteamdetails: any;
  viewwkteamdetails:any;
  viewarteamdetails:any;
  viewbatteamdetails:any;
  viewbowlteamdetails:any;

  constructor(
    private http: HttpClient,
    private Comman: CommonService,
    private router: Router,
    private activatedRoute: ActivatedRoute,) { 
  }

  public form = {
    getteamDetails:null,
  }

  ngOnInit() {
    this.activatedRoute.params.subscribe(params => {
      // get the username out of the route params
        this.matchkey = params.matchkey;
      });

      this.getteamviewDetails();
  }

  getteamviewDetails(){

    const form ={
      'id':this.matchkey
    };

    this.Comman.myviewteam(form).subscribe((all) => {
      this.viewteamdetails = all['response']['tlist'];
      this.viewwkteamdetails = all['response']['details']['wk'];
      this.viewbatteamdetails = all['response']['details']['bat'];
      this.viewarteamdetails = all['response']['details']['ar'];
      this.viewbowlteamdetails = all['response']['details']['bowl'];
    });

  }

  preview(matchid){

    const form ={
      'team_id': matchid,
      'match_key':this.matchkey
    };

    this.Comman.selectviewteam(form).subscribe((all) => {

      console.log(all);
      this.viewwkteamdetails = all['response']['wk'];
      this.viewbatteamdetails = all['response']['bat'];
      this.viewarteamdetails = all['response']['ar'];
      this.viewbowlteamdetails = all['response']['bowl'];
    });
  }


  teamedit(teamid){

    this.router.navigate(['editteam', this.matchkey, teamid]);

  }

}
