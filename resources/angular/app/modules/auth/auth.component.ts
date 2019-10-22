import {Component, OnDestroy, OnInit, ViewEncapsulation} from '@angular/core';

declare var $: any;

@Component({
    selector: 'renova-auth',
    templateUrl: './auth.component.html',
    styleUrls: ['./auth.component.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class AuthComponent implements OnInit, OnDestroy {

    constructor() {
    }

    ngOnInit() {
        $('ul.tabs').tabs();

        $('renova-header').addClass('hidden');
        $('renova-footer').addClass('hidden');
        $('#video-background').show();
        $('main').addClass('main-auth-page');
    }

    ngOnDestroy() {
        $('renova-header').removeClass('hidden');
        $('renova-footer').removeClass('hidden');
        $('main').removeClass('main-auth-page');
        $('#video-background').hide();
    }

}
