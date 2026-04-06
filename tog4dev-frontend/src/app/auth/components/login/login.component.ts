import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import {JsonPipe, NgIf} from '@angular/common';
import {Router, RouterLink} from '@angular/router';

import { AuthService } from 'app/auth/services/auth.service';
import { CookieService } from 'ngx-cookie-service';
import { LoginService } from './services/login.service';

import { BasicInputComponent } from 'app/shared/components/inputs/components/basic-input/basic-input.component';
import {TranslatePipe} from "@ngx-translate/core";
import {BasketService} from "../../../shared/services/basket/basket.service";
import {StorageService} from "../../../core/storage/storage.service";

@Component({
    selector: 'app-login',
    imports: [
        BasicInputComponent,
        JsonPipe,
        TranslatePipe,
        NgIf,
        RouterLink
    ],
    templateUrl: './login.component.html',
    styleUrl: './login.component.scss'
})
export class LoginComponent {
  isLoading = false;
  error_message: string = '';

  signupRoutes: Record<'ar' | 'en', string> = {
    ar: '/ar/إنشاء-حساب',
    en: '/en/signup',
  };
  forgetRoutes: Record<'ar' | 'en', string> = {
    ar: '/ar/نسيت-كلمة-المرور',
    en: '/en/forget-password',
  };
  constructor(
    public loginService: LoginService, 
    public authService: AuthService,
    public cookieService: CookieService,
    public router: Router,
    public storageService: StorageService,
    public basketService: BasketService,
    public cdr: ChangeDetectorRef
  ) {}

  /**
   * remember-me check event handler
   * 
   * @param { Event } event 
   */
  onRememberMeCheck(event: Event) {
    this.loginService.isRememberMeChecked = (event.target as HTMLInputElement).checked;
  }

  /**
   * Login user and store user token
   * 
   * @returns { void }
   */
  loginUser(event: Event): void {
    event.preventDefault();
    this.isLoading = true;
    this.error_message = '';
    this.loginService.attemptLogin().subscribe({
      next: (value) => {
        this.isLoading = false;

        this.cookieService.set('user', JSON.stringify(value), 20, "/");
        this.authService.loggedInUser = value;
        this.authService.is_loggedin = true;
        this.basketService.quantity = value.cart;
        
        this.router.navigate(['/' + this.storageService.siteLanguage$.value]);
      },
      error: (error) => {
        this.isLoading = false;
        if(error.status == 401){
          this.error_message = 'invalid credentials';
        }
      }
    })
  }

  emailErrorHandler(errorMsg: string) {
    this.error_message = '';
    this.loginService.loginForm.email.errorMsg = errorMsg;
    this.loginService.loginForm.email.isValid = !errorMsg;
  }

  passwordErrorHandler(errorMsg: string) {
    this.error_message = '';
    this.loginService.loginForm.password.errorMsg = errorMsg;
    this.loginService.loginForm.password.isValid = !errorMsg;
  }
}
