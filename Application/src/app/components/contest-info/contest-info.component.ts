import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { Observable, timer } from 'rxjs';
import { take, map } from 'rxjs/operators';
import { AuthService } from 'src/app/services/auth.service';
import { TokenService } from 'src/app/services/token.service';
 
import * as $ from 'jquery';

@Component({
    selector: 'app-contest-info',
    templateUrl: './contest-info.component.html',
    styleUrls: ['./contest-info.component.css']
})
export class ContestInfoComponent implements OnInit {

    public error: any = []; // Register Server Side Validation Error Variable
    public contestid;
    public range;
    public board_details;
    public teamname;
    public contest_details;
    public contest_detail;
    public contest_count;
    public teamcount;
    public user_id;
    public details;
    public run_str1;
    public team_name2;
    public team_name1;
    public run_str;
    public run_str2;
    public team_create_status;
    public listcount;
    public scores;
    public data: any = [];
    public keeper1: any = [];
    public bowler1: any = [];
    public allrounder1: any = [];
    public batsman1: any = [];
    public winning_details: any = [];

    public mydata=[];
    public notmydata=[];

    public wklist;
    public viewwkplayer =[];

    public viewarplayer = [];
    public viewbatplayer = [];
    public viewbowlplayer = [];

    list: any = [];
    team1_key: any;
    team2_key: any;
    time_left: any;
    contest_display: any = [];
    msg_completed: any;
    public matchkey: any;
    match_details: any;
    fantasypoints: any = [];
    fantasypointscount: any = [];
    user_points: any;

    constructor(
        private Comman: CommonService,
        private Notify: ToastrService,
        private router: Router,
        private activatedRoute: ActivatedRoute,
        private Token: TokenService,
        private Auth: AuthService,
    ) { }

    ngOnInit() {

        this.activatedRoute.params.subscribe(params => {
            // get the username out of the route params
            this.contestid = params.id;
            this.matchkey = params.matchkey;
            this.user_id = params.userid;
        });

        this.getSelectedContest();
        this.getmatchdetails();
        this.getdetails();
        this.getContest();

        const match = this.matchkey;
        const matchkeyss = this.matchkey;
        const constid = this.contestid;
        const userid = this.user_id;
        let conn = new WebSocket('wss://fantasyapi.demozab.com:8100');
    // let conn = new WebSocket('ws://192.168.1.66:8100');

        conn.onopen = function (e) {
            console.log('Connected to websockey');
            conn.send(JSON.stringify({ type: 'Leaderboard', match_key: match, contest_id: constid, user_id: userid }));
        }; 
        // tslint:disable-next-line: only-arrow-functions
        conn.onmessage = function (event) {
            const Data = JSON.parse(event.data);
            if (matchkeyss === Data.match_key) {
                if (Data.leaderboard != '') {
                    if (Data.score['score_team2']) {
                        $('.run_str2_name').html(Data.score['score_team2']['team_name']);
                        $('.run_str2').html(Data.score['score_team2']['run_str']);
                    }   
                    if (Data.score['score_team1']) {
                        $('.run_str1_name').html(Data.score['score_team1']['team_name']);
                        $('.run_str1').html(Data.score['score_team1']['run_str']);
                        // $('.run_str1').html(Data.score['score_team1']['team_name']);
                    }
                    $('.user_points').html(Data.leaderboard[0]['fantasy_points']);
                    $('.user_rank').html(Data.leaderboard[0]['rank']);
                    $('.teamname').html(Data.leaderboard[0]['user_name']);
                    var alldata = '';
                    // var players = [];
                    var notmydata = '';
                    var mydata = '';
                    var newdata = '';
                    if (Data.leaderboard.length > 0) {
                        for (var i = 0; i < Data.leaderboard.length; i++) {
                            var leaderboard = Data.leaderboard[i];

                            var contest = leaderboard['contest_id'];
                            var match_key = leaderboard['match_key'];

                            if (match_key == match && contest == constid) {

                                var user_id = leaderboard['user_name'];
                                var id = leaderboard['user_id'];
                                var fpoints = leaderboard['fantasy_points'];
                                var rank = leaderboard['rank'];
                                var paid_status = leaderboard['paid_status'];
                                var keyid = leaderboard['id'].$oid;
                                var players = leaderboard['players'];
                                var keeper1 = [];
                                var batsman1 = [];
                                var allrounder1 = [];
                                var bowler1 = [];
                                var match_status = Data.match_status;
                                keeper1 = players['keeper'];
                                bowler1 = players['bowler'];
                                batsman1 = players['batsman'];
                                allrounder1 = players['allrounder'];

                                var pathname = window.location.pathname;


                                var array = pathname.split('/');

                                var matchpart = array[array.length-3];
                                var contestpart = array[array.length-2];
                                var userpart = array[array.length-1];

                                if( matchpart == match_key  && contestpart == contest){

                                if (id != userid) {

                                    // tslint:disable-next-line: max-line-length
                                    notmydata += '<div data-target=#open-' + keyid  + ' data-toggle="collapse" class="leader-board-div white-box"> <div class="leader-div1">' + user_id + '</div> <div class="leader-div2">' + fpoints + '</div> <div class="leader-div3">' + rank + '</div></div>';
                                    // notmydata = this.gethtmldata(players,keyid,alldata);
                                    var img = 'https://fantasy.demozab.com/assets/images/ground-bg1.jpg';


                                    if(match_status != 'notstarted'){
                                        keyid = keyid;
                                    }else{
                                        keyid = '';
                                    }

                                    // tslint:disable-next-line: max-line-length
                                    notmydata += '<div class="collapse" data-parent="#accordionExample" id="open-' + keyid + '" > <div class="socket white-box v-ground white-t" style="background-image:url(' + img + '); background-size: 100%; background-position: center center;">';
                                    // tslint:disable-next-line: max-line-length
                                    notmydata += '<table class="select-table-player white-t"><tbody><tr><td><h6> Wicket-Keepers </h6> <table><tbody>';
                                    if (keeper1.length > 0) {

                                        for (var wi = 0; wi < keeper1.length; wi++) {
                                            var wk = keeper1[wi];
                                            if (wk['caption'] == 1) {
                                                var chigh = " captain";
                                            }
                                            else if (wk['v_caption'] == 1) {
                                                var chigh = " vcaptain";
                                            } else {
                                                var chigh = "";
                                            }
                                            // tslint:disable-next-line: max-line-length
                                            notmydata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                                            // tslint:disable-next-line: max-line-length
                                            notmydata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> WK - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
                                        }
                                    }
                                    else {
                                        notmydata += ' <tr> <td colspan="4"> - </td> </tr> ';
                                    }
                                    notmydata += ' </tbody> </table> </td>';
                                    notmydata += '  <td> <h6>Batsmen</h6> <table>  <tbody>';
                                    if (batsman1.length > 0) {

                                        for (var wj = 0; wj < batsman1.length; wj++) {
                                            var wk = batsman1[wj];
                                            if (wk['caption'] == 1) {
                                                var chigh = " captain";
                                            }
                                            else if (wk['v_caption'] == 1) {
                                                var chigh = " vcaptain";
                                            } else {
                                                var chigh = "";
                                            }
                                            // tslint:disable-next-line: max-line-length
                                            notmydata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                                            // tslint:disable-next-line: max-line-length
                                            notmydata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> BAT - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
                                        }
                                    }
                                    else {
                                        notmydata += ' <tr> <td colspan="4"> - </td> </tr> ';
                                    }
                                    notmydata += ' </tbody> </table> </td>';
                                    notmydata += '  <td> <h6>Allrounder</h6> <table>  <tbody>';
                                    if (allrounder1.length > 0) {

                                        for (var wl = 0; wl < allrounder1.length; wl++) {
                                            var wk = allrounder1[wl];
                                            if (wk['caption'] == 1) {
                                                var chigh = " captain";
                                            }
                                            else if (wk['v_caption'] == 1) {
                                                var chigh = " vcaptain";
                                            } else {
                                                var chigh = "";
                                            }
                                            // tslint:disable-next-line: max-line-length
                                            notmydata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                                            // tslint:disable-next-line: max-line-length
                                            notmydata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> AR - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
                                        }
                                    }
                                    else {
                                        notmydata += ' <tr> <td colspan="4"> - </td> </tr> ';
                                    }
                                    notmydata += ' </tbody> </table> </td>';
                                    notmydata += '  <td> <h6>Bowler</h6> <table>  <tbody>';
                                    if (bowler1.length > 0) {
                                        //   foreach (keeper1 as wk) { 
                                        for (var wm = 0; wm < bowler1.length; wm++) {
                                            var wk = bowler1[wm];
                                            if (wk['caption'] == 1) {
                                                var chigh = " captain";
                                            }
                                            else if (wk['v_caption'] == 1) {
                                                var chigh = " vcaptain";
                                            } else {
                                                var chigh = "";
                                            }
                                            // tslint:disable-next-line: max-line-length
                                            notmydata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                                            // tslint:disable-next-line: max-line-length
                                            notmydata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> BOWL - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
                                        }
                                    }
                                    else {
                                        notmydata += ' <tr> <td colspan="4"> - </td> </tr></tbody> </table>  </td> </tr> ';
                                    }
                                    notmydata += '</table> </td></tr></tbody></table> </div>  </div>';
                                }


                                if (id != '' && id == userid) {
                                    // tslint:disable-next-line: max-line-length
                                    mydata += ' <div data-target=#open-' +keyid + ' data-toggle="collapse" class="leader-board-div white-box showmyteam ownteamrow"> <div class="leader-div1">' + user_id + '</div> <div class="leader-div2">' + fpoints + '</div> <div class="leader-div3">' + rank + '</div></div>';
                                    // mydata += gethtmldata(players,keyid,alldata);
                                    var img = 'https://fantasy.demozab.com/assets/images/ground-bg1.jpg';
                                    // tslint:disable-next-line: max-line-length
                                    mydata += '<div class="collapse" data-parent="#accordionExample" id="open-' + keyid + '" > <div class="socket white-box v-ground white-t" style="background-image:url(' + img + '); background-size: 100%; background-position: center center;">';
                                    // tslint:disable-next-line: max-line-length
                                    mydata += '<table class="select-table-player white-t"><tbody><tr><td><h6> Wicket-Keepers </h6> <table><tbody>';
                                    if (keeper1.length > 0) {

                                        for (var wi = 0; wi < keeper1.length; wi++) {
                                            var wk = keeper1[wi];
                                            if (wk['caption'] == 1) {
                                                var chigh = " captain";
                                            }
                                            else if (wk['v_caption'] == 1) {
                                                var chigh = " vcaptain";
                                            } else {
                                                var chigh = "";
                                            }
                                            // tslint:disable-next-line: max-line-length
                                            mydata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                                            // tslint:disable-next-line: max-line-length
                                            mydata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> WK - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
                                        }
                                    }
                                    else {
                                        mydata += ' <tr> <td colspan="4"> - </td> </tr> ';
                                    }
                                    mydata += ' </tbody> </table> </td>';
                                    mydata += '  <td> <h6>Batsmen</h6> <table>  <tbody>';
                                    if (batsman1.length > 0) {

                                        for (var wj = 0; wj < batsman1.length; wj++) {
                                            var wk = batsman1[wj];
                                            if (wk['caption'] == 1) {
                                                var chigh = " captain";
                                            }
                                            else if (wk['v_caption'] == 1) {
                                                var chigh = " vcaptain";
                                            } else {
                                                var chigh = "";
                                            }
                                            // tslint:disable-next-line: max-line-length
                                            mydata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                                            // tslint:disable-next-line: max-line-length
                                            mydata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> BAT - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
                                        }
                                    }
                                    else {
                                        mydata += ' <tr> <td colspan="4"> - </td> </tr> ';
                                    }
                                    mydata += ' </tbody> </table> </td>';
                                    mydata += '  <td> <h6>Allrounder</h6> <table>  <tbody>';
                                    if (allrounder1.length > 0) {

                                        for (var wl = 0; wl < allrounder1.length; wl++) {
                                            var wk = allrounder1[wl];
                                            if (wk['caption'] == 1) {
                                                var chigh = " captain";
                                            }
                                            else if (wk['v_caption'] == 1) {
                                                var chigh = " vcaptain";
                                            } else {
                                                var chigh = "";
                                            }
                                            // tslint:disable-next-line: max-line-length
                                            mydata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                                            // tslint:disable-next-line: max-line-length
                                            mydata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> AR - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
                                        }
                                    }
                                    else {
                                        mydata += ' <tr> <td colspan="4"> - </td> </tr> ';
                                    }
                                    mydata += ' </tbody> </table> </td>';
                                    mydata += '  <td> <h6>Bowler</h6> <table>  <tbody>';
                                    if (bowler1.length > 0) {

                                        for (var wm = 0; wm < bowler1.length; wm++) {
                                            var wk = bowler1[wm];
                                            if (wk['caption'] == 1) {
                                                var chigh = " captain";
                                            }
                                            else if (wk['v_caption'] == 1) {
                                                var chigh = " vcaptain";
                                            } else {
                                                var chigh = "";
                                            }
                                            // tslint:disable-next-line: max-line-length
                                            mydata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                                            // tslint:disable-next-line: max-line-length
                                            mydata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> BOWL - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
                                        }
                                    }
                                    else {
                                        mydata += ' <tr> <td colspan="4"> - </td> </tr></tbody> </table>  </td> </tr> ';
                                    }
                                    mydata += '</table> </td></tr></tbody></table> </div>  </div>';
                                }
                            }

                            }
                        }
                        newdata = mydata + notmydata;
                    }
                    $('.notsocketview').hide()
                    $('.myteams').html(newdata);

                }
            }
        };
        this.getAllContest();
    }

    joinContest(entryFee, catid) {
        const form = {
            entryfee: entryFee,
            catid: catid,
            id: this.matchkey,
        }
        this.Comman.joincontest(form).subscribe(
            data => this.handleResponse(data)
            // error => this.handleError(error)
        );
    }
    handleResponse(data) {
        if (data.status === false) {
            this.Notify.error(data.message);
            //  this.ngOnInit();
        } else if (data.status === true) {
            this.Notify.success(data.message);
            this.router.navigate(['contest-info', data.response.match_key, data.response.contest_id, data.response.user_id]);
        }
        else if (data.create_status === false) {
            this.Notify.error(data.message);
        }
    }

    getAllContest() {
        this.data = {
            id: this.matchkey,
            contestid: this.contestid,
        };
        this.Comman.allcontest(this.data).subscribe((all) => {
            this.contest_detail = all['response']['contestdetails'];
            this.contest_display = this.contest_detail;
            // this.contestid = this.contest_detail[0]['_id'];
            // this.contest_display = this.contest_detail;
        });
    }

    getContest() {
        this.data = {
            match_key: this.matchkey,
            contest_id: this.contestid,
        };
        this.Comman.contestinfo(this.data).subscribe((all) => {
            this.contest_details = all['response']['contest_details'];
        });
    }

    getSelectedContest() {
        this.data = {
            id: this.contestid,
        };
        this.Comman.selectedContest(this.data).subscribe((all) => {
            this.contest_detail = all['contestdetails'];
            this.contest_display = this.contest_detail;
            this.winning_details = all['response']['winners'];
            this.list = Object.entries(this.winning_details).map(([type, value]) => ({ type, value }));
        });
    }

    getdetails() {

        this.details = {
            'contestid': this.matchkey,
        };
        this.Comman.userparticipentdetails(this.details).subscribe((all) => {
            this.team_create_status = all['response']['team_create_status'];
        });

    }
 
    getmatchdetails() {
 
        this.data = {
            id: this.matchkey,
            contestid: this.contestid,
        };

        this.Comman.selectmatchdetails(this.data).subscribe((all) => {
            console.log(all);

            this.mydata=[];
            this.notmydata=[];

            let mydataindex = 0;
            let notmydataindex = 1;

            for(let i=0;i < all['fantasypoints'].length;i++){
                if (all['fantasypoints'][i]['user_id'] != '' && all['fantasypoints'][i]['user_id'] == this.user_id) {

                    let newName = {
                        id:mydataindex.toString(),
                        contest_id:all['fantasypoints'][i]['contest_id'],
                        fantasy_points:all['fantasypoints'][i]['fantasy_points'],
                        match_key:all['fantasypoints'][i]['match_key'],
                        paid_status:all['fantasypoints'][i]['paid_status'],
                        price_update_status:all['fantasypoints'][i]['price_update_status'],
                        price_winning_amount:all['fantasypoints'][i]['price_winning_amount'],
                        rank:all['fantasypoints'][i]['rank'],
                        user_id:all['fantasypoints'][i]['user_id'],
                        user_name:all['fantasypoints'][i]['user_name'],
                        winner_status:all['fantasypoints'][i]['winnner_status'],
                        $oid:all['fantasypoints'][i]['_id'].$oid,
               };
                    this.fantasypoints.push(newName);
                    notmydataindex++;
                }


                if (all['fantasypoints'][i]['user_id'] != this.user_id) {

                    let newName = {
                        id:notmydataindex.toString(),
                        contest_id:all['fantasypoints'][i]['contest_id'],
                        fantasy_points:all['fantasypoints'][i]['fantasy_points'],
                        match_key:all['fantasypoints'][i]['match_key'],
                        paid_status:all['fantasypoints'][i]['paid_status'],
                        price_update_status:all['fantasypoints'][i]['price_update_status'],
                        price_winning_amount:all['fantasypoints'][i]['price_winning_amount'],
                        rank:all['fantasypoints'][i]['rank'],
                        user_id:all['fantasypoints'][i]['user_id'],
                        user_name:all['fantasypoints'][i]['user_name'],
                        winner_status:all['fantasypoints'][i]['winner_status'],
                        $oid:all['fantasypoints'][i]['user_id'],
                     };

                    this.fantasypoints.push(newName);
                 }
              }

            // this.fantasypoints.push(this.mydata)
            // this.fantasypoints.push(this.notmydata);

              this.fantasypoints.sort(this.compare);

            if (all['response']['status'] === 'notstarted') {
                this.time_left = all['response']['left_time'],
                this.team1_key = all['response']['team1_key'],
                this.team2_key = all['response']['team2_kay'],
                this.fantasypoints = this.fantasypoints,
                this.fantasypointscount = this.fantasypoints.length,
                // this.fantasypoints = all['fantasypoints'],
                this.range = all['range'],
                this.teamcount = all['teamcount'],
                this.msg_completed = all['response']['msg_completed'];
            } else if (all['response']['status'] === 'started') {
                this.msg_completed = all['response']['msg_completed'];
                this.time_left = all['response']['status'],
                this.team1_key = all['response']['team1_key'],
                this.team2_key = all['response']['team2_kay'],
                this.fantasypoints = this.fantasypoints,
                this.fantasypointscount = this.fantasypoints.length,
                // this.fantasypoints = all['fantasypoints'],
                this.teamcount = all['teamcount'],
                this.msg_completed = all['response']['msg_completed'];
                if (all['response']['score_team2']) {
                    this.run_str2 = all['response']['score_team2']['run_str'];
                    this.team_name2 = all['response']['score_team2']['team_name'];
                }   
                if (all['response']['score_team1']) {
                    this.team_name1 = all['response']['score_team1']['team_name'];
                    this.run_str1 = all['response']['score_team1']['run_str'];
                }
            } else if (all['response']['status'] === 'completed') {
                this.time_left = all['response']['status'],
                this.team1_key = all['response']['team1_key'],
                this.team2_key = all['response']['team2_kay'],
                this.fantasypoints = this.fantasypoints,
                // this.fantasypoints = all['fantasypoints'],
                this.fantasypointscount = this.fantasypoints.length,
                this.teamcount = all['teamcount'],
                this.listcount = all['spotcount'],
                this.teamname = all['team_name']['user_name'],
                this.msg_completed = all['response']['msg_completed'];
                if (all['response']['score_team2']) {
                    this.run_str2 = all['response']['score_team2']['run_str'];
                    this.team_name2 = all['response']['score_team2']['team_name'];
                }   
                if (all['response']['score_team1']) {
                    this.team_name1 = all['response']['score_team1']['team_name'];
                    this.run_str1 = all['response']['score_team1']['run_str'];
                }
            }
        });
    }

    compare(a, b) {
        const bandA = a.id;
        const bandB = b.id;

        let comparison = 0;
        if (bandA > bandB) {
          comparison = 1;
        } else if (bandA < bandB) {
          comparison = -1;
        }
        return comparison;
      }

    getelevenplayers(teamid) {

        this.data = {
            teamid: teamid
        };

        this.Comman.getelevenplayer(this.data).subscribe((all) => {

            var wkindex = 0;
            var bowlindex = 0;
            var batindex = 0;
            var arindex = 0;

            this.viewwkplayer=[];
            this.viewbatplayer=[];
            this.viewbowlplayer=[];
            this.viewarplayer=[];

            // tslint:disable-next-line: prefer-for-of

            for (let i = 0; i < all['response']['players'].length; i++) {

                if(all['response']['players'][i]['role'] == 'wk'){
                           let newName = {
                                id:batindex.toString(),
                                caption:all['response']['players'][i]['caption'],
                                credit:all['response']['players'][i]['credit'],
                                fantasy_points:all['response']['players'][i]['fantasy_points'],
                                name:all['response']['players'][i]['name'],
                                player_key:all['response']['players'][i]['player_key'],
                                team_name:all['response']['players'][i]['team_name'],
                                role:all['response']['players'][i]['role'],
                                v_caption:all['response']['players'][i]['v_caption']
                       };
                            this.viewwkplayer.push(newName);

                            wkindex++;

                    }else if(all['response']['players'][i]['role'] == 'bat'){

                        let newName = {
                                id:batindex.toString(),
                                caption:all['response']['players'][i]['caption'],
                                credit:all['response']['players'][i]['credit'],
                                fantasy_points:all['response']['players'][i]['fantasy_points'],
                                name:all['response']['players'][i]['name'],
                                player_key:all['response']['players'][i]['player_key'],
                                team_name:all['response']['players'][i]['team_name'],
                                role:all['response']['players'][i]['role'],
                                v_caption:all['response']['players'][i]['v_caption']
                            };
                            this.viewbatplayer.push(newName);
                            batindex++;

                    }else if(all['response']['players'][i]['role'] == 'bowl'){

                        let newName = {
                                id:batindex.toString(),
                                caption:all['response']['players'][i]['caption'],
                                credit:all['response']['players'][i]['credit'],
                                fantasy_points:all['response']['players'][i]['fantasy_points'],
                                name:all['response']['players'][i]['name'],
                                player_key:all['response']['players'][i]['player_key'],
                                team_name:all['response']['players'][i]['team_name'],
                                role:all['response']['players'][i]['role'],
                                v_caption:all['response']['players'][i]['v_caption']
                            };
                            this.viewbowlplayer.push(newName);
                            bowlindex++;

                    }else if(all['response']['players'][i]['role'] == 'ar'){

                        let newName = {
                                id:batindex.toString(),
                                caption:all['response']['players'][i]['caption'],
                                credit:all['response']['players'][i]['credit'],
                                fantasy_points:all['response']['players'][i]['fantasy_points'],
                                name:all['response']['players'][i]['name'],
                                player_key:all['response']['players'][i]['player_key'],
                                team_name:all['response']['players'][i]['team_name'],
                                role:all['response']['players'][i]['role'],
                                v_caption:all['response']['players'][i]['v_caption']
                            };
                            this.viewarplayer.push(newName);
                            arindex++;
                }
        }
        });

    }

    gethtmldata1(players,keyid) {

        var alldata = '';

        var keeper1 = [];
        var batsman1 = [];
        var allrounder1 = [];
        var bowler1 = [];

        keeper1 = players['keeper'];
        bowler1 = players['bowler'];
        batsman1 = players['batsman'];
        allrounder1 = players['allrounder'];

        var img = "https://fantasy.demozab.com/assets/images/ground-bg1.jpg";
        // tslint:disable-next-line: max-line-length
        alldata += '<div class="collapse" data-parent="#accordionExample" id="open-' + keyid + '" > <div class="socket white-box v-ground white-t" style="background-image:url(' + img + '); background-size: 100%; background-position: center center;">';
        alldata += '<table class="select-table-player white-t"><tbody><tr><td><h6> Wicket-Keepers </h6> <table><tbody>';
        if (keeper1.length > 0) {
            //   foreach (keeper1 as wk) { 
            for (var wi = 0; wi < keeper1.length; wi++) {
                var wk = keeper1[wi];
                if (wk['caption']) {
                    var chigh = " captain";
                }
                else if (wk['v_caption']) {
                    var chigh = " vcaptain";
                } else {
                    var chigh = "";
                }
                alldata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                // tslint:disable-next-line: max-line-length
                alldata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> WK - ' + wk.team_name + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
            }
        }
        else {
            alldata += ' <tr> <td colspan="4"> - </td> </tr> ';
        }
        alldata += ' </tbody> </table> </td>';
        alldata += '  <td> <h6>Batsmen</h6> <table>  <tbody>';
        if (batsman1.length > 0) {
            //   foreach (keeper1 as wk) { 
            for (var wj = 0; wj < batsman1.length; wj++) {
                var wk = batsman1[wj];
                if (wk['caption']) {
                    var chigh = " captain";
                }
                else if (wk['v_caption']) {
                    var chigh = " vcaptain";
                } else {
                    var chigh = "";
                }
                alldata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                // tslint:disable-next-line: max-line-length
                alldata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> WK - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
            }
        }
        else {
            alldata += ' <tr> <td colspan="4"> - </td> </tr> ';
        }
        alldata += ' </tbody> </table> </td>';
        alldata += '  <td> <h6>Allrounder</h6> <table>  <tbody>';
        if (allrounder1.length > 0) {
            //   foreach (keeper1 as wk) { 
            for (var wl = 0; wl < allrounder1.length; wl++) {
                var wk = allrounder1[wl];
                if (wk['caption']) {
                    var chigh = " captain";
                }
                else if (wk['v_caption']) {
                    var chigh = " vcaptain";
                } else {
                    var chigh = "";
                }
                alldata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                // tslint:disable-next-line: max-line-length
                alldata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> WK - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
            }
        }
        else {
            alldata += ' <tr> <td colspan="4"> - </td> </tr> ';
        }
        alldata += ' </tbody> </table> </td>';
        alldata += '  <td> <h6>Bowler</h6> <table>  <tbody>';
        if (bowler1.length > 0) {
            //   foreach (keeper1 as wk) { 
            for (var wm = 0; wm < bowler1.length; wm++) {
                var wk = bowler1[wm];
                if (wk['caption']) {
                    var chigh = " captain";
                }
                else if (wk['v_caption']) {
                    var chigh = " vcaptain";
                } else {
                    var chigh = "";
                }
                alldata += ' <tr class="player-data-td ' + chigh + '"> <td class="player-pic"><img src="assets/images/player.svg"></td>';
                // tslint:disable-next-line: max-line-length
                alldata += ' <td class="player-name h6">' + wk['name'] + '<br><span class="grey-t s-t"> WK - ' + wk['team_name'] + '</span></td><td>' + wk["fantasy_points"] + '<br><span class="grey-t s-t">Points</span></td> </tr> ';
            }
        }
        else {
            alldata += ' <tr> <td colspan="4"> - </td> </tr></tbody> </table>  </td> </tr> ';
        }
        alldata += '</table> </div>  </div>';
        // return alldata;

    }

}
