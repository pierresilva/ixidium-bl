import {Component, OnDestroy, OnInit} from '@angular/core';

declare var $: any;

@Component({
  selector: 'renova-auth-change-password',
  templateUrl: './auth-change-password.component.html',
  styleUrls: ['./auth-change-password.component.scss']
})
export class AuthChangePasswordComponent implements OnInit, OnDestroy {

  constructor() { }

    ngOnInit() {
        $('renova-header').addClass('hidden');
        $('renova-footer').addClass('hidden');
        $('main').addClass('main-auth-page');
    }

    ngOnDestroy() {
        $('renova-header').removeClass('hidden');
        $('renova-footer').removeClass('hidden');
        $('main').removeClass('main-auth-page');
    }

}
