import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';
import { CommonService } from 'src/app/services/common.service';
import { Router, RoutesRecognized, NavigationEnd } from '@angular/router';
import {Location} from '@angular/common';
import 'rxjs/add/operator/filter';
import 'rxjs/add/operator/pairwise';


@Component({
  selector: 'app-inner-header',
  templateUrl: './inner-header.component.html',
  styleUrls: ['./inner-header.component.css']
})
export class InnerHeaderComponent implements OnInit {

  matchcount : any;
  previousurl:any;
  tempurl:any;
  urlstatus:any;
  public currenturl: string = "";

  constructor(
      private Auth: AuthService,
      private Comman: CommonService,
      private router: Router,
      private location: Location

  ) {  }

  ngOnInit() {
    this.currenturl = this.router.url;
    this.mymatchcount();
    this.urlstatus = 0;

    if ( this.router.url.indexOf('/contests') > -1 ||
         this.router.url.indexOf('/viewteam') > -1 ||
         this.router.url.indexOf('/team-create') > -1 ||
         this.router.url.indexOf('/create-contest') > -1 ||
         this.router.url.indexOf('/mycontest') > -1 ||
         this.router.url.indexOf('/contest-info') > -1 ||
         this.router.url.indexOf('/account-verify') > -1 ||
         this.router.url.indexOf('/withdraw') > -1 ||
         this.router.url.indexOf('/editteam') > -1 ||
         this.router.url.indexOf('/contest-info') > -1 )
         {
            this.urlstatus = 1;
        }


    }

  mymatchcount() {
    this.Comman.mymatchcount().subscribe((all) => {
    this.matchcount = all['response'];
    });
  } 

  backClicked() {
    // this.router.navigateByUrl(this.previousurl);
    this.location.back();
  }

  logout(Event: MouseEvent){

    event.preventDefault();
    this.Comman.logout();
  }

}
