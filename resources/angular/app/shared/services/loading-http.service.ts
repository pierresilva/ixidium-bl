import {Injectable, OnInit} from '@angular/core';
import {ReplaySubject} from 'rxjs/ReplaySubject';

@Injectable()
export class LoadingHttpService implements OnInit {
    public isLoadingSubject = new ReplaySubject<boolean>(1);
    public isLoading = this.isLoadingSubject.asObservable();

    constructor() {
    }

    ngOnInit() {
        this.isLoadingSubject.next(false);
    }

}
