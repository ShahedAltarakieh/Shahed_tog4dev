import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { OrangeMoneyPostBody } from '../types/orange-money.types';
import {StorageService} from "../../../../core/storage/storage.service";
import {CookieService} from "ngx-cookie-service";
import { environment } from 'environments/environment';
import { AuthService } from 'app/auth/services/auth.service';
@Injectable({
  providedIn: 'root'
})
export class OrangeMoneyService {
  private apiUrl = environment.apiUrl;
  orangeMoneyForm: any = {
    cliq_number: {
      value: '',
      errorMsg: '',
      isValid: false,
    }
  };
    
  constructor(public httpClient: HttpClient,
              public authService: AuthService,
              public cookieService: CookieService,
              public storageService: StorageService) { }

  /**
   * create new user
   * 
   * @returns 
   */
  send_verification_code(userData: any) {
    const loggedInUser = this.authService.loggedInUser;
    const { cliq_number } = this.orangeMoneyForm;

    const body: OrangeMoneyPostBody = {
      orange_number: cliq_number.value,
      code: this.cookieService.get('t4d') ?? null,
      first_name : (userData && userData.first_name) ? userData.first_name : loggedInUser.user.first_name,
      last_name : (userData && userData.last_name) ? userData.last_name : loggedInUser.user.last_name,
      email: (userData && userData.email) ? userData.email : loggedInUser.user.email,
      phone_number: (userData && userData.phone) ? userData.phone :loggedInUser.user.phone,
      temp_id: this.cookieService.get('session_id') ?? '',
    };
    
    return this.httpClient.post(this.apiUrl + 'api/v1/payment/orange-money', body, {
      headers: {'Accept-Language': this.storageService.siteLanguage$.value, 'Content-Type': 'application/json' },
    });
  }

  complete_order(otp: string, payment_id: string){
      const body: any = {
      id: payment_id,
      otp: otp,
      temp_id: this.cookieService.get('session_id') ?? '',
    };
    return this.httpClient.post(this.apiUrl + 'api/v1/payment/orange-money/pay', body, {
      headers: {'Accept-Language': this.storageService.siteLanguage$.value, 'Content-Type': 'application/json' },
    });
  }
}
