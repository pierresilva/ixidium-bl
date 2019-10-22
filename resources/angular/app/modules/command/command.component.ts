import { Component, OnInit } from '@angular/core';
import {ApiService} from '../../shared/services/api.service';

@Component({
  selector: 'renova-command',
  templateUrl: './command.component.html',
  styleUrls: ['./command.component.css']
})
export class CommandComponent implements OnInit {

  command = '';
  output = '';
  constructor(
    private api: ApiService,
  ) { }

  ngOnInit() {
  }

  sendCommand() {
    this.api.post('run-command', {
      'command': this.command
    }).subscribe(async (res: any) => {
      this.output = await res.data;
    });
  }

}
