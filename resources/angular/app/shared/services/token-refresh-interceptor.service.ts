import { Injectable, Injector } from '@angular/core';
import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse, HttpClient } from '@angular/common/http';
import { environment } from './../../../environments/environment';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class TokenRefreshInterceptorService implements HttpInterceptor {
    attemps = 0;
    constructor(private injector: Injector) {}

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {

        return next.handle(request).catch((errorResponse: HttpErrorResponse) => {
            const error = (typeof errorResponse.error !== 'object') ? JSON.parse(errorResponse.error) : errorResponse;

            if (errorResponse.status === 401 && error.error.message === 'Unauthenticated.') {
                this.attemps ++;
                const http = this.injector.get(HttpClient);
                if (this.attemps > 1) {
                    this.attemps = 0;
                    return Observable.throw(errorResponse);
                }
                return http.post<any>(`${environment.api_url}auth/refresh`, {})
                    .flatMap(data => {
                      localStorage.setItem('token', data.token);
                        const cloneRequest = request.clone({setHeaders: {'Authorization': `Bearer ${data.token}`}});
                        return next.handle(cloneRequest);
                    });
            }

            return Observable.throw(errorResponse);
        });

    }
}
