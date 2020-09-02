import { Component, OnInit } from '@angular/core';
import * as $ from 'jquery';

@Component({
  selector: 'app-inner-footer',
  templateUrl: './inner-footer.component.html',
  styleUrls: ['./inner-footer.component.css']
})
export class InnerFooterComponent implements OnInit {

  constructor() { }

  ngOnInit() {
    $(document).on("scroll", function(){
      if
        ($(document).scrollTop() >300){
        $(".page-content-whl").addClass("shrink");
      }
      else
      {
        $(".page-content-whl").removeClass("shrink");
      }
    });
  }

}
