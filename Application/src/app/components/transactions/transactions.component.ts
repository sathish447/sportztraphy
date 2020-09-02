import { Component ,OnInit, Output, EventEmitter, ElementRef, ViewChild, Input, OnDestroy } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/services/common.service';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgForm } from '@angular/forms';
import { FormBuilder, FormGroup, Validators, FormArray, FormControl } from '@angular/forms';
import { DatePipe } from '@angular/common';



@Component({
  selector: 'app-transactions',
  templateUrl: './transactions.component.html',
  styleUrls: ['./transactions.component.css']
})
export class TransactionsComponent implements OnInit {
  @Input() options = {};
  @Output() scrolled = new EventEmitter();
  // @ViewChild('anchor') anchor: ElementRef<HTMLElement>;
  data: any;
  trans_details: any;
  config:any;
  collection:any;
  totalItems:any;
  notscrolly:any;
  notEmptyPost:any;
  spinner:any;
  page: number = 1; 

  public p: number = 1;

  constructor(
    private http: HttpClient,
    private Comman: CommonService,
    private Notify: ToastrService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
  ) { }

  ngOnInit() {
    this.getTransaction();
  }
  onScrollDown() {
    this.page = this.page + 1;  
    this.getTransaction(); 
  }

  getTransaction() {

    this.data = {
      'offset': 0,
      'limit': 50,
    };

      this.Comman.getTrans(this.data).subscribe((all) => {
      this.trans_details = all['response']['details'];
 
      this.collection = { count: this.trans_details.length, data: this.trans_details };
      this.totalItems =  this.collection.count;

      this.config = {
            itemsPerPage: 10,     
            currentPage: 1,
            totalItems: this.collection.count
          };

      console.log(this.trans_details);
    });
  }

  pageChanged(event){
    this.config.currentPage = event;
  }



}
