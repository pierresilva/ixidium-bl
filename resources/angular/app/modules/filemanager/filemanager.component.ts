import { Component, OnInit, OnDestroy } from '@angular/core';

@Component({
  selector: 'renova-filemanager',
  templateUrl: './filemanager.component.html',
  styleUrls: ['./filemanager.component.scss']
})
export class FilemanagerComponent implements OnInit, OnDestroy {

  constructor() { }

  ngOnInit() {
    $('renova-header').addClass('hidden');
    $('renova-footer').addClass('hidden');
  }

  ngOnDestroy() {
    $('renova-header').removeClass('hidden');
    $('renova-footer').removeClass('hidden');
  }

}
