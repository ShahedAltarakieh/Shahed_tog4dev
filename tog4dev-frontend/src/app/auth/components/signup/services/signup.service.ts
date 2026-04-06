import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { RegisterUserPostBody } from '../types/signup.types';
import { SignupForm } from 'app/auth/types/auth.types';
import { environment } from 'environments/environment';
import { StorageService } from 'app/core/storage/storage.service';
@Injectable({
  providedIn: 'root'
})
export class SignupService {
  private apiUrl = environment.apiUrl;
  signupForm: SignupForm = {
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
    phone_code:{
      value: '',
      errorMsg: '',
      isValid: false,
    },
    phone: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    password: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    organizationName: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    country: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    city: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    birthday: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
  };

  privacyPolicyChecked = false;
  receiveEmailsChecked = false;
  
  constructor(public httpClient: HttpClient, private storageService: StorageService) { }

  /**
   * create new user
   * 
   * @returns 
   */
  registerUser() {
    const { first_name, last_name, birthday, city, country, email, organizationName, password, phone, phone_code } = this.signupForm;

    const body: RegisterUserPostBody = {
      first_name: first_name.value,
      last_name: last_name.value,
      birthday: birthday.value,
      country: country.value,
      phone: phone_code.value + phone.value,
      email: email.value,
      organization_name: organizationName.value,
      password: password.value,
    };

    return this.httpClient.post(this.apiUrl + 'api/v1/register', body, {
      headers: { 'Accept-Language': this.storageService.siteLanguage$.value, 'Content-Type': 'application/json' }
    });
  }
}
