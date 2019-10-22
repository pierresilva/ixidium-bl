import {Injectable} from '@angular/core';
import {HttpEvent, HttpInterceptor, HttpHandler, HttpRequest} from '@angular/common/http';
import {Observable} from 'rxjs/Rx';
import 'rxjs/add/observable/throw';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/finally';
import {MzToastService} from '@ngx-materialize';
import {environment} from '../../../environments/environment';
import {LoadingHttpService} from './loading-http.service';

@Injectable()
export class LoadingInterceptorService implements HttpInterceptor {
    pendingRequests = 0;

    constructor(private loading: LoadingHttpService,
                private toastService: MzToastService) {
    }

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {

        const authReq = req.clone();

        this.track(true);

        return next.handle(authReq)
            .catch((error, caught) => {
                if (error.error.message) {
                    this.toastService.show(
                        error.error.message,
                        4000,
                        'red'
                    );
                } else {
                    this.toastService.show(
                        'Ocurrio un error!',
                        4000,
                        'red'
                    );
                }

                if (error.error.errors) {
                    for (let key in error.error.errors) {

                      if ( error.status === 422 ) {
                        this.toastService.show(
                          // error.error[key][0],
                          error.error.errors[key],
                          4000,
                          'red'
                        );
                      }

                      if ( environment.production === false &&  error.status !== 422) {
                        this.toastService.show(
                          // error.error[key][0],
                          error.error.errors[key],
                          4000,
                          'red'
                        );
                      }
                    }
                }
                return Observable.throw(error);
            })
            .finally(() => {
                this.track(false);
            });
    }

    track(track: boolean): void {
        if (track) {
            this.pendingRequests++;
        } else {
            this.pendingRequests--;
        }
        if (this.pendingRequests > 0) {
            this.loading.isLoadingSubject.next(true);
        } else {
            this.loading.isLoadingSubject.next(false);
        }

    }
}
