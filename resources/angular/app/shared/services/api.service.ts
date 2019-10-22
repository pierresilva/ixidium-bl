import {Injectable, OnInit} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';

import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/finally';

import {environment} from '../../../environments/environment';
import {Router} from '@angular/router';
import {LoadingHttpService} from './loading-http.service';

@Injectable()

/**
 * Call Backend Http request service
 */
export class ApiService implements OnInit {

    constructor(private http: HttpClient,
                private router: Router,
                private loading: LoadingHttpService) {
    }

    ngOnInit() {
        this.loading.isLoadingSubject.next(false);
    }

    /**
     * GET Http Request
     *
     * @param {string} path
     * @param {HttpParams} params
     * @returns {Observable<any>}
     */
    get(path: string, params: any = {}): Observable<any> {
        return this.http.get<any>(`${environment.api_url}${path}`, params)
            .do(data => { });
    }

    /**
     * GET Http Request. External
     *
     * @param {string} path
     * @param {HttpParams} params
     * @returns {Observable<any>}
     */
    getFull(path: string, params: any): Observable<any> {
        return this.http.get<any>(`${path}`, params)
            .do(data => { });
    }

    /**
     * PUT Http Request
     *
     * @param {string} path
     * @param {Object} body
     * @returns {Observable<any>}
     */
    put(path: string, body: any): Observable<any> {
        return this.http.put<any>(`${environment.api_url}${path}`, body)
            .do(data => { });
    }

    /**
     * POST Http Request
     *
     * @param {string} path
     * @param {object} body
     * @returns {Observable<any>}
     */
    post(path: string, body: object): Observable<any> {
        return this.http.post<any>(`${environment.api_url}${path}`, body)
            .do(data => { });
    }

    /**
     * DELETE Http Request
     *
     * @param {string} path
     * @returns {Observable<any>}
     */
    delete(path: string, body: any): Observable<any> {
        return this.http.delete<any>(`${environment.api_url}${path}`, body)
            .do(data => { });
    }

}
