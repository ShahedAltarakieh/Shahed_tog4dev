import { Injectable } from '@angular/core';

import { LoginResponse } from '../types/auth.types';
import { CookieService } from 'ngx-cookie-service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  loggedInUser: any = null;
  is_loggedin: boolean = false;
  constructor(public cookieService: CookieService) {

  }

  /**
   * Set loggedIn user cookie
   */
  setLoggedInUserCookie() {
    if (this.cookieService.check('user')) {
      this.loggedInUser = JSON.parse(this.cookieService.get('user'));
    }
  }
}
