import {Injectable} from '@angular/core';
import {CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, CanActivateChild, Router} from '@angular/router';
import {Observable} from 'rxjs/Observable';
import {AuthService} from '../services/auth.service';
import {MzToastService} from '@ngx-materialize';
import {MessageService} from '../services/message.service';
import {LoadingHttpService} from '../services/loading-http.service';

@Injectable()
export class AuthGuard implements CanActivate, CanActivateChild {

    constructor(private auth: AuthService,
                private toast: MzToastService,
                private message: MessageService,
                private router: Router,
                private loading: LoadingHttpService) {
    }

    canActivate(next: ActivatedRouteSnapshot,
                state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        if (next.data.logged) {
            if (!(this.auth.check() === next.data.logged)) {
                this.showToast('No está logueado!');
                return false;
            }
        }
        if (next.data.profiles !== undefined) {
            if (!this.auth.userIs(next.data.profiles)) {
                this.showToast('No tiene el perfil!');
                return false;
            }
        }
        if (next.data.actions !== undefined) {
            if (!this.auth.userCan(next.data.actions)) {
                this.showToast('No puede ejecutar la acción!');
                return false;
            }
        }
        return true;
    }

    canActivateChild(next: ActivatedRouteSnapshot,
                     state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        if (next.data.logged) {
            if (!(this.auth.check() === next.data.logged)) {
                this.showToast(this.message.get('error-not-logged'));
                return false;
            }
        }
        if (next.data.profiles) {
            if (!this.auth.userIs(next.data.profiles)) {
                this.showToast(this.message.get('error-not-has-role'));
                return false;
            }
        }
        if (next.data.actions) {
            if (!this.auth.userCan(next.data.actions)) {
                this.showToast(this.message.get('error-not-has-permission'));
                return false;
            }
        }
        return true;
    }

    showToast(message: string) {
        this.loading.isLoadingSubject.next(false);
        this.router.navigateByUrl('/home');
        this.toast.show(
            message,
            4000,
            'orange',
        );
    }


}
