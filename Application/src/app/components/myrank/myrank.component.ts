import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { FormBuilder, FormGroup, Validators, FormArray, FormControl } from '@angular/forms';
import { DatePipe } from '@angular/common';

@Component({
  selector: 'app-myrank',
  templateUrl: './myrank.component.html',
  styleUrls: ['./myrank.component.css']
})
export class MyrankComponent implements OnInit {

  data: any;
  trans_details: any;
  config:any;
  collection:any;
  totalItems:any;

  public p: number = 1;

  constructor(
    private http: HttpClient,
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
  ) { }

  ngOnInit() {
    this.getranks();
  }
 
  getranks() {
    this.data = {
      'offset': 0,
      'limit': 50,
    }; 

    this.Comman.getRankDetails(this.data).subscribe((all) => {
      this.trans_details = all['response']['details'];
      this.collection = { count: this.trans_details.length, data: this.trans_details };
      this.totalItems = this.collection.count;
      console.log(this.collection);
      this.config = {
            itemsPerPage: 10,
            currentPage: 1,
            totalItems: this.collection.count
          };
    });
  }

  pageChanged(event){
    this.config.currentPage = event;
  }

}
