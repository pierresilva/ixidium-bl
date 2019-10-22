import {Component, Input, OnInit} from '@angular/core';
import {AlertService} from '../alert.service';

declare var $: any;

@Component({
  selector: 'renova-alert-service',
  templateUrl: './alert-service.component.html',
  styleUrls: ['./alert-service.component.scss']
})
export class AlertServiceComponent implements OnInit {
    type: any;
    title: any;
    message: any;

    constructor(private alertService: AlertService) { }

  ngOnInit() {
      $('.modal').modal();
      // this function waits for a message from alert service, it gets
      // triggered when we call this from any other component
      this.alertService.getMessage().subscribe(
          message => {
              this.message = message;
          });
  }

}

