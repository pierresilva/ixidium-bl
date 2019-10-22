import {Directive, EventEmitter, HostListener, OnInit, Output, Input, AfterViewInit} from '@angular/core';

@Directive({
    selector: '[renovaScrollTo]'
})
export class ScrollToDirective implements OnInit {
    element: any;

    @Input() scrollTo = 'top';

    constructor() {
    }

    ngOnInit() {

    }

    @HostListener('click', ['$event'])
    clickEvent(event) {
        event.preventDefault();
        event.stopPropagation();
        try {
            document.querySelector('#' + this.scrollTo[0]).scrollIntoView({
                behavior: 'smooth'
            });
        } catch (e) {
            document.querySelector('#top').scrollIntoView({
                behavior: 'smooth'
            });
        }

    }

}
