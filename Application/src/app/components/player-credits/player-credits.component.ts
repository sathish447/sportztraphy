import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { defaultDayOfMonthOrdinalParse } from 'ngx-bootstrap/chronos/locale/locale.class';
// import { NgForm } from '@angular/forms';

@Component({
  selector: 'app-player-credits',
  templateUrl: './player-credits.component.html',
  styleUrls: ['./player-credits.component.css']
})
export class PlayerCreditsComponent implements OnInit {
playerListteam1: any  = []; 
playerListteam2: any  = []; 
  constructor(
      private Comman: CommonService,
      private Notify: ToastrService,
      private router: Router,
      private activatedRoute: ActivatedRoute,
  ) { }

  ngOnInit() {
      this.me();
  }

    me(){
    this.Comman.playercredits(this.playerListteam1).subscribe(
      data => {
        console.log(data);
        //this.userphone.push(data['userphone']);

        this.playerListteam1 = data['team1'];
        this.playerListteam2 = data['team2'];

      }
    );
  }

}
