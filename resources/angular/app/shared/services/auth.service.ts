import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { environment } from '../../../environments/environment';
import { JwtService } from './jwt.service';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { ReplaySubject } from 'rxjs/ReplaySubject';
import { User } from '../models/user';
import { ApiService } from './api.service';
import { MzToastService } from '@ngx-materialize';
import { Observable } from 'rxjs/Observable';
import { MessageService } from './message.service';
import 'rxjs/add/operator/distinctUntilChanged';
import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/do';

@Injectable()
export class AuthService {

  public currentUserSubject = new BehaviorSubject<any>({});
  public currentUser = this.currentUserSubject.asObservable().distinctUntilChanged();

  public userRolesSubject = new BehaviorSubject(null);
  public userRoles = this.userRolesSubject.asObservable().distinctUntilChanged();

  public userPermissionsSubject = new BehaviorSubject(null);
  public userPermissions = this.userPermissionsSubject.asObservable().distinctUntilChanged();

  public userProfilesSubject = new BehaviorSubject(null);
  public userProfiles = this.userProfilesSubject.asObservable().distinctUntilChanged();

  public userActionsSubject = new BehaviorSubject(null);
  public userActions = this.userActionsSubject.asObservable().distinctUntilChanged();

  public isAuthenticatedSubject = new BehaviorSubject<boolean>(false);
  public isAuthenticated = this.isAuthenticatedSubject.asObservable();

  public isLockedSubject = new ReplaySubject<boolean>(1);
  public isLocked = this.isLockedSubject.asObservable();

  constructor(
    private jwtService: JwtService,
    private http: HttpClient,
    private apiService: ApiService,
    private toastService: MzToastService,
    private router: Router,
    private messageService: MessageService
  ) { }

  check(): boolean {
    return localStorage.getItem('token') ? true : false;
  }

  getIsLogged(): Observable<any> {
    return this.isAuthenticated;
  }

  login(credentials: { email: string, password: string }): Observable<boolean> {
    return this.apiService.post('transversal-security/login', credentials)
      .do(data => {
        localStorage.setItem('token', data.token);
        localStorage.setItem('meta', data.meta);
        this.currentUserSubject.next(atob(data.meta));
        this.userProfilesSubject.next(this.jwtService.tokenProfiles());
        this.userActionsSubject.next(this.jwtService.tokenActions());
        this.isAuthenticatedSubject.next(true);
        this.toastService.show(
          this.messageService.get('Bienvenido ' + this.getUserAttribute('first_name') + '!'),
          4000,
          'green',
        );
      });
  }

  register(credentials) {
    return this.apiService.post('auth/register', credentials)
      .do(data => {
        localStorage.setItem('token', data.token);
        localStorage.setItem('meta', data.meta);
        this.currentUserSubject.next(atob(data.meta));
        this.userProfilesSubject.next(this.jwtService.tokenProfiles());
        this.userActionsSubject.next(this.jwtService.tokenActions());
        this.isAuthenticatedSubject.next(true);
        this.toastService.show(
          this.messageService.get('auth-register-success'),
          4000,
          'green',
        );
      });
  }

  logout() {
    return this.apiService.post('auth/logout', {})
      .catch((error): any => {
        localStorage.clear();
        this.currentUserSubject.next(new User());
        this.userProfilesSubject.next(['invitado']);
        this.userActionsSubject.next(['invitado']);
        this.isAuthenticatedSubject.next(false);
        this.toastService.show(
          this.messageService.get('auth-logout-success'),
          4000,
          'green',
        );
      })
      .do(resp => {
        localStorage.clear();
        this.currentUserSubject.next(new User());
        this.userProfilesSubject.next(['invitado']);
        this.userActionsSubject.next(['invitado']);
        this.isAuthenticatedSubject.next(false);
        this.toastService.show(
          this.messageService.get('auth-logout-success'),
          4000,
          'green',
        );
      });
  }

  forceLogout() {
    localStorage.clear();
    this.currentUserSubject.next(new User());
    this.userProfilesSubject.next(['invitado']);
    this.userActionsSubject.next(['invitado']);
    this.isAuthenticatedSubject.next(false);
    this.toastService.show(
      this.messageService.get('auth-logout-success'),
      4000,
      'green',
    );
  }


  getUser(): User {
    return localStorage.getItem('meta') ? JSON.parse(atob(localStorage.getItem('meta'))) : null;
  }

  setUser(): Promise<boolean> {
    return this.http.get<any>(`${environment.api_url}auth/me`).toPromise()
      .then(data => {
        if (data.user) {
          localStorage.setItem('meta', btoa(JSON.stringify(data)));
          return true;
        }
        return false;
      });
  }

  userHasPermission(permissions) {
    let token;
    token = this.jwtService.decodeToken();
    if (!token) {
      return false;
    }
    for (let i = 0; i < permissions.length; i++) {
      for (let j = 0; j < token.permissions.length; j++) {
        if (token.permissions[j] === permissions[i]) {
          return true;
        }
      }
    }
    return false;
  }

  userHasRole(roles) {
    let token;
    token = this.jwtService.decodeToken();
    if (!token) {
      return false;
    }
    for (let i = 0; i < roles.length; i++) {
      for (let j = 0; j < token.roles.length; j++) {
        if (token.roles[j] === roles[i]) {
          return true;
        }
      }
    }
    return false;
  }

  userCan(actions) {
    let token;
    token = this.jwtService.decodeToken();
    let tokenActions = '';

    if (token) {
      tokenActions = JSON.parse(token.actions);
      for (let i = 0; i < actions.length; i++) {
        for (let j = 0; j < tokenActions.length; j++) {
          if (tokenActions[j] === actions[i]) {
            return true;
          }
        }
      }
    }

    return false;
  }

  userIs(profiles) {
    let token;
    token = this.jwtService.decodeToken();
    let tokenProfiles = '';

    if (token) {
      tokenProfiles = JSON.parse(token.profiles);
      for (let i = 0; i < profiles.length; i++) {
        for (let j = 0; j < tokenProfiles.length; j++) {
          if (tokenProfiles[j] === profiles[i]) {
            return true;
          }
        }
      }
    }

    return false;
  }

  userLogged() {
    let token;
    token = this.jwtService.decodeToken();
    if (token) {
      return true;
    }
    return false;
  }

  populateObservables() {
    const token = this.jwtService.decodeToken();
    if (token) {
      this.currentUserSubject.next(token);
      this.userProfilesSubject.next(this.jwtService.tokenProfiles());
      this.userActionsSubject.next(this.jwtService.tokenActions());
      this.isAuthenticatedSubject.next(true);
    } else {
      this.isAuthenticatedSubject.next(false);
      this.userProfilesSubject.next(this.jwtService.tokenProfiles());
      this.userActionsSubject.next(this.jwtService.tokenActions());
      this.currentUserSubject.next(new User());
    }
  }

  getUserAttribute(attribute) {
    let token;
    token = this.jwtService.decodeToken();
    if (token) {
      return token[attribute];
    }
    return '';
  }
}
