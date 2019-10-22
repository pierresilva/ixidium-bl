import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'renova-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.scss']
})
export class FooterComponent implements OnInit {
  dateYear: any;
  constructor() { }

  ngOnInit() {
    this.dateYear = new Date().getFullYear();
  }

}
