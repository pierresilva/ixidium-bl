import { Injectable } from '@angular/core';

@Injectable()
export class JwtService {

  constructor() { }
  /**
   * Return the user token
   *
   * @returns {String}
   */
  getToken(): String {
    return window.localStorage['meta'];
  }

  /**
   * Save the given token
   *
   * @param {String} token
   */
  saveToken(token: String) {
    window.localStorage['jwtToken'] = token;
  }

  /**
   * Remove token from localStorage
   */
  destroyToken() {
    window.localStorage.removeItem('jwtToken');
  }

  decodeToken() {
    // let token, base64Url, base64;
    const token: any = this.getToken();
    if (! token) {
      return false;
    }
    // base64Url = token.split('.')[1];
    // base64 = base64Url.replace('-', '+').replace('_', '/');
    // return JSON.parse(window.atob(base64));
    // console.log(JSON.parse(window.atob(token)));
    return JSON.parse(window.atob(token));

  }

  tokenActions() {
    let token;
    token = this.decodeToken();
    if (!token) {
      return false;
    }
    return token.actions;
  }

  tokenProfiles() {
    let token;
    token = this.decodeToken();
    if (!token) {
      return false;
    }

    return token.profiles;
  }

  tokenEmail() {
    let token;
    token = this.decodeToken();
    if (!token) {
      return false;
    }

    return token.email;
  }

  tokenName() {
    let token;
    token = this.decodeToken();
    if (!token) {
      return false;
    }

    return token.name;
  }

  tokenUserId() {
    let token;
    token = this.decodeToken();
    if (!token) {
      return false;
    }

    return token.sub;
  }

  tokenTSId() {
    let token;
    token = this.decodeToken();
    if (!token) {
      return false;
    }

    return token.id;
  }

  tokenExpiration() {

    let token;
    token = this.decodeToken();

    if (token.exp < Date.now() / 1000) {
      return true;
    } else {
      return false;
    }
  }
}
