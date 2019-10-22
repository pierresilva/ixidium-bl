import {Component, OnInit, ViewEncapsulation} from '@angular/core';

declare var $: any;

@Component({
    selector: 'renova-home',
    templateUrl: './home.component.html',
    styleUrls: ['./home.component.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class HomeComponent implements OnInit {

    constructor() {
    }

    ngOnInit() {
        $('.parallax').parallax();
        $('.slider').slider({indicators: false});
    }


}
