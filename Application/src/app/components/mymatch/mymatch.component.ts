import { Component, OnInit } from '@angular/core';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { TokenService } from 'src/app/services/token.service';

@Component({
  selector: 'app-mymatch',
  host: {'class': 'shrink tab-pages'},
  templateUrl: './mymatch.component.html',
  styleUrls: ['./mymatch.component.css']
})
export class MymatchComponent implements OnInit {

  public error:any = []; // Register Server Side Validation Error Variable

  completed : any;
  live : any; 
  upcomming : any;
  authid : any;

  complen :any;
  livelen :any;
  uplen :any;

  constructor(
    private Token: TokenService,
    private Auth: AuthService,
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router
  ) { }

  ngOnInit() {
    this.getmyMatch();
  }

  getmyMatch(){
    this.Comman.mymatchdetails().subscribe((all) => {
        this.completed = all['response']['completed'];
        this.live = all['response']['live'];
        this.upcomming = all['response']['upcomming'];
        this.authid = all['authid'];

        this.complen = all['response']['comp_length'];
        this.livelen = all['response']['live_length'];
        this.uplen = all['response']['up_length'];

    });
  }
}
