import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { Observable } from 'rxjs';

import { LoginForm, LoginResponse } from 'app/auth/types/auth.types';
import {StorageService} from "../../../../core/storage/storage.service";
import { CookieService } from "ngx-cookie-service";
import { environment } from 'environments/environment';

@Injectable({
  providedIn: 'root'
})
export class LoginService {
  private apiUrl = environment.apiUrl;
  loginForm: LoginForm = {
    email: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    password: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
  };

  isRememberMeChecked = false;

  constructor(public httpClient: HttpClient,
              public storageService: StorageService,
              private cookieService: CookieService) { }

  /**
   * Attempt to login user
   * 
   * @returns { Observable<LoginResponse> }
   */
  attemptLogin(): Observable<LoginResponse> {
    const { email, password } = this.loginForm;

    return this.httpClient.post<LoginResponse>(this.apiUrl + 'api/v1/login', {
      email: email.value,
      password: password.value,
      temp_id: this.cookieService.get('session_id') ?? ''
    });
  }

  forgetPassword(email: string){
    return this.httpClient.post<LoginResponse>(this.apiUrl + 'api/v1/forgot-password', {
      email: email,
    }, {
      headers: {'Accept-Language': this.storageService.siteLanguage$.value, 'Content-Type': 'application/json' },
    });
  }

  resetPassword(data: any){
    return this.httpClient.post<LoginResponse>(this.apiUrl + 'api/v1/reset-password', {
      email: data.email.value,
      token: data.token.value,
      password: data.password.value,
      password_confirmation: data.confirmPassword.value
    }, {
      headers: {'Accept-Language': this.storageService.siteLanguage$.value, 'Content-Type': 'application/json' },
    });
  }
}
