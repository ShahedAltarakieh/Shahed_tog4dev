import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { CollectionTeamPostBody } from '../types/collection-team.types';
import {StorageService} from "../../../../core/storage/storage.service";
import {CookieService} from "ngx-cookie-service";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class CollectionService {
  private apiUrl = environment.apiUrl;
  collectionForm: any = {
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
    phone: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    city: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    address: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
  };
    
  constructor(public httpClient: HttpClient,
              public cookieService: CookieService,
              public storageService: StorageService) { }

  /**
   * create new user
   * 
   * @returns 
   */
  submitCollection() {
    const { first_name, last_name, phone, city, email, address } = this.collectionForm;

    const body: CollectionTeamPostBody = {
      first_name: first_name.value,
      last_name: last_name.value,
      email: email.value,
      phone: phone.value,
      city: city.value,
      address: address.value,
      temp_id: this.cookieService.get('session_id') ?? '',
    };
    return this.httpClient.post(this.apiUrl + 'api/v1/collection-team', body, {
      headers: {'Accept-Language': this.storageService.siteLanguage$.value, 'Content-Type': 'application/json' },
    });
  }
}
