import { Component, OnInit } from '@angular/core';
import * as $ from 'jquery';
import { FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { FormBuilder, FormGroup, Validators, FormArray, FormControl } from '@angular/forms';
import { analyzeAndValidateNgModules } from '@angular/compiler';

@Component({
  selector: 'app-team-create',
  templateUrl: './team-create.component.html',
  styleUrls: ['./team-create.component.css']
})
export class TeamCreateComponent implements OnInit {

  short_name: any;
  player_percent: any;
  alplayerdetails: any;
  batplayerdetails: any;
  bowlplayerdetails: any;
  keeperplayerdetails: any;
  currentcredit:any;
  totalcredit :any;
  playercount :any;
  selectteal1count:any;
  selectteal2count:any;
  match_details:any;
  data:any;
  team1_key:any;
  matchkey:any;
  team2_key:any;
  time_left:any;
  selectedplayers:any = [];
  playerindex:any;
  playerdetail:any;
  contestid:any;
  public error:any = []; // Register Server Side Validation Error Variable

  markwkcount:any;
  markarcount:any;
  markbowlcount:any;
  markbatcount:any;
  playerdetaillen:any;
  playerbatting_style:any;
  playerbowling_style:any;

  team1count:any;
  team2count:any;

  playertotalpoints:any;
  playercredit:any;
  playerdob:any;

  playername:any;
  playernationality:any;
  playerrole:any;

  constructor(
    private http: HttpClient,
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
  ) { }

  ngOnInit() {
    this.totalcredit = '100';
    this.playercount = '0';
    this.playerindex = '0';
    this.playerdetaillen = 0;

    this.team1count = 6;
    this.team2count = 5;

    this.markwkcount = this.markarcount = this.markbowlcount = this.markbatcount = 0;

    $('#saveteam').prop('disabled', true); //TO DISABLED

    this.activatedRoute.params.subscribe(params => {
      // get the username out of the route params
        this.matchkey = params.matchkey;
        this.contestid = params.contestid;
      });

      this.getteamDetails();
      this.getmatchdetails();


  }

  playerdetails(){
    const form ={
      'id':this.matchkey
    };

  }

  getteamDetails(){
    const form ={
      'id':this.matchkey
    };

    this.Comman.getcreateteamdetails(form).subscribe((all) => {

      this.alplayerdetails = all['response']['ar'];
      this.batplayerdetails = all['response']['bat'];
      this.bowlplayerdetails = all['response']['bowl'];
      this.keeperplayerdetails = all['response']['wk'];
      this.selectteal1count = all['response']['selectteal1count'];
      this.selectteal2count = all['response']['selectteal2count'];
    });

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

    });
  }

  wkPlayeradd(section,id,name,credit,player_key,role,teamname,tno){
    this.addplayercalc(section,id,name,credit, 'wk-players', 'wk-player',player_key,role,teamname,tno);
  }

  batPlayeradd(section,id,name,credit,player_key,role,teamname,tno){ 
    this.addplayercalc(section,id,name,credit,'bat-players','bat-player',player_key,role,teamname,tno);
  }


  arPlayeradd(section,id,name,credit,player_key,role,teamname,tno){
    this.addplayercalc(section,id,name,credit,'ar-players','ar-player',player_key,role,teamname,tno);
  }


  bowlPlayeradd(section, id, name, credit,player_key,role,teamname,tno){

    this.addplayercalc(section,id,name,credit,'bowl-players','bowl-player',player_key,role,teamname,tno);
  }

  addplayercalc(section,id,name,credit,secid,divid,player_key,role,teamname,tno){

      this.currentcredit = this.totalcredit - credit;

      if (this.playercount >= 11) {
        this.Notify.error('you cannot pick more than 11 players.');
        return false;
      }

      if (this.team1count == 0 && tno == 'team1') {
        this.Notify.error('you cannot pick more than 6 players in team 1.');
        return false;
      }


      if (this.team2count == 0 && tno == 'team2') {
        this.Notify.error('you cannot pick more than 5 players in team 2.');
        return false;
      }


      if (this.currentcredit >= 0) {

        if(tno == 'team1'){
          this.team1count--;
  
        }else if(tno == 'team2'){
          this.team2count--;
        }

        this.playercount++;

        if(secid == 'wk-players'){
          this.markwkcount++;
        } else if(secid == 'ar-players'){
          this.markarcount++;
        } else if(secid == 'bowl-players'){
          this.markbowlcount++;
        }else if(secid == 'bat-players'){
          this.markbatcount++;
        }

        if (this.playercount == 11) {
          $('#saveteam').prop('disabled', false); //TO ENABLE
        }
        // tslint:disable-next-line: max-line-length
        $('#'+secid).append('<div id="'+divid+'-'+player_key+'" class="select-player"><div class="select-palyer-inner"><div class="player-pic"><img src="assets/images/player.svg" /></div><div class="player-name">'+name+'</div><div class="player-credits">'+credit+' cr</div></div></div>');

        $('#'+section+'player-data-td'+id).addClass('active');

        this.totalcredit = this.currentcredit;
        const tempplayer = {
            'name' : name,
            'player_key' : player_key,
            'credit' : credit,
            'role' : role,
            'team_name':teamname,
            'caption':'0',
            'v_caption':'0',
            'fantasy_points':'0',
        };
        this.selectedplayers[this.playerindex] = tempplayer;
        this.playerindex++;
      } else {
        this.Notify.error('you dont have enough credits to select this player.');
      }
  }


  wkPlayerremove(section,id,credit,tno,name,player_key){
    // tslint:disable-next-line: max-line-length
    $('#wkplayer-data-td'+id).removeClass('active');
    $('#wk-player-'+player_key).remove();
    this.subplayercalc(section,credit,tno,name,player_key);
  }

  batPlayerremove(section,id,credit,tno,name,player_key){
    // tslint:disable-next-line: max-line-length
    $('#batplayer-data-td'+id).removeClass('active');
    $('#bat-player-'+player_key).remove();

    this.subplayercalc(section,credit,tno,name,player_key);
  }


  arPlayerremove(section,id,credit,tno,name,player_key){
    // tslint:disable-next-line: max-line-length

    $('#arplayer-data-td'+id).removeClass('active');
    $('#ar-player-'+player_key).remove();

    this.subplayercalc(section,credit,tno,name,player_key);
  }


  bowlPlayerremove(section,id,credit,tno,name,player_key){
    // tslint:disable-next-line: max-line-length
    $('#bowlplayer-data-td'+id).removeClass('active');
    $('#bowl-player-'+player_key).remove();

    this.subplayercalc(section,credit,tno,name,player_key);
  }

  subplayercalc(section,credit,tno,name,player_key){
    this.totalcredit = this.totalcredit + credit;
    this.playercount--;


    if(tno == 'team1'){
      this.team1count++;

    }else if(tno == 'team2'){
      this.team2count++;
    }

    if(section == 'wk'){
      this.markwkcount--;
    } else if(section == 'ar'){
      this.markarcount--;
    } else if(section == 'bowl'){
      this.markbowlcount--;
    }else if(section == 'bat'){
      this.markbatcount--;
    }

    if (this.playercount == 11) {
      $('#saveteam').prop('disabled', false); //TO ENABLE
    }else{
      $('#saveteam').prop('disabled', true); //TO ENABLE
    }

    this.Removedata(this.selectedplayers, name);
}

Removedata(arr, name) {
  for (var i = 0; i < this.selectedplayers.length; i++) {
    var cur = this.selectedplayers[i];
    if (cur.name == name) {
        arr.splice(i, 1);
        this.playerindex--;
        break;
    }
}
}

saveTeam(){
  const form = {
      contest_id:this.contestid,
      match_key:this.matchkey,
      player:this.selectedplayers,
  }

  this.Comman.saveteam(form).subscribe(
    data => this.handleResponse(data),
    error => this.handleError(error)
  );
}

   /* Handling Response*/
   handleResponse(data) {
    if (data.status === true) {
      this.Notify.success(data.message);
      this.router.navigate(['team-captain', data.response['team']['_id']]);
      // this.router.navigateByUrl('team-captain');
    } else if (data.status === false) {
        this.Notify.error(data.message);
    }

  }

   /* Handling Errors*/
   handleError(error) {
    this.error = error.error.errors;
  }

  getplayerdetail(playerid){

    const form = {
      playerid:playerid,
      match_id:this.matchkey
    }
    
    this.Comman.getplayerdetails(form).subscribe((all) => {

      console.log(all);
      this.player_percent = all['response']['details']['player_percent'];
      this.short_name = all['response']['details']['short_name'];
      this.playercredit = all['response']['details']['credits'];
      this.playertotalpoints = all['response']['details']['total_points'];
      this.playerbatting_style = all['response']['details']['batting_style'];
      this.playerbowling_style = all['response']['details']['bowling_style'];

      this.playerdob = all['response']['details']['dob'];
      this.playername = all['response']['details']['name'];

      this.playernationality = all['response']['details']['nationality'];
      this.playerrole = all['response']['details']['role'];

  });
  }



}
