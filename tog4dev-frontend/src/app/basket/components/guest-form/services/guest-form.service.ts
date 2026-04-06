import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import {GuestPostBody} from "../types/guest-form.types";
import {CookieService} from "ngx-cookie-service";
import { environment } from 'environments/environment';
import { StorageService } from 'app/core/storage/storage.service';
@Injectable({
  providedIn: 'root'
})
export class GuestFormService {
  private apiUrl = environment.apiUrl;
  guestForm: any = {
    first_name: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    last_name: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    email: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    phone_code: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    phone: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
  };
    
  constructor(public httpClient: HttpClient,
              private storageService: StorageService,
              public cookieService: CookieService) {
  }

  /**
   * create new user
   * 
   * @returns 
   */
  createGuestAccount() {
    const { first_name, last_name, email, phone, phone_code} = this.guestForm;
    const body: GuestPostBody = {
      first_name: first_name.value,
      last_name: last_name.value,
      email: email.value,
      phone: phone_code.value + phone.value,
      phone_code: phone_code.value,
      temp_id: this.cookieService.get('session_id') ?? '',
    };

    return this.httpClient.post(this.apiUrl + 'api/v1/payment/create-user', body, {
      headers: { 'Accept-Language': this.storageService.siteLanguage$.value, 'Content-Type': 'application/json' }
    });
  }
}
