import {Injectable, OnInit} from '@angular/core';
import {ReplaySubject} from 'rxjs/ReplaySubject';

@Injectable()
export class OnlineHelpService implements OnInit {

    public helpSubject = new ReplaySubject<boolean>(1);
    public help = this.helpSubject.asObservable();

    constructor() {
    }

    ngOnInit(): void {
        this.helpSubject.next(false);
    }

}
